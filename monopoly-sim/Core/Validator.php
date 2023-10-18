<?php


namespace Core;

class Validator {

    protected const PROPERTY_FILTER = '/[:;\'\"^£$%&*()}{@#~?><>|=+¬]/';

    public static function valid_json(string $str): bool {
        json_decode($str);
        return json_last_error() === JSON_ERROR_NONE;
    }

    public static function valid_property_name(string $val, $min=1, $max=255): bool {
        $val = trim($val);
        if (preg_match(self::PROPERTY_FILTER, $val)) {
            return false;
        }
        return strlen($val) >= $min && strlen($val) < $max;
    }

    public static function valid_number(string $val): bool {
        if (filter_var($val, FILTER_VALIDATE_FLOAT) || is_numeric($val)) {
            return true;
        }
        return false;
    }

    public static function valid_int(string $val): bool {
        if (filter_var($val, FILTER_VALIDATE_INT) || is_numeric($val)) {
            return true;
        }
        return false;
    }

    public static function password(#[\SensitiveParameter] string $val, $min=8, $max=32): bool {
        $val = trim($val);
        // FIXME
        return strlen($val) >= $min && strlen($val) < $max;
    }

    public static function email(string $val): bool {
        if (filter_var($val, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }

    public static function validate_buy(string $id, string $price, string $cost): bool {
        if (!$id || !$price) {
            return false;
        }
        if (!is_numeric($cost)) {
            return false;
        }
        if (!self::valid_property_name($id) || !self::valid_number($price) || !self::valid_number($cost)) {
            return false;
        }
        return true;
    }

    public static function validate_sell(string $id, string $sell, string $down, string $buy_rt, string $inv_rt, string $term): bool {
        if (!$id || !$sell || !$buy_rt || !$inv_rt || !$term) {
            return false;
        }
        if (!is_numeric($down)) {
            return false;
        }
        if (!self::valid_property_name($id) || !self::valid_number($sell) || !self::valid_number($down) || !self::valid_number($buy_rt) || !self::valid_number($inv_rt) || !self::valid_int($term)) {
            return false;
        }
        return true;
    }

    public static function validate_add_capital(string $id, string $capital): bool {
        if (!$id || !$capital) {
            return false;
        }
        if (!self::valid_property_name($id) || !self::valid_number($capital)) {
            return false;
        }
        return true;
    }

    public static function validate_save(string $name, string|int $uid, string $state): bool {
        if (!$name || !$uid || !$state) {
            return false;
        }
        if (!self::valid_property_name($name) || !self::valid_int($uid) || !self::valid_json($state)) {
            return false;
        }
        return true;
    }

}
