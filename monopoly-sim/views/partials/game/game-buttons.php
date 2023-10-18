<?php
    use Game\Simulation;
    use Game\StateArray;
    $simulation = $_SESSION['game'];
?>


<div class='py-3'>
    <a href="game/buy" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 mr-2 rounded" name="buy" title="Buy a property. Property will be added to Unsold Properties.">
        Buy Property
    </a>
    <?php
        $num_unsold = count($simulation->getUnsoldProperties());
        if ($num_unsold > 0) {
           echo "<a href=\"game/sell\" class=\"bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 mr-2 rounded\" name=\"sell\" title=\"Sell an unsold property and finance it with an investor.\">Sell Property</a>";
        }
    ?>
    <?php
        $num_sold = count($simulation->getSoldProperties());
        if ($num_sold > 0) {
            echo "<a href=\"game/buyer-pay\" class=\"bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 mr-2 rounded\" name=\"buyer-pay\" title=\"Used if a buyer pays extra on their mortgage. Reduces Buyer PRIN in Sold Properties table.\">Buyer Pay Extra</a>";
            echo "<a href=\"game/pay-investor\" class=\"bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 mr-2 rounded\" name=\"pay-investor\" title=\"Pay investor extra capital toward loan. Reduces Investor PRIN in Sold Properties table.\">Pay Investor Extra</a>";
        }
    ?>
    <a href="game/add-capital" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 mr-2 rounded" name="add-capital" title="Add additional capital. Creates an entry in Additional Capital Events table and increases or decreases your Free Capital.">
        Add Capital
    </a>
</div>
<div class='clearfix'>
    <form method="POST" action="/game">
        <input hidden name="action" value="next">
        <button type="submit" class="float-left bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 mr-2 rounded">Next Month</button>
    </form>
    <?php if (isset($_SESSION['prev_state'])) : ?>
        <?php if (!$_SESSION['prev_state']->isEmpty()) : ?>
            <form method="POST" action="/game">
                <input hidden name="action" value="back">
                <button type="submit" class="float-left bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 mr-2 rounded">Back</button>
            </form>
        <?php endif ?>
    <?php endif ?>
    <a href="game/save" class="float-right bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 ml-2 rounded">
        Save
    </a>
    <a href="game/load" class="float-right bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 ml-2 rounded">
        Load
    </a>
</div>
