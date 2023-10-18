<?php

use Core\Database;
use Game\Simulation;
use Game\StateArray;

const EMPTY_GAME_STATE = '{"debt":0,"free_capital":0,"month":0,"investors":[],"properties":[],"unsold_properties":[],"free_capital_events":[],"';
const MAX_PREV_STATES = 10;


// dump & die
function dd($var): void {
    echo "<pre>";
    var_dump($var);
    echo "</pre>";
    die();
}

// dump dont die
function dump($var) {
    echo "<pre>";
    var_dump($var);
    echo "</pre>";
}

function date_f(): string {
   return date('dMY_Hi\Z', time());
}

// Is uri a given value?
function uriIs(string $val): bool {
    return $_SERVER['REQUEST_URI'] == $val;
}

function sanatize($str): string {
    $str = htmlspecialchars($str);
    return $str;
}

// return base path for given path
function base_path(string $path): string {
   return BASE_PATH . $path;
}

// Requires view for a given path
function view(string $path, array $attributes=[]): void {
    extract($attributes);
    require(base_path('views/' . $path));
}

function login(array $params): void {
    $_SESSION['user'] = $params;
    session_regenerate_id(true);
}

function logout(): void {
    $_SESSION = [];
    session_destroy();
    $params = session_get_cookie_params();
    setcookie('PHPSESSID', '', time() - 86400, $params['path'], $params['domain']);
}

function strip_email(string $email): string {
    return strstr($email, '@', true);
}

function money(int|float $val): string {
    return '$' . number_format($val, 2, '.', ',');
}

function rate(float $rate): float {
    if ($rate > 1) {
        return $rate / 100;
    }
    return $rate;
}

function rate_str(float $rate): string {
    return number_format($rate * 100, 2, '.') . '%';
}

function get_state(): string {
    if (isset($_SESSION['user']) && isset($_SESSION['game'])) {
        return explode('game_date', $_SESSION['game']->getJson())[0];
    }
    return EMPTY_GAME_STATE;
}

function autosave(): void {
    if (isset($_SESSION['user']) && isset($_SESSION['game'])) {
        $state = get_state();
        if ($state && $state != EMPTY_GAME_STATE) {
            $date_f = date_f();
            $name = "autosave-{$date_f}";
            $uid = $_SESSION['user']['user_id'] ?? '';
            $state = $_SESSION['game']->getJson();
            $db_path = base_path('db/monopoly-sim.db');
            $dsn = 'sqlite:' . $db_path;
            $db = new Database($dsn);
            $db->saveGame($name, (int) $uid, $state);
            $db = null;
        }
    }
}

function save_prev_state(): void {
    if (isset($_SESSION['user']) && isset($_SESSION['game'])) {
        $state = get_state();
        if ($state && $state != EMPTY_GAME_STATE) {
            $state = $_SESSION['game']->getJson();
            if (!isset($_SESSION['prev_state'])) {
                $_SESSION['prev_state'] = new StateArray();
            }
            $_SESSION['prev_state']->add($state);
        }
    }
}

function update_marked_capital(): void {
    if (isset($_SESSION['user']) && isset($_SESSION['game'])) {
        $marked_events = $_POST['marked'] ?? [];
        $capital_events = $_SESSION['game']->getFreeCapitalEvents();
        $new_free_capital_events = [];
        foreach ($capital_events as $event) {
            if (in_array($event[0], $marked_events)) {
                array_push($new_free_capital_events, [$event[0], $event[1], true]);
            } else {
                array_push($new_free_capital_events, [$event[0], $event[1], false]);
            }
        }
        $_SESSION['game']->setFreeCapitalEvents($new_free_capital_events);
    }
}
