<?php


namespace Core\Middleware;

class Authenticated {
    public function handle() {
        if (!array_key_exists('user', $_SESSION)) {
            header('location: /login');
            exit();
        }
    }
}
