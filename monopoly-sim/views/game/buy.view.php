<?php
    if (!isset($_SESSION['game'])) {
        header('location: /');
        exit();
    }

    $conf = parse_ini_file(base_path('config/config.ini'));
    require(base_path('views/partials/head.php'));
    require(base_path('views/partials/nav.php'));
    require(base_path('views/partials/header.php'));
?>

<main>
    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
        <?php require(base_path('views/partials/game/game-parameters-table.php')) ?>
        <form method="POST" action="/game">
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-2">
                            <label for="purchase-price" class="block text-sm font-medium leading-6 text-gray-900">Purchase Price</label>
                            <div class="mt-2">
                                <input required type="number" step="0.01" name="purchase-price" id="purchase-price" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-1 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <label for="upgrade-cost" class="block text-sm font-medium leading-6 text-gray-900">Renovation/Construction Cost</label>
                            <div class="mt-2">
                                <input required type="number" step="0.01" name="upgrade-cost" id="upgrade-cost" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>
                        <p>
                            <div class="sm:col-span-4">
                                <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Property Name</label>
                                <div class="mt-2">
                                    <input required id="name" name="name" type="text" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                </div>
                            </div>
                        </p>
                    </div>
                    <ul>
                        <?php if (isset($errors['message'])) : ?>
                            <li class="text-red-500 text-xs mt-2"><?= $errors['message'] ?></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
            <div class="mt-6 flex items-center justify-begin gap-x-6">
                <input hidden name="action" value="buy">
                <a type="submit" href="/game" class="text-sm font-semibold leading-6 text-gray-900">Cancel</a>
                <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Submit</button>
            </div>
        </form>
    </div>
</main>

<?php require(base_path('views/partials/footer.php')) ?>
