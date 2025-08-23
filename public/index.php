<?php
// public/index.php
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Blog — Step 1</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Tailwind Play CDN (fine for dev) -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-50 text-gray-900">
<main class="max-w-2xl mx-auto p-6">
    <h1 class="text-3xl font-bold">It’s alive 🎉</h1>
    <p class="mt-3">This page is rendered by <span class="font-mono">PHP 8.3</span> and styled via <span class="font-mono">Tailwind CDN</span>.</p>

    <div class="mt-6 p-4 rounded border bg-white">
        <p class="text-sm text-gray-600">Server time (PHP):
            <span class="font-mono">
          <?= htmlspecialchars(date('Y-m-d H:i:s')) ?>
        </span>
        </p>
    </div>
</main>
</body>
</html>
