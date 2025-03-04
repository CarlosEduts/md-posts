<?php
// Página inicial

require_once __DIR__ . '/../includes/config.php';

$pdo = getDBConnection();
$stmt = $pdo->query('SELECT * FROM posts');

?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Flowbite CSS e JS -->
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>

    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css" />

</head>

<body class="light bg-gray-50 dark:bg-gray-700">
    <?= $twig->render('side_bar.twig.html') ?>

    <div class="p-4 sm:ml-64">
        <div class="w-full max-w-2xl p-3 flex flex-col gap-4 m-auto">
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