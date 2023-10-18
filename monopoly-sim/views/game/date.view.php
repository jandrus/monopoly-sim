<?php
    $conf = parse_ini_file(base_path('config/config.ini'));
    require(base_path('views/partials/head.php'));
    require(base_path('views/partials/nav.php'));
    require(base_path('views/partials/header.php'));
?>

<main>
    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
        This is the starting date for the game. If your using this to represent actual business transactions, this will aid in inputing data in the correct month and year.
        <form method="POST" action="/game">
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <label for="month" class="mb-2 text-sm font-medium text-gray-900">Select month</label>
                            <select required name="month" id="month" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                <option value="" disabled selected hidden>Choose month</option>
                                <?php
                                    $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July ', 'August', 'September', 'October', 'November', 'December'];
                                    for ($i=1; $i<=count($months); $i++) {
                                        echo "<option value=\"{$i}\">{$months[$i-1]}</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="sm:col-span-3">
                            <label for="year" class="mb-2 text-sm font-medium text-gray-900">Select year</label>
                            <select required name="year" id="year" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                <option value="" disabled selected hidden>Choose year</option>
                                <?php
                                    $year = (int) date('Y');
                                    for ($i=$year; $i>=1971; $i--) {
                                        echo "<option value=\"{$i}\">{$i}</option>";
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
                <input hidden name="action" value="new">
                <a type="submit" href="/" class="text-sm font-semibold leading-6 text-gray-900">Cancel</a>
                <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Submit</button>
            </div>
        </form>
    </div>
</main>

<?php require(base_path('views/partials/footer.php')) ?>
