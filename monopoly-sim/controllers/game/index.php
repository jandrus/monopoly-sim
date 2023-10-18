<?php


if (!isset($_SESSION['game'])) {
    view('game/date.view.php', [
        'heading' => 'Start Date for Game'
    ]);
} else {
    view('game/index.view.php', [
        'heading' => 'Monopoly Simulator'
    ]);
}
