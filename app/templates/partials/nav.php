<?php
// app/templates/partials/nav.php
$u = current_user();
?>
<nav class="p-4 border-b bg-white">
    <div class="max-w-4xl mx-auto flex items-center justify-between">
        <a href="/" class="font-bold">My Blog</a>
        <div class="space-x-4 text-sm">
            <a href="/" class="text-gray-600 hover:underline">Home</a>
            <a href="/politics" class="text-gray-600 hover:underline">Politics</a>
            <a href="/economics-finance" class="text-gray-600 hover:underline">Economics &amp; Finance</a>
            <a href="/social-affairs" class="text-gray-600 hover:underline">Social Affairs</a>
            <a href="/health" class="text-gray-600 hover:underline">Health</a>

            <?php if ($u): ?>
                <a href="/account" class="text-gray-900 font-medium hover:underline">Account</a>
                <form method="post" action="/logout" class="inline">
                    <?php csrf_field(); ?>
                    <button class="text-gray-600 hover:underline">Logout</button>
                </form>
            <?php else: ?>
                <a href="/login" class="text-gray-600 hover:underline">Sign in</a>
                <a href="/register" class="text-gray-600 hover:underline">Register</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
