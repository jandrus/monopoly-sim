<?php


use Core\Validator;
use Core\Database;


$email = $_POST['email'];
$pass = $_POST['password'];

$errors = [];
if (!Validator::email($email)) {
    $errors['email'] = 'Invalid email address, or address does not exists.';
}

if (!Validator::password($pass)) {
    $errors['password'] = 'Password must be between 8 and 32 characters.';
}

if (! empty($errors)) {
    return view('sessions/create.view.php', [
        'errors' => $errors
    ]);
}

$db_path = base_path('db/monopoly-sim.db');
$dsn = 'sqlite:' . $db_path;
$db = new Database($dsn);
$user_data = $db->getUserData($email);

if ($user_data) {
    if (password_verify($pass, $user_data['password'])) {
        login([
            'email' => $user_data['email'],
            'user_id' => $user_data['user_id']
        ]);
        header('location: /');
        exit();
    } else {
        $errors['password'] = 'Incorrect password.';
        return view('sessions/create.view.php', [
            'errors' => $errors
        ]);
    }
} else {
    $errors['email'] = 'Invalid email address, or address does not exists.';
    return view('sessions/create.view.php', [
        'errors' => $errors
    ]);
}
