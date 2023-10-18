<?php
    $conf = parse_ini_file(base_path('config/config.ini'));
    require(base_path('views/partials/head.php'));
    require(base_path('views/partials/nav.php'));
    require(base_path('views/partials/header.php'));
?>

<main>
    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
    <?php $games = array_reverse($_SESSION['user']['saved_games']) ?>
    <?php if ($games) : ?>
        <div class="container">
            <form method="POST" action="/game">
                <input type="hidden" name="_method" value="DELETE"/>
                <h1 class='text-2xl font-bold text-center'>Saved Games</h1>
                <table class="text-left w-full border border-gray-800">
                    <thead class="bg-black flex text-white w-full">
                        <tr class="flex w-full mb-2">
                            <th class="p-1 w-1/12">Delete</th>
                            <th class="p-1 w-3/12">Name</th>
                            <th class="p-1 w-2/12">Number of Properties</th>
                            <th class="p-1 w-2/12">Free Capital</th>
                            <th class="p-1 w-2/12">Debt</th>
                            <th class="p-1 w-2/12">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-400 bg-grey-light flex flex-col items-center justify-between overflow-y-scroll w-full" style="max-height: 25vh;">
                        <?php
                            foreach ($games as $game) {
                                $name = $game['name'];
                                $state = json_decode($game['state'], true);
                                $id = $game['id'];
                                $num_properties = count($state['unsold_properties']) + count($state['properties']);
                                $free_capital = $state['free_capital'];
                                $debt = $state['debt'];
                                $date_f = date('D dMY H:i', $game['timestamp']);
                                echo "<tr class=\"divide-x divide-gray-400 flex w-full mb-2\">";
                                echo "<td class=\"text p-1 w-1/12 overflow-hidden\"><input type=\"checkbox\" name=\"marked[]\" value=\"{$id}\" id=\"checkbox\"></td>";
                                echo "<td class=\"text p-1 w-3/12 overflow-hidden\">{$name}</td>";
                                echo "<td class=\"text p-1 w-2/12 overflow-hidden\">{$num_properties}</td>";
                                echo "<td class=\"text p-1 w-2/12 overflow-hidden\">\${$free_capital}</td>";
                                echo "<td class=\"text p-1 w-2/12 overflow-hidden\">\${$debt}</td>";
                                echo "<td class=\"text p-1 w-2/12 overflow-hidden\">{$date_f}</td>";
                                echo "</tr>";
                            }
                        ?>
                    </tbody>
                </table>
                <a href="/" class="text font-semibold leading-6 text-gray-900 py-2 px-2 mr-2 mt-3">Cancel</a>
                <button onclick="return confirm('Delete Saves?');" type="submit" class="text bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-2 mt-3 rounded">Delete Selected</button>
            </form>
        </div>
    <?php endif ?>
    </div>
</main>

<?php require(base_path('views/partials/footer.php')) ?>
