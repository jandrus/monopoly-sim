<?php
    if(!isset($_SESSION['game'])) {
        return;
    }

    use Game\Simulation;

    $sold_properties = array_reverse($_SESSION['game']->getSoldProperties());
?>
<?php if(sizeof($sold_properties) > 0) : ?>
    <div class="container">
        <h1 class='text-2xl font-bold text-center'>Sold Properties</h1>
        <table class="text-left w-full border border-gray-800">
            <thead class="bg-black flex text-white w-full">
                <tr class="flex w-full mb-2">
                    <th class="p-1 w-1/12 text-xs overflow-hidden">Name</th>
                    <th class="p-1 w-1/12 text-xs overflow-hidden">Sell Price</th>
                    <th class="p-1 w-1/12 text-xs overflow-hidden">Buyer Down</th>
                    <th class="p-1 w-1/12 text-xs overflow-hidden">Buyer RT</th>
                    <th class="p-1 w-1/12 text-xs overflow-hidden">Mortgage PMT</th>
                    <th class="p-1 w-1/12 text-xs overflow-hidden">Investor Loan</th>
                    <th class="p-1 w-1/12 text-xs overflow-hidden">Investor RT</th>
                    <th class="p-1 w-1/12 text-xs overflow-hidden">Investor PMT</th>
                    <th class="p-1 w-1/12 text-xs overflow-hidden">Investor PRIN</th>
                    <th class="p-1 w-1/12 text-xs overflow-hidden">Buyer PRIN</th>
                    <th class="p-1 w-1/12 text-xs overflow-hidden">Loan Term</th>
                    <th class="p-1 w-1/12 text-xs overflow-hidden">Month</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-400 bg-grey-light flex flex-col items-center justify-between overflow-y-scroll w-full" style="max-height: 11vh;">
                <?php
                    foreach ($sold_properties as $property) {
                        $info = $_SESSION['game']->getPropertyInfo($property);
                        $sell_price = money($info[0]);
                        $buyer_down = money($info[1]);
                        $buyer_rate = rate_str($info[2]);
                        $mort = money($info[3]);
                        $loan = money($info[4]);
                        $investor_rate = rate_str($info[5]);
                        $investor_pmt = money($info[6]);
                        $investor_prin = money($info[7]);
                        $buyer_prin = money($info[8]);
                        echo "<tr class=\"divide-x divide-gray-400 flex w-full mb-2\">";
                        echo "<td class=\"text-xs p-1 w-1/12 overflow-hidden\">{$property}</td>";
                        echo "<td class=\"text-xs p-1 w-1/12 overflow-hidden\">{$sell_price}</td>";
                        echo "<td class=\"text-xs p-1 w-1/12 overflow-hidden\">{$buyer_down}</td>";
                        echo "<td class=\"text-xs p-1 w-1/12 overflow-hidden\">{$buyer_rate}</td>";
                        echo "<td class=\"text-xs p-1 w-1/12 overflow-hidden\">{$mort}</td>";
                        echo "<td class=\"text-xs p-1 w-1/12 overflow-hidden\">{$loan}</td>";
                        echo "<td class=\"text-xs p-1 w-1/12 overflow-hidden\">{$investor_rate}</td>";
                        echo "<td class=\"text-xs p-1 w-1/12 overflow-hidden\">{$investor_pmt}</td>";
                        echo "<td class=\"text-xs p-1 w-1/12 overflow-hidden\">{$investor_prin}</td>";
                        echo "<td class=\"text-xs p-1 w-1/12 overflow-hidden\">{$buyer_prin}</td>";
                        echo "<td class=\"text-xs p-1 w-1/12 overflow-hidden\">{$info[9]}</td>";
                        echo "<td class=\"text-xs p-1 w-1/12 overflow-hidden\">{$info[10]}</td>";
                        echo "</tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>
<?php endif ?>
