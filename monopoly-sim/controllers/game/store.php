<?php


if (!isset($_POST['action'])) {
    header('location: /game');
    exit();
}

$action = $_POST['action'];

if (!isset($_SESSION['game']) && !in_array($action, ['new', 'load'])) {
    header('location: /');
    exit();
}

use Core\Validator;
use Core\Database;
use Game\Simulation;
use Game\StateArray;



$errors = [];
switch ($action){
    case 'buy':
        $id = $_POST['name'] ?? '';
        $purchase_price = $_POST['purchase-price'] ?? '';
        $upgrade_cost = $_POST['upgrade-cost'] ?? '';
        if (!Validator::validate_buy($id, $purchase_price, $upgrade_cost)) {
            $errors['message'] = 'Invalid input detected. Property name cannot contain special characters and must be less than 255 characters in length (12 characters is recommended). Purchase price and Renovation/Construction cost must be a number.';
            return view('game/buy.view.php', [
                'heading' => 'Buy Property',
                'errors' => $errors
            ]);
        }
        save_prev_state();
        $_SESSION['game']->buyProperty(trim($id), (float) $purchase_price, (float) $upgrade_cost);
        header('location: /game');
        exit();
        break;
    case 'sell':
        $id = $_POST['property-id'] ?? '';
        $sell_amount = $_POST['sell-price'] ?? '';
        $down_payment = $_POST['down-payment'] ?? '';
        $buyer_rate = $_POST['buyer-rate'] ?? '';
        $investor_rate = $_POST['investor-rate'] ?? '';
        $loan_term = $_POST['term'] ?? '';
        if (!Validator::validate_sell($id, $sell_amount, $down_payment, $buyer_rate, $investor_rate, $loan_term)) {
            $errors['message'] = 'Invalid input detected. Special characters are not allowed.';
            return view('game/sell.view.php', [
                'heading' => 'Sell Property',
                'errors' => $errors
            ]);
        }
        $id = trim($id);
        if (!in_array($id, $_SESSION['game']->getUnsoldProperties())) {
            $errors['message'] = 'Invalid input detected. Property not found.';
            return view('game/sell.view.php', [
                'heading' => 'Sell Property',
                'errors' => $errors
            ]);
        }
        $buyer_rate = rate((float) $buyer_rate);
        $investor_rate = rate((float) $investor_rate);
        save_prev_state();
        $_SESSION['game']->sellProperty($id, (float) $sell_amount, (float) $down_payment, $buyer_rate, $investor_rate, (int) $loan_term);
        header('location: /game');
        exit();
        break;
    case 'buyer-pay':
        $id = $_POST['property-id'] ?? '';
        $capital = $_POST['additional-capital'] ?? '';
        if (!Validator::validate_add_capital($id, $capital)) {
            $errors['message'] = 'Invalid input detected. Property cannot contain special characters and must be less than 255 characters in length (12 characters is recommended). Additional capital must be a number.';
            return view('game/buyer-pay.view.php', [
                'heading' => 'Buyer Pays Extra',
                'errors' => $errors
            ]);
        }
        $id = trim($id);
        if (!in_array($id, $_SESSION['game']->getSoldProperties())) {
            $errors['message'] = 'Invalid input detected. Property not found.';
            return view('game/buyer-pay.view.php', [
                'heading' => 'Buyer Pays Extra',
                'errors' => $errors
            ]);
        }
        $outstanding_principle = $_SESSION['game']->getBuyerOutstandingPrinciple($id);
        $capital = (float) $capital;
        if ($outstanding_principle < $capital) {
            $errors['message'] = 'Additional payment from buyer cannot exceed principle owed on the home.';
            return view('game/buyer-pay.view.php', [
                'heading' => 'Buyer Pays Extra',
                'errors' => $errors
            ]);
        }
        save_prev_state();
        $_SESSION['game']->allocateBuyerCapital($id, $capital);
        header('location: /game');
        exit();
        break;
    case 'pay-investor':
        $id = $_POST['property-id'] ?? '';
        $capital = $_POST['additional-capital'] ?? '';
        if (!Validator::validate_add_capital($id, $capital)) {
            $errors['message'] = 'Invalid input detected. Property cannot contain special characters and must be less than 255 characters in length (12 characters is recommended). Additional capital must be a number.';
            return view('game/buyer-pay.view.php', [
                'heading' => 'Buyer Pays Extra',
                'errors' => $errors
            ]);
        }
        $id = trim($id);
        if (!in_array($id, $_SESSION['game']->getSoldProperties())) {
            $errors['message'] = 'Invalid input detected. Property not found.';
            return view('game/pay-investor.view.php', [
                'heading' => 'Pay Investor Extra',
                'errors' => $errors
            ]);
        }
        $outstanding_principle = $_SESSION['game']->getInvestorOutstandingPrinciple($id);
        $capital = (float) $capital;
        if ($outstanding_principle < $capital) {
            $errors['message'] = 'Additional payment to investor cannot exceed principle owed.';
            return view('game/pay-investor.view.php', [
                'heading' => 'Pay Investor Extra',
                'errors' => $errors
            ]);
        }
        $free_capital = round($_SESSION['game']->free_capital, 2);
        if ($free_capital < $capital) {
            $errors['message'] = 'Additional payment to investor cannot exceed free capital.';
            return view('game/pay-investor.view.php', [
                'heading' => 'Pay Investor Extra',
                'errors' => $errors
            ]);
        }
        save_prev_state();
        $_SESSION['game']->allocateInvestorCapital($id, $capital);
        header('location: /game');
        exit();
        break;
    case 'add-capital':
        $id = $_POST['event-name'] ?? '';
        $capital = $_POST['additional-capital'] ?? '';
        if (!Validator::validate_add_capital($id, $capital)) {
            $errors['message'] = 'Invalid input detected. Name cannot contain special characters and must be less than 255 characters in length (12 characters is recommended). Additional capital must be a number.';
            return view('game/add-capital.view.php', [
                'heading' => 'Add Capital',
                'errors' => $errors
            ]);
        }
        save_prev_state();
        $_SESSION['game']->acquireExtraCapital(trim($id), (float) $capital);
        header('location: /game');
        exit();
        break;
    case 'mark-capital':
        update_marked_capital();
        header('location: /game');
        exit();
        break;
    case 'next':
        save_prev_state();
        $_SESSION['game']->step();
        header('location: /game');
        exit();
        break;
    case 'save':
        $name = $_POST['save-label'] ?? '';
        $uid = $_SESSION['user']['user_id'] ?? '';
        $state = $_SESSION['game']->getJson();
        if (!Validator::validate_save($name, $uid, $state)) {
            $errors['message'] = 'Invalid input detected. Name cannot contain special characters and must be less than 255 characters in length.';
            return view('game/save-game.view.php', [
                'heading' => 'Save Game',
                'errors' => $errors
            ]);
        }
        $db_path = base_path('db/monopoly-sim.db');
        $dsn = 'sqlite:' . $db_path;
        $db = new Database($dsn);
        $db->saveGame($name, (int) $uid, $state);
        header('location: /');
        exit();
        break;
    case 'load':
        $db_path = base_path('db/monopoly-sim.db');
        $dsn = 'sqlite:' . $db_path;
        $db = new Database($dsn);
        $id = (int) $_POST['id'] ?? '';
        $state = $db->getGameState($_SESSION['user']['user_id'], $id);
        $db = null;
        if ($state) {
            autosave();
            $_SESSION['game'] = Simulation::load($state['state']);
            header('location: /game');
            exit();
        }
        $errors['message'] = 'Invalid input detected. Game not found.';
        return view('game/load-game.view.php', [
            'heading' => 'Load Game',
            'errors' => $errors
        ]);
        exit();
        break;
    case 'new':
        $month = $_POST['month'] ?? '';
        $year = $_POST['year'] ?? '';
        $d = DateTime::createFromFormat('d-m-Y', "01-{$month}-{$year}");
        $ts = 0;
        if ($d === false) {
            header('location: /');
            exit();
            break;
        } else {
            $ts = $d->getTimestamp();
        }
        autosave();
        $simulation = new Simulation();
        $_SESSION['game'] = $simulation;
        unset($_SESSION['prev_state']);
        $_SESSION['game']->setStartDate($ts);
        header('location: /game');
        exit();
        break;
    case 'back':
        if (isset($_SESSION['prev_state'])) {
            if (!$_SESSION['prev_state']->isEmpty()) {
                $_SESSION['game'] = Simulation::load($_SESSION['prev_state']->get());
            }
        }
        header('location: /game');
        exit();
        break;
    default:
        header('location: /');
        exit();
        break;
}
