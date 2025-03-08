<?php
// Página de visualização do post selecionado
require_once __DIR__ . '/../includes/config.php';

session_start();

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

$parsedown = new Parsedown();
$md_content = $parsedown->text($post['content']);

$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

$html_content = $purifier->purify($md_content);

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
    <?= $twig->render('side_bar.twig.html', ['user' => !empty($_SESSION['user_name']) ? $_SESSION['user_name'] :  "Conta"]) ?>

    <div class="p-4 sm:ml-64">
        <div class="w-full max-w-2xl p-3 flex flex-col gap-4 m-auto">
            <h1 class="mb-4 text-4xl font-extrabold leading-none tracking-tight text-gray-900 md:text-5xl lg:text-6xl dark:text-white"><?= $post['title'] ?></h1>
            <div>
                <p class=" w-fit px-2 rounded-md text-gray-900 dark:text-white bg-white/10"><?= $post['creation_date'] ?></p>
            </div>
            <div>
                <img src="<?= $post['image_url'] != "" ? $post['image_url'] : 'https://images.unsplash.com/photo-1588421357574-87938a86fa28?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' ?>" alt="" class="w-full h-80 rounded-md">
            </div>
            <div class="text-gray-900 dark:text-white">
                <style>
                    /* Geral */
                    a {
                        color: #007bff;
                        text-decoration: none;
                    }

                    /* Títulos */
                    h1,
                    h2,
                    h3,
                    h4,
                    h5,
                    h6 {
                        margin-top: 20px;
                        margin-bottom: 10px;
                        font-weight: bold;
                    }

                    h1 {
                        font-size: 2em;
                    }

                    h2 {
                        font-size: 1.75em;
                    }

                    h3 {
                        font-size: 1.5em;
                    }

                    h4 {
                        font-size: 1.25em;
                    }

                    h5 {
                        font-size: 1em;
                    }

                    h6 {
                        font-size: 0.85em;
                    }

                    /* Parágrafos */
                    p {
                        margin-bottom: 15px;
                    }

                    /* Listas */
                    ul,
                    ol {
                        margin-bottom: 20px;
                    }

                    ol {
                        list-style-type: decimal;

                    }

                    li {
                        margin-bottom: 5px;
                    }

                    /* Negrito e Itálico */
                    strong {
                        font-weight: bold;
                    }

                    em {
                        font-style: italic;
                    }

                    /* Bloco de citação */
                    blockquote {
                        border-left: 5px solid #ddd;
                        padding-left: 10px;
                        margin: 20px 0;
                        font-style: italic;
                        color: #555;
                    }

                    /* Código */
                    code {
                        font-family: monospace;
                        background-color: #f0f0f0;
                        padding: 2px 4px;
                        border-radius: 4px;
                    }

                    pre code {
                        display: block;
                        padding: 10px;
                        background-color: #f0f0f0;
                        border-radius: 4px;
                        overflow-x: auto;
                    }

                    /* Tabelas */
                    table {
                        width: 100%;
                        border-collapse: collapse;
                        margin: 20px 0;
                    }

                    table,
                    th,
                    td {
                        border: 1px solid #ddd;
                    }

                    th {
                        background-color: #f4f4f4;
                        padding: 10px;
                    }

                    td {
                        padding: 10px;
                        text-align: left;
                    }

                    /* Imagens */
                    img {
                        max-width: 100%;
                        height: auto;
                        border-radius: 4px;
                    }
                </style>
                <?= $html_content ?>
            </div>
        </div>
    </div>
</body>

</html>