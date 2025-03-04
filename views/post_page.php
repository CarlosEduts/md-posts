<?php
// Página de visualização do post selecionado
require_once __DIR__ . '/../includes/config.php';

$request = $_SERVER['REQUEST_URI'];
$request = parse_url($request, PHP_URL_PATH);

// Capturar ID do post na URL
$postID = '';
if (preg_match('/^\/post\/(\d+)$/', $request, $matches)) {
    $postID = (int) $matches[1];
}

if (empty($postID)) {
    die('ID não encontrado');
}

$pdo = getDBConnection();
$stmt = $pdo->prepare('SELECT * FROM posts WHERE id = ?');
$stmt->execute([$postID]);
$post = $stmt->fetch();

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
            <h1 class="mb-4 text-4xl font-extrabold leading-none tracking-tight text-gray-900 md:text-5xl lg:text-6xl dark:text-white"><?= $post['title'] ?></h1>
            <div>
                <img src="<?= $post['image_url'] != "" ? $post['image_url'] : 'https://images.unsplash.com/photo-1588421357574-87938a86fa28?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' ?>" alt="" class="w-full h-80 rounded-md">
            </div>
            <p class="mb-3 text-gray-500 dark:text-gray-400"><?= $post['content'] ?></< /p>
        </div>
    </div>
</body>

</html>