<?php
    $conf = parse_ini_file(base_path('config/config.ini'));
    require('partials/head.php');
    require('partials/nav.php');
    require('partials/header.php');

    use Core\Database;
?>

<main>
    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
        <?php
            if (isset($_SESSION['user'])) {
                $db_path = base_path('db/monopoly-sim.db');
                $dsn = 'sqlite:' . $db_path;
                $db = new Database($dsn);
                $games = $db->getGames((int) $_SESSION['user']['user_id']);
                $_SESSION['user']['saved_games'] = $games;
                require('partials/game/auth-dashboard.php');
            } else {
                require('partials/guest-dashboard.php');
            }
        ?>
    </div>
</main>

<?php require('partials/footer.php') ?>
