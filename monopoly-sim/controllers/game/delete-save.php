<?php

$games = $_SESSION['user']['saved_games'] ?? [];

if ($games) {
    view('/game/delete-save.view.php', [
        'heading' => 'Delete Saved Games'
    ]);
} else {
    view('index.view.php', [
        'heading' => 'Dashboard'
    ]);
}
