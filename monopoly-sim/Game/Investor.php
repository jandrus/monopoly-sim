<?php


namespace Game;

class Investor {
    public float $loan_amount;                 // amount of capital borrowed
    public float $interest_rate;               // ANNUAL interest rate capital is borrowed at
    public int $loan_term;                     // number of MONTHS capital is borrowed
    public int $duration = 0;                  // number of MONTHS capital HAS BEEN borrowed for
    public float $additional_principle = 0.0;  // additional principle paid by the buyer for the property


    function __construct(float $loan_amount, float $interest_rate, int $loan_term) {
        $this->loan_amount = $loan_amount;
        $this->interest_rate = $interest_rate;
        $this->loan_term = $loan_term * 12;
    }

    public static function load(array $params): Investor {
        $instance = new self((float) $params['loan_amount'], $params['interest_rate'], $params['loan_term'] / 12);
        $instance->duration = $params['duration'];
        $instance->additional_principle = (float) $params['additional_principle'];
        return $instance;
    }

    public function getDuration(): int {
        return $this->duration;
    }

    public function getInfo(): array {
        return [$this->loan_amount, $this->interest_rate, $this->getMonthlyPayment()];
    }

    public function additionalPrincipleEvent(float $principle): void {
        if ($this->isActive()) {
            if ($principle <= $this->getOutstandingPrinciple()) {
                $this->additional_principle += $principle;
            } else {
                $this->additional_principle += $this->getOutstandingPrinciple();
            }
        }
    }

    public function getMonthlyPayment(): float {
        $pmt = 0.0;
        if ($this->isActive()) {
            $r = $this->interest_rate / 12;
            $pmt = round($this->loan_amount * (($r * ((1 + $r) ** $this->loan_term)) / (((1 + $r) ** $this->loan_term) - 1)), 2);
            $outstanding = $this->getOutstandingPrinciple();
            if ($pmt > $outstanding) {
                $pmt = $outstanding;
            }
        }
        return $pmt;
    }

    public function getOutstandingPrinciple(): float {
        $outstanding = $this->loan_amount - $this->additional_principle;
        if ($this->duration > 0) {
            $r = $this->interest_rate / 12;
            $outstanding -= round($this->loan_amount - $this->loan_amount * ((((1 + $r) ** $this->loan_term) - ((1 + $r) ** $this->duration)) / (((1 + $r) ** $this->loan_term) - 1)), 2);
            if ($outstanding < 0) {
                $outstanding = 0.0;
            }
        }
        return $outstanding;
    }

    public function step(): void {
        if ($this->isActive()) {
            $this->duration ++;
        }
    }

    private function isActive(): bool {
        return $this->getOutstandingPrinciple() > 0;
    }
}
