<nav class="bg-gray-800">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <img class="h-10 w-10" src=<?= $conf['logo'] ?> alt="">
                </div>
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4">
                        <!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->
                        <a href="/" class="<?= uriIs('/') ? 'bg-gray-900 text-white' : 'text-gray-300' ?> hover:bg-gray-700 px-3 py-2 rounded-md text-sm font-medium">Dashboard</a>
                        <?php if($_SESSION['user'] ?? false) : ?>
                            <a href="/game" class="<?= uriIs('/game') ? 'bg-gray-900 text-white' : 'text-gray-300' ?> hover:bg-gray-700 px-3 py-2 rounded-md text-sm font-medium">Game</a>
                        <?php endif; ?>
                        <a href="/about" class="<?= uriIs('/about') ? 'bg-gray-900 text-white' : 'text-gray-300' ?> hover:bg-gray-700 px-3 py-2 rounded-md text-sm font-medium">About</a>
                    </div>
                </div>
            </div>
            <div class="hidden md:block">
                <div class="ml-4 flex items-center md:ml-6">
                    <!-- Profile dropdown -->
                    <?php if($_SESSION['user'] ?? false) : ?>
                        <div class="ml-4 mt-4 flex items-center">
                            <form method="POST" action="/sessions">
                                <input type="hidden" name="_method" value="DELETE"/>
                                <button onclick="return confirm('Logout?');" class="rounded-full bg-gray-800 p-1 text-gray-300 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800"><?= strip_email($_SESSION['user']['email']) ?></button>
                            </form>
                        </div>
                        <div class="relative ml-3">
                            <div class="flex max-w-xs items-center rounded-full bg-gray-800 text-sm">
                                <svg class="h-8 w-8 rounded-full border" width="45" height="45" data-jdenticon-value=<?= $_SESSION['user']['email'] ?>></svg>
                            </div>
                        </div>
                    <?php else : ?>
                        <div class="ml-3">
                            <a href="/register" class="relative rounded-full bg-gray-800 p-1 text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">Register</a>
                            <a href="/login" class="relative rounded-full bg-gray-800 p-1 text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">Login</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</nav>
