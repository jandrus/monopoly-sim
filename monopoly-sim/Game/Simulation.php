<?php


namespace Game;

require base_path('Game/Property.php');
require base_path('Game/Investor.php');

class Simulation {
    // FIXME -> not all public, but private fucks up json
    public $debt = 0.0;                 // amount of debt
    public $free_capital = 0.0;         // free capital to be allocated
    public $month = 0;                  // duration of simulation -> num of months the simulation has been running
    public $investors = [];             // [id => investor, ...]
    public $properties = [];            // [id => property, ...]
    public $unsold_properties = [];     // [id => (float)debt, ...]
    public $free_capital_events = [];   // array to hold events (description, amount) that add to free_capital
    public $game_date = 31536000;       // Starting point TS


    public function getDate(): string {
        $d = new \DateTime();
        $d->setTimestamp($this->game_date);
        return $d->format('M Y');
    }

    public function setStartDate(int $ts): void {
        $this->game_date = $ts;
    }

    public function getJson(bool $pretty=false): string|false {
        if ($pretty) {
            return json_encode($this, JSON_PRETTY_PRINT);
        }
        return json_encode($this);
    }

    public static function load(string $state): Simulation {
        $params = json_decode($state, true);
        $instance = new self();
        $instance->debt = (float) $params['debt'];
        $instance->free_capital = (float) $params['free_capital'];
        $instance->month = $params['month'];
        foreach ($params['investors'] as $key => $value) {
            $instance->investors[$key] = Investor::load($value);
        }
        foreach ($params['properties'] as $key => $value) {
            $instance->properties[$key] = Property::load($value);
        }
        foreach ($params['unsold_properties'] as $key => $value) {
            $instance->unsold_properties[$key] = $value;
        }
        $instance->free_capital_events = [];
        foreach ($params['free_capital_events'] as $value) {
            array_push($instance->free_capital_events, $value);
        }
        $instance->game_date = $params['game_date'];
        return $instance;
    }

    public function getMonth(): int {
        return $this->month;
    }

    public function getPropertyInfo(string $id): array {
        if (array_key_exists($id, $this->properties)) {
            $property_info = $this->properties[$id]->getInfo();
            $investor_info = $this->investors[$id]->getInfo();
            $property_principle = $this->properties[$id]->getOutstandingPrinciple();
            $investor_principle = $this->investors[$id]->getOutstandingPrinciple();
            $loan_term = $this->properties[$id]->loan_term;
            $duration = $this->properties[$id]->getDuration();
            return array_merge($property_info, $investor_info, [$investor_principle, $property_principle, $loan_term, $duration]);
        }
        if (array_key_exists($id, $this->unsold_properties)) {
            return $this->unsold_properties[$id];
        }
    }

    public function getFreeCapitalEvents(): array {
        return $this->free_capital_events;
    }

    public function getSoldProperties(): array {
        return array_keys($this->properties);
    }

    public function getUnsoldProperties(): array {
        return array_keys($this->unsold_properties);
    }

    public function getExpenses(): float {
        $expenses = 0.0;
        foreach ($this->investors as $investor) {
            if ($investor->getDuration() > 0) {
                $expenses += $investor->getMonthlyPayment();
            }
        }
        return $expenses;
    }

    public function getIncome(): float {
        $income = 0.0;
        foreach ($this->properties as $property) {
            if ($property->getDuration() > 0) {
                $income += $property->getMortgagePayment();
            }
        }
        return $income;
    }

    public function getBuyerOutstandingPrinciple(string $id): float {
        return round($this->properties[$id]->getOutstandingPrinciple(), 2);
    }

    public function getInvestorOutstandingPrinciple(string $id): float {
        return round($this->investors[$id]->getOutstandingPrinciple(), 2);
    }

    public function acquireExtraCapital(string $description, float $capital): void {
        $this->free_capital += $capital;
        array_push($this->free_capital_events, [$description, $capital, false]);
    }

    public function markExtraCapital(string $id): void {
        $i = 0;
        foreach ($this->free_capital_events as $event) {
            if ($event[0] == $id) {
                $this->free_capital_events[$i][2] = true;
            }
            $i++;
        }
    }

    public function setFreeCapitalEvents(array $new): void {
        $this->free_capital_events = $new;
    }

    public function buyProperty(string $id, float $purchase_price, float $upgrade_cost): void {
        $this->debt += $purchase_price + $upgrade_cost;
        $this->unsold_properties += [$id => array($purchase_price, $upgrade_cost)];
    }

    public function allocateBuyerCapital(string $id, float $capital): void {
        $this->free_capital += $capital;
        $this->free_capital = round($this->free_capital, 2);
        $this->properties[$id]->additionalPrincipleEvent($capital);
        $this->removeSettled($id);
    }

    public function allocateInvestorCapital(string $id, float $capital): void {
        $this->free_capital -= $capital;
        $this->free_capital = round($this->free_capital, 2);
        $this->investors[$id]->additionalPrincipleEvent($capital);
        $this->removeSettled($id);
    }

    public function sellProperty(string $id,
                                 float $sell_amount,
                                 float $down_payment,
                                 float $buyer_rate,
                                 float $investor_rate,
                                 int $loan_term): void {
        $property_debt = $this->unsold_properties[$id][0] + $this->unsold_properties[$id][1];
        $this->debt -= $property_debt;
        unset($this->unsold_properties[$id]);
        $this->properties += [$id => new Property($sell_amount, $down_payment, $buyer_rate, $loan_term)];
        $this->investors += [$id => new Investor($property_debt, $investor_rate, $loan_term)];
    }

    public function step(): void {
        $expenses = 0.0;
        $income = 0.0;
        foreach ($this->properties as $property) {
            $income += $property->getMortgagePayment();
            $property->step();
        }
        foreach ($this->investors as $investor) {
            $expenses += $investor->getMonthlyPayment();
            $investor->step();
        }
        if ($expenses > $income) {
            $this->debt += round($expenses - $income, 2);
        } else {
            $this->free_capital += round($income - $expenses, 2);
        }
        $this->month++;
        $this->updateDate(1);
        foreach ($this->getSoldProperties() as $id) {
            $this->removeSettled($id);
        }
    }

    private function updateDate(int $months): void {
        $d = new \DateTime();
        $d->setTimestamp($this->game_date);
        $d->modify("+{$months} month");
        $this->game_date = $d->getTimestamp();
    }

    private function removeSettled(string $id): void {
        $buyer_prin = $this->getBuyerOutstandingPrinciple($id);
        $inv_prin = $this->getInvestorOutstandingPrinciple($id);
        if ($buyer_prin == 0 && $inv_prin == 0) {
            unset($this->properties[$id]);
            unset($this->investors[$id]);
        }
    }
}
