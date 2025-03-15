<?php

// Página para visualização do post selecionado
require_once __DIR__ . '/../includes/config.php';
session_start();

// Capturar ID do post na URL
$request = $_SERVER['REQUEST_URI'];
$request = parse_url($request, PHP_URL_PATH);

if (preg_match('/^\/post\/(\d+)$/', $request, $matches)) {
    $post_id = (int) $matches[1];
}

if (empty($post_id)) {
    die('ID não encontrado');
}

try {
    // Receber o post
    $pdo = getDBConnection();
    $stmt = $pdo->prepare('SELECT * FROM posts WHERE id = ?');
    $stmt->execute([$post_id]);
    $post = $stmt->fetch();

    // Receber o nome do autor do post
    $author_data =  $pdo->prepare('SELECT * FROM users WHERE id = ?');
    $author_data->execute([$post['user_id']]);
    $author_name = $author_data->fetch();

    // Receber contagem de likes do post
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM likes WHERE post_id = :post_id");
    $stmt->execute(['post_id' => $post_id]);
    $likes = $stmt->fetch()['total'] ?? 0;
} catch (PDOException $e) {
    echo $twig->render(
        'alert.twig.html',
        ['type' => 'error', 'content' => 'Erro ao acessar post: ' . $e->getMessage()]
    );
}

// Converter o Markdown para HTML
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
<?= $twig->render('head.twig.html', ['title' => $post['title']]) ?>

<body class="light bg-gray-50 dark:bg-gray-700">
    <?= $twig->render('side_bar.twig.html', ['user' => !empty($_SESSION['user_name']) ? $_SESSION['user_name'] :  "Conta"]) ?>

    <div class="p-4 sm:ml-64">
        <div class="w-full max-w-2xl p-3 flex flex-col gap-4 m-auto relative">
            <h1 class="mb-4 text-4xl font-extrabold leading-none tracking-tight text-gray-900 md:text-5xl lg:text-6xl dark:text-white"><?= $post['title'] ?></h1>
            <div class="flex items-center flex-wrap gap-2">
                <p class="w-fit px-2 rounded-md mb-0 text-gray-900 dark:text-white bg-white/10"><?= $author_name['username'] ?></p>
                <p class="w-fit px-2 rounded-md mb-0 text-gray-900 dark:text-white bg-white/10"><?= $post['creation_date'] ?></p>

                <!-- Formuário para curtida do post -->
                <form method="POST">
                    <input type="hidden" name="like" value="like">
                    <button type="submit" class="text-gray-900 dark:text-white flex items-center justify-center gap-1">
                        <?= $liked ? '<i class="ti ti-heart-filled text-xl text-red-500"></i>' . $likes : '<i class="ti ti-heart text-xl "></i>' . $likes ?>
                    </button>
                </form>

                <!-- Botão de compartilhar -->
                <button class="text-gray-900 dark:text-white flex text-xl items-center justify-center gap-1" id="shareButton">
                    <i class="ti ti-share"></i>
                </button>
            </div>
            <div>
                <img src="<?= $post['image_url'] != "" ? $post['image_url'] : 'https://images.unsplash.com/photo-1588421357574-87938a86fa28?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' ?>" alt="" class="w-full h-80 rounded-md">
            </div>
            <div class="text-gray-900 dark:text-white md-styles">
                <?= $html_content ?>
            </div>
        </div>
    </div>

    <!-- Script para a função de compartilhar -->
    <script>
        const shareData = {
            title: window.location.href,
            text: window.location.href,
            url: window.location.href,
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
</body>

</html>