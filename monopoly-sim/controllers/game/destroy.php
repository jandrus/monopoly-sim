<?php


use Core\Database;

if (!isset($_SESSION['user']['user_id'])) {
    header('location: /');
    exit();
}

$uid = $_SESSION['user']['user_id'];

$db_path = base_path('db/monopoly-sim.db');
$dsn = 'sqlite:' . $db_path;
$db = new Database($dsn);

foreach ($_POST['marked'] as $save) {
    $db->removeGame($save, $uid);
}

header('location: /');
exit();
