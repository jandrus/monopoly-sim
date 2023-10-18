<h1 class="text-center text-4xl font-bold tracking-tight text-gray-900 sm:text-6xl">Welcome <?= strip_email($_SESSION['user']['email']) ?>!</h1>
<?php $games = array_reverse($_SESSION['user']['saved_games']) ?>
<?php if ($games) : ?>
    <div class="container">
        <h1 class='text-2xl font-bold text-center'>Saved Games</h1>
        <table class="text-left w-full border border-gray-800">
            <thead class="bg-black flex text-white w-full">
                <tr class="flex w-full mb-2">
                    <th class="p-1 w-1/5">Name</th>
                    <th class="p-1 w-1/5">Number of Properties</th>
                    <th class="p-1 w-1/5">Free Capital</th>
                    <th class="p-1 w-1/5">Debt</th>
                    <th class="p-1 w-1/5">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-400 bg-grey-light flex flex-col items-center justify-between overflow-y-scroll w-full" style="max-height: 25vh;">
                <?php
                    foreach ($games as $game) {
                        $name = $game['name'];
                        $state = json_decode($game['state'], true);
                        $num_properties = count($state['unsold_properties']) + count($state['properties']);
                        $free_capital = $state['free_capital'];
                        $debt = $state['debt'];
                        $date_f = date('D dMY H:i', $game['timestamp']);
                        echo "<tr class=\"divide-x divide-gray-400 flex w-full mb-2\">";
                        echo "<td class=\"text p-1 w-1/5 overflow-hidden\">{$name}</td>";
                        echo "<td class=\"text p-1 w-1/5 overflow-hidden\">{$num_properties}</td>";
                        echo "<td class=\"text p-1 w-1/5 overflow-hidden\">\${$free_capital}</td>";
                        echo "<td class=\"text p-1 w-1/5 overflow-hidden\">\${$debt}</td>";
                        echo "<td class=\"text p-1 w-1/5 overflow-hidden\">{$date_f}</td>";
                        echo "</tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>
    <div class='clearfix'>
        <a href="/game/date" class="float-left rounded-md bg-indigo-600 px-3 py-2 mr-2 my-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">New Game</a>
        <a href="/game/load" class="float-left rounded-md bg-red-600 px-3 py-2 mr-2 my-3 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">Load Game</a>
        <?php if (isset($_SESSION['game'])) : ?>
            <a href="/game" class="float-left rounded-md bg-green-600 px-3 py-2 mr-2 my-3 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">Continue Game</a>
        <?php endif ?>
        <a href="/game/delete-save" class="float-right rounded-md bg-red-600 px-3 py-2 my-3 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">Delete Save</a>
    </div>
<?php else : ?>
    <p class="text-center mt-6 text-lg leading-8 text-gray-600">Use this tool as a game to forecast your Real Estate business plans.</p>
    <p class="text-center mt-4 text-lg leading-8 text-gray-600">Click the "New Game" button or select the "Game" tab to get started.</p>
    <div class="mt-6 flex justify-center gap-x-6">
        <a href="/game/date" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">New Game</a>
        <?php if (isset($_SESSION['game'])) : ?>
            <a href="/game" class="rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">Continue Game</a>
        <?php endif ?>
    </div>
<?php endif ?>
