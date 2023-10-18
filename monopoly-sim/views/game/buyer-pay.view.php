<?php
    if (!isset($_SESSION['game'])) {
        header('location: /');
        exit();
    }

    $conf = parse_ini_file(base_path('config/config.ini'));
    require(base_path('views/partials/head.php'));
    require(base_path('views/partials/nav.php'));
    require(base_path('views/partials/header.php'));

    $simulation = $_SESSION['game'];
?>

<main>
    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
        <?php require(base_path('views/partials/game/game-parameters-table.php')) ?>
        <div class='py-3'>
            <?php require(base_path('views/partials/game/sold-properties-table.php')) ?>
        </div>
        <form method="POST" action="/game">
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-6">
                            <label for="property-id" class="mb-2 text-sm font-medium text-gray-900">Select Property</label>
                            <select required name="property-id" id="property-id" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                <option value="" disabled selected hidden>Choose a property</option>
                                <?php
                                    foreach ($simulation->getSoldProperties() as $property) {
                                        echo "<option value=\"{$property}\">{$property}</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="sm:col-span-3">
                            <label for="additional-capital" class="block text-sm font-medium leading-6 text-gray-900">Additional Payment from Buyer</label>
                            <div class="mt-2">
                                <input required type="number" step="0.01" name="additional-capital" id="additional-capital" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
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
                <input hidden name="action" value="buyer-pay">
                <a type="submit" href="/game" class="text-sm font-semibold leading-6 text-gray-900">Cancel</a>
                <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Submit</button>
            </div>
        </form>
    </div>
</main>

<?php require(base_path('views/partials/footer.php')) ?>
