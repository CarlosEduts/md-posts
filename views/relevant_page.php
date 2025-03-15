<?php

// Página relevantes
require_once __DIR__ . '/../includes/config.php';
session_start();

$pdo = getDBConnection();
$stmt = $pdo->query(
    'SELECT posts.*, COUNT(likes.id) AS like_count 
    FROM posts 
    LEFT JOIN likes ON posts.id = likes.post_id 
    GROUP BY posts.id 
    ORDER BY like_count DESC, posts.creation_date DESC'
);

?>

<!DOCTYPE html>
<html lang="pt">
<?= $twig->render('head.twig.html', ['title' => 'Posts Relevantes']) ?>

<body class="light bg-gray-50 dark:bg-gray-700">
    <?= $twig->render('side_bar.twig.html', ['user' => !empty($_SESSION['user_name']) ? $_SESSION['user_name'] :  "Conta"]) ?>

    <div class="p-4 sm:ml-64">
        <div class="w-full max-w-2xl p-3 flex flex-col gap-4 m-auto">
            <?php
            while ($post = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
                echo $twig->render(
                    'post_card.twig.html',
                    [
                        'title' => $post['title'],
                        'image_url' => $post['image_url'] != "" ? $post['image_url'] : 'https://images.unsplash.com/photo-1588421357574-87938a86fa28?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                        'creation_date' => $post['creation_date'],
                        'id' => $post['id']
                    ]
                );
            }
            ?>
        </div>
    </div>
</body>

</html>