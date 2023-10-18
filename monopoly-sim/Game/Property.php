<?php


namespace Game;

class Property {
    public float $sell_amount;                 // amount the buyer pays for the property
    public float $down_payment;                // money down the buyer pays initially
    public float $interest_rate;               // interest rate the buyer is paying
    public int $loan_term;                     // MONTHS the buyer agrees to (loan term * 12)
    public float $loan_amount;                 // amount borrowed by the buyer
    public int $duration = 0;                  // MONTHS the buyer has paid the mortgage
    public float $additional_principle = 0.0;  // additional principle paid by the buyer for the property

    function __construct(float $sell_amount, float $down_payment, float $interest_rate, int $loan_term) {
        $this->sell_amount = $sell_amount;
        $this->down_payment = $down_payment;
        $this->interest_rate = $interest_rate;
        $this->loan_term = $loan_term * 12;
        $this->loan_amount = $this->sell_amount - $this->down_payment;
    }

    public static function load(array $params): Property {
        $instance = new self((float) $params['sell_amount'], (float) $params['down_payment'], $params['interest_rate'], $params['loan_term'] / 12);
        $instance->duration = $params['duration'];
        $instance->additional_principle = (float) $params['additional_principle'];
        return $instance;
    }

    public function getInfo(): array {
        return [$this->sell_amount, $this->down_payment, $this->interest_rate, $this->getMortgagePayment()];
    }

    public function getDuration(): int {
        if ($this->isActive()) {
            return $this->duration;
        }
        return 0;
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

    public function getMortgagePayment(): float {
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
            $this->duration++;
        }
    }

    private function isActive(): bool {
        return $this->getOutstandingPrinciple() > 0;
    }
}
