<?php


use Core\Validator;
use Core\Database;


$email = $_POST['email'];
$pass = $_POST['password'];
$repeat_pass = $_POST['repeat-password'];


// Validate input
$errors = [];
if (!Validator::email($email)) {
    $errors['email'] = 'Invalid email address, or address already exists.';
}

if (!Validator::password($pass)) {
    $errors['password'] = 'Password must be between 8 and 32 characters.';
}

if (strcmp($pass, $repeat_pass) !== 0) {
    $errors['repeat-password'] = 'Passwords do not match.';
}

if (! empty($errors)) {
    return view('registration/create.view.php', [
        'errors' => $errors
    ]);
}

$db_path = base_path('db/monopoly-sim.db');
$dsn = 'sqlite:' . $db_path;
$db = new Database($dsn);
$user_data = $db->getUserData($email);

if ($user_data) {
    $errors['email'] = 'Invalid email address, or address already exists.';
    return view('registration/create.view.php', [
        'errors' => $errors
    ]);
} else {
    $db->createUser($email, $pass);
    $user_data = $db->getUserData($email);
    login([
        'email' => $user_data['email'],
        'user_id' => $user_data['user_id']
    ]);
    header('location: /');
    exit();
}
