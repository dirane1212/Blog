<?php
// app/posts.php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/../vendor/autoload.php';

function save_post(array $data, ?int $id = null): array {
    $pdo = db();

    // Your Markdown conversion stays as-is
    $parsedown = new Parsedown();
    if (method_exists($parsedown, 'setSafeMode')) {
        $parsedown->setSafeMode(true); // strips raw HTML, prevents XSS from Markdown
    }
    $html = $parsedown->text($data['body_md']);



    if ($id) {
        $stmt = $pdo->prepare("
        UPDATE posts SET
          title        = :title,
          slug         = :slug,
          section      = CAST(:section AS section_type),
          excerpt      = :excerpt,
          body_md      = :body_md,
          body_html    = :body_html,
          access_level = :access_level,
          status       = CAST(:status AS post_status),
          published_at = CASE
                           WHEN CAST(:status AS post_status) = 'published'::post_status
                                AND published_at IS NULL THEN now()
                           ELSE published_at
                         END,
          updated_at   = now()
        WHERE id = :id
        RETURNING *;
    ");
        $stmt->execute([
            ':title'        => $data['title'],
            ':slug'         => $data['slug'],
            ':section'      => $data['section'],      // politics | economics_finance | social_affairs
            ':excerpt'      => $data['excerpt'],
            ':body_md'      => $data['body_md'],
            ':body_html'    => $html,
            ':access_level' => $data['access_level'], // free | premium
            ':status'       => $data['status'],       // draft | published
            ':id'           => $id,
        ]);
    } else {
        $stmt = $pdo->prepare("
        INSERT INTO posts (
          author_id, section, title, slug, excerpt,
          body_md, body_html, access_level, status, published_at
        )
        VALUES (
          :author_id,
          CAST(:section AS section_type),
          :title,
          :slug,
          :excerpt,
          :body_md,
          :body_html,
          :access_level,
          CAST(:status AS post_status),
          CASE
            WHEN CAST(:status AS post_status) = 'published'::post_status THEN now()
            ELSE NULL
          END
        )
        RETURNING *;
    ");
        $stmt->execute([
            ':author_id'    => $data['author_id'],
            ':section'      => $data['section'],
            ':title'        => $data['title'],
            ':slug'         => $data['slug'],
            ':excerpt'      => $data['excerpt'],
            ':body_md'      => $data['body_md'],
            ':body_html'    => $html,
            ':access_level' => $data['access_level'],
            ':status'       => $data['status'],
        ]);
    }


    return $stmt->fetch();
}

function find_post_by_slug(string $slug): ?array {
    $pdo = db();
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE slug=:slug AND status='published' LIMIT 1");
    $stmt->execute([':slug' => $slug]);
    $p = $stmt->fetch();
    return $p ?: null;
}

function list_published_posts(int $limit = 10): array {
    $pdo = db();
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE status='published' ORDER BY published_at DESC LIMIT :lim");
    $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}
