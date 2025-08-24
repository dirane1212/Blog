<?php
// app/templates/layout.php
function render_layout(string $title, string $content): void {
    ?>
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <title><?= htmlspecialchars($title) ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="min-h-screen bg-gray-50 text-gray-900">
    <nav class="p-4 border-b bg-white">
        <div class="max-w-4xl mx-auto flex items-center justify-between">
            <a href="/" class="font-bold">My Blog</a>
            <div class="space-x-4 text-sm">
                <a href="/" class="text-gray-600 hover:underline">Home</a>
                <a href="/health" class="text-gray-600 hover:underline">Health</a>
            </div>
        </div>
    </nav>
    <main class="max-w-4xl mx-auto p-6">
        <?= $content ?>
    </main>
    <footer class="text-center text-sm text-gray-500 py-6 border-t mt-12">
        © <?= date('Y') ?> My Blog
    </footer>
    </body>
    </html>
    <?php
}
