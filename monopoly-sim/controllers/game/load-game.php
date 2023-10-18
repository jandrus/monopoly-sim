<?php


$games = array_reverse($_SESSION['user']['saved_games']);
if ($games) {
    view('game/load-game.view.php', [
        'heading' => 'Load Game'
    ]);
} else {
    header('location: /');
    exit();
}
