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
<!--    Include the Nav and Header in the layout which in turn is included in each page-->
    <?php include __DIR__ . '/partials/nav.php'; ?>

    <main class="max-w-4xl mx-auto p-6">
        <?= $content ?>
    </main>
<!--Include the Footer in the layout which in turn is included in each page-->
<?php include __DIR__ . '/partials/footer.php'; ?>
    </body>
    </html>
    <?php
}
