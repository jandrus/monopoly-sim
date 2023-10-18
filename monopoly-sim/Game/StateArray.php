<?php


namespace Game;


const MAX_STATES = 12;


class StateArray {

    private $states;

    function __construct() {
        $this->states = [];
    }

    public function add(mixed $element): void {
        if (sizeof($this->states) >= MAX_STATES) {
            array_shift($this->states);
        }
        array_push($this->states, $element);
    }

    public function get(): mixed {
        if ($this->isEmpty()) {
            return null;
        }
        return array_pop($this->states);
    }

    public function isEmpty(): bool {
        return count($this->states) == 0;
    }


}
