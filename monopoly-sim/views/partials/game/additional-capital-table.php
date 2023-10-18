<?php
    if(!isset($_SESSION['game'])) {
        return;
    }

    use Game\Simulation;

    $events = array_reverse($_SESSION['game']->getFreeCapitalEvents());
?>
<?php if(sizeof($events) > 0) : ?>
    <div class="container">
        <form method="POST" action="/game">
            <h1 class='text-2xl font-bold text-center'>Additional Capital Events</h1>
            <table class="text-left w-full border border-gray-800">
                <thead class="bg-black flex text-white w-full">
                    <tr class="flex w-full mb-2">
                        <th class="p-1 w-1/5 overflow-hidden">Accounted For <button type="submit" class="text-xs bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-1 rounded">Save</button></th>
                        <th class="p-1 w-2/5 overflow-hidden">Name</th>
                        <th class="p-1 w-2/5 overflow-hidden">Capital</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-400 bg-grey-light flex flex-col items-center justify-between overflow-y-scroll w-full" style="max-height: 10vh;">
                    <?php
                        foreach ($events as $event) {
                            $capital = money($event[1]);
                            echo "<tr class=\"divide-x divide-gray-400 flex w-full mb-2\">";
                            if ($event[2]) {
                                echo "<td class=\"text-xs p-1 w-1/5 overflow-hidden\"><input type=\"checkbox\" checked name=\"marked[]\" value=\"{$event[0]}\" id=\"checkbox\"></td>";
                            } else {
                                echo "<td class=\"text-xs p-1 w-1/5 overflow-hidden\"><input type=\"checkbox\" name=\"marked[]\" value=\"{$event[0]}\" id=\"checkbox\"></td>";
                            }
                            echo "<td class=\"text-xs p-1 w-2/5 overflow-hidden\">{$event[0]}</td>";
                            echo "<td class=\"text-xs p-1 w-2/5 overflow-hidden\">{$capital}</td>";
                            echo "</tr>";
                        }
                    ?>
                </tbody>
            </table>
            <input hidden name="action" value="mark-capital">
        </form>
    </div>
<?php endif ?>
