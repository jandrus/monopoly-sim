<?php
    if(!isset($_SESSION['game'])) {
        return;
    }

    use Game\Simulation;

    $unsold_properties = array_reverse($_SESSION['game']->getUnsoldProperties());
?>
<?php if(sizeof($unsold_properties) > 0) : ?>
    <div class="container">
        <h1 class='text-2xl font-bold text-center'>Unsold Properties</h1>
        <table class="text-left w-full border border-gray-800">
            <thead class="bg-black flex text-white w-full">
                <tr class="flex w-full mb-2">
                    <th class="p-1 w-1/4 overflow-hidden">Property Name</th>
                    <th class="p-1 w-1/4 overflow-hidden">Purchase Price</th>
                    <th class="p-1 w-1/4 overflow-hidden">Upgrade/Construction Cost</th>
                    <th class="p-1 w-1/4 overflow-hidden">Total Cost</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-400 bg-grey-light flex flex-col items-center justify-between overflow-y-scroll w-full" style="max-height: 11vh;">
                <?php
                    foreach ($unsold_properties as $property) {
                        $info = $_SESSION['game']->getPropertyInfo($property);
                        $purchase_price = money($info[0]);
                        $costs = money($info[1]);
                        $total = money($info[0] + $info[1]);
                        echo "<tr class=\"divide-x divide-gray-400 flex w-full mb-2\">";
                        echo "<td class=\"text-xs p-1 w-1/4 overflow-hidden\">{$property}</td>";
                        echo "<td class=\"text-xs p-1 w-1/4 overflow-hidden\">{$purchase_price}</td>";
                        echo "<td class=\"text-xs p-1 w-1/4 overflow-hidden\">{$costs}</td>";
                        echo "<td class=\"text-xs p-1 w-1/4 overflow-hidden\">{$total}</td>";
                        echo "</tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>
<?php endif ?>
