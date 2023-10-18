<?php
    if (!isset($_SESSION['user']['saved_games'])) {
        header('location: /');
        exit();
    }

    $conf = parse_ini_file(base_path('config/config.ini'));
    require(base_path('views/partials/head.php'));
    require(base_path('views/partials/nav.php'));
    require(base_path('views/partials/header.php'));

    $games = array_reverse($_SESSION['user']['saved_games']);
?>

<main>
    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
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
                <tbody class="divide-y divide-gray-400 bg-grey-light flex flex-col items-center justify-between overflow-y-scroll w-full" style="max-height: 11vh;">
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
        <form method="POST" action="/game">
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-4">
                            <label for="id" class="mb-2 text-sm font-medium text-gray-900">Select game to load</label>
                            <select required name="id" id="id" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                <option value="" disabled selected hidden>Select game</option>
                                <?php
                                    foreach ($games as $game) {
                                        $name = $game['name'];
                                        $id = $game['id'];
                                        echo "<option value=\"{$id}\">{$name}</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <ul>
                        <?php if (isset($errors['message'])) : ?>
                            <li class="text-red-500 text-xs mt-2"><?= $errors['message'] ?></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
            <div class="mt-6 flex items-center justify-begin gap-x-6">
                <input hidden name="action" value="load">
                <a type="submit" href="/" class="text-sm font-semibold leading-6 text-gray-900">Cancel</a>
                <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Submit</button>
            </div>
        </form>
    </div>
</main>

<?php require(base_path('views/partials/footer.php')) ?>
