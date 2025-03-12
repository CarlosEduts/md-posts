<?php
// Página de visualização do post selecionado
require_once __DIR__ . '/../includes/config.php';

session_start();

$request = $_SERVER['REQUEST_URI'];
$request = parse_url($request, PHP_URL_PATH);

// Capturar ID do post na URL
$post_id = '';
if (preg_match('/^\/post\/(\d+)$/', $request, $matches)) {
    $post_id = (int) $matches[1];
}

if (empty($post_id)) {
    die('ID não encontrado');
}

// Peceber o post
$pdo = getDBConnection();
$stmt = $pdo->prepare('SELECT * FROM posts WHERE id = ?');
$stmt->execute([$post_id]);
$post = $stmt->fetch();

// Receber contagem de likes do post
$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM likes WHERE post_id = :post_id");
$stmt->execute(['post_id' => $post_id]);
$likes = $stmt->fetch()['total'] ?? 0;

$parsedown = new Parsedown();
$md_content = $parsedown->text($post['content']);

$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

$html_content = $purifier->purify($md_content);

// Dar like no post
$liked = false;
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Verificar a curtida do usuário em relação ao post
    $stmt = $pdo->prepare("SELECT * FROM likes WHERE user_id = :user_id AND post_id = :post_id");
    $stmt->execute(['user_id' => $user_id, 'post_id' => $post_id]);
    $liked = $stmt->fetch();

    // Verificar se o usuário clicou no batão de curtir
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['like'])) {

        // Verifica se o usuário já curtiu o post
        if ($liked) {
            // Se já curtiu, remove o like (descurtir)
            $stmt = $pdo->prepare("DELETE FROM likes WHERE user_id = :user_id AND post_id = :post_id");
            $stmt->execute(['user_id' => $user_id, 'post_id' => $post_id]);
            $liked = false;
        } else {
            // Se não curtiu, adiciona um like
            $stmt = $pdo->prepare("INSERT INTO likes (user_id, post_id) VALUES (:user_id, :post_id)");
            $stmt->execute(['user_id' => $user_id, 'post_id' => $post_id]);
            $liked = true;
        }
    }
}

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

        <div class="w-full max-w-2xl p-3 flex flex-col gap-4 m-auto relative">
            <h1 class="mb-4 text-4xl font-extrabold leading-none tracking-tight text-gray-900 md:text-5xl lg:text-6xl dark:text-white"><?= $post['title'] ?></h1>
            <div class="flex items-center gap-2">

                <p class="w-fit px-2 rounded-md mb-0 text-gray-900 dark:text-white bg-white/10"><?= $post['creation_date'] ?></p>

                <!-- Formuário para curtida do post -->
                <form method="POST">
                    <input type="hidden" name="like" value="like">
                    <button type="submit" class="text-gray-900 dark:text-white flex items-center justify-center gap-1">
                        <?php
                        if ($liked) echo '<i class="ti ti-heart-filled text-xl text-red-500"></i>' . $likes;
                        else echo '<i class="ti ti-heart text-xl "></i>' . $likes;
                        ?>
                    </button>
                </form>

                <button class="text-gray-900 dark:text-white flex text-xl items-center justify-center gap-1" id="shareButton"><i class="ti ti-share"></i></button>

                <script>
                    const shareData = {
                        title: window.location.href,
                        text: window.location.href,
                        url: window.location.href, // ou qualquer URL que desejar
                    };

                    function share() {
                        if (navigator.share) {
                            navigator.share(shareData)
                                .then(() => console.log("Compartilhado com sucesso"))
                                .catch((error) => console.error("Erro ao compartilhar:", error));
                        } else {
                            alert("Compartilhamento não suportado neste navegador.");
                        }
                    }

                    document.getElementById("shareButton").addEventListener("click", share);
                </script>

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