<?php
// tools/migrate.php
// Usage (from project root):
//   php tools/migrate.php

require __DIR__ . '/../app/config.php';
require __DIR__ . '/../app/db.php';

$dir = __DIR__ . '/../db/migrations';
if (!is_dir($dir)) {
    fwrite(STDERR, "Migrations folder not found: $dir\n");
    exit(1);
}

$files = glob($dir . '/*.sql');
sort($files); // run in lexical order 001_, 002_, ...

if (!$files) {
    echo "No migrations to run.\n";
    exit(0);
}

$pdo = db();
$pdo->beginTransaction();
try {
    // Ensure migrations bookkeeping
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS app_migrations (
            id SERIAL PRIMARY KEY,
            filename TEXT UNIQUE NOT NULL,
            run_at TIMESTAMPTZ NOT NULL DEFAULT now()
        );
    ");

    // Determine which ones already ran
    $ran = [];
    foreach ($pdo->query("SELECT filename FROM app_migrations") as $row) {
        $ran[$row['filename']] = true;
    }

    $applied = 0;
    foreach ($files as $file) {
        $name = basename($file);
        if (isset($ran[$name])) {
            echo "SKIP $name (already applied)\n";
            continue;
        }
        $sql = file_get_contents($file);
        echo "APPLY $name ...\n";
        $pdo->exec($sql);
        $stmt = $pdo->prepare("INSERT INTO app_migrations (filename) VALUES (:f)");
        $stmt->execute([':f' => $name]);
        $applied++;
    }

    $pdo->commit();
    echo $applied ? "Done. Applied $applied migration(s).\n" : "Nothing new to apply.\n";
} catch (Throwable $e) {
    $pdo->rollBack();
    fwrite(STDERR, "Migration failed: " . $e->getMessage() . "\n");
    exit(1);
}
