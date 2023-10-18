<?php
    $conf = parse_ini_file(base_path('config/config.ini'));
    require(base_path('views/partials/head.php'));
    require(base_path('views/partials/nav.php'));
    require(base_path('views/partials/header.php'));

    use Game\Simulation;

    if (isset($_SESSION['game'])) {
        $simulation = $_SESSION['game'];
    } else {
        $simulation = new Simulation();
        $_SESSION['game'] = $simulation;
    }
?>

<main>
    <div class="mx-auto max-w-7xl py-7 sm:px-6 lg:px-8">
        <?php require(base_path('views/partials/game/game-parameters-table.php')) ?>
        <div class='py-3'>
            <?php require(base_path('views/partials/game/unsold-properties-table.php')) ?>
        </div>
        <div class='py-3'>
            <?php require(base_path('views/partials/game/sold-properties-table.php')) ?>
        </div>
        <div class='py-3'>
            <?php require(base_path('views/partials/game/additional-capital-table.php')) ?>
        </div>
        <?php require(base_path('views/partials/game/game-buttons.php')) ?>
    </div>
</main>

<?php require(base_path('views/partials/footer.php')) ?>
