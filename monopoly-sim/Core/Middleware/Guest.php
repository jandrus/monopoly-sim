<?php


namespace Core\Middleware;

class Guest {
    public function handle() {
        if (array_key_exists('user', $_SESSION)) {
            header('location: /');
            exit();
        }
    }
}
