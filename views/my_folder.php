<?php

// Minha Pasta - Posts que criei
require_once __DIR__ . '/../includes/config.php';
session_start();

$pdo = getDBConnection();
$stmt = $pdo->prepare('SELECT * FROM posts WHERE user_id = ?');
$stmt->execute([$_SESSION['user_id']]);

?>

<!DOCTYPE html>
<html lang="pt">
<?= $twig->render('head.twig.html', ['title' => 'Meus Posts']) ?>

<body class="light bg-gray-50 dark:bg-gray-700">
    <?= $twig->render('side_bar.twig.html', ['user' => !empty($_SESSION['user_name']) ? $_SESSION['user_name'] :  "Conta"]) ?>

    <div class="p-4 sm:ml-64">
        <div class="w-full max-w-2xl p-3 flex flex-col gap-4 m-auto">
            <div class="text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:text-gray-400 dark:border-gray-700">
                <ul class="flex flex-wrap -mb-px">
                    <li class="me-2">
                        <a href="#" class="inline-block p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500" aria-current="page">Meus posts</a>
                    </li>
                    <li class="me-2">
                        <a href="/my-folder-posts-liked" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300">Posts que gostei</a>
                    </li>
                </ul>
            </div>

            <?php
            while ($post = $stmt->fetch()) {
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