<?php

// Página de criação de posts
require_once __DIR__ . '/../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $title = $_POST['title'];
    $image_url = $_POST['image-url'];
    $content = $_POST['content'];

    try {
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("INSERT INTO posts (title, image_url, content) VALUES (?, ?, ?)");
        $stmt->execute([$title, $image_url, $content]);

        echo $twig->render('alert.twig.html', ['type' => 'success', 'content' => 'Post criado com sucesso!']);
    } catch (PDOException $e) {
        echo $twig->render('alert.twig.html', ['type' => 'error', 'content' => 'Erro ao criar post: ' . $e->getMessage()]);
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
    <?= $twig->render('side_bar.twig.html') ?>

    <div class="p-4 sm:ml-64">
        <div class="w-full max-w-2xl p-3 flex flex-col gap-4 m-auto">
            <form method="POST">
                <div class="mb-5">
                    <label for="title" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Título</label>
                    <input name="title" type="title" id="title" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
                </div>

                <div class="mb-5">
                    <label for="image-url" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">URL da imagem da capa (Opcional)</label>
                    <input name="image-url" type="image-url" id="image-url" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                </div>

                <div id="focusTextarea" class="w-full mb-4 border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600">
                    <div class="flex items-center justify-between px-3 py-2 border-b border-gray-200 dark:border-gray-600">
                        <div class="flex flex-wrap items-center divide-gray-200 sm:divide-x sm:rtl:divide-x-reverse dark:divide-gray-600">
                            <div class="flex items-center space-x-1 rtl:space-x-reverse sm:pe-4">
                                <button type="button" class="p-2 text-gray-500 rounded-sm cursor-pointer hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600">
                                    <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 12 20">
                                        <path stroke="currentColor" stroke-linejoin="round" stroke-width="2" d="M1 6v8a5 5 0 1 0 10 0V4.5a3.5 3.5 0 1 0-7 0V13a2 2 0 0 0 4 0V6" />
                                    </svg>
                                    <span class="sr-only">Attach file</span>
                                </button>
                            </div>
                        </div>

                        <button onclick="fullScreen()" type="button" data-tooltip-target="tooltip-fullscreen" class="p-2 text-gray-500 rounded-sm cursor-pointer sm:ms-auto hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600">
                            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 19 19">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 1h5m0 0v5m0-5-5 5M1.979 6V1H7m0 16.042H1.979V12M18 12v5.042h-5M13 12l5 5M2 1l5 5m0 6-5 5" />
                            </svg>
                            <span class="sr-only">Full screen</span>
                        </button>
                        <div id="tooltip-fullscreen" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-xs opacity-0 tooltip dark:bg-gray-700">
                            Show full screen
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>
                    </div>

                    <div class="px-4 py-2 bg-white rounded-b-lg dark:bg-gray-800">
                        <label for="editor" class="sr-only">Escrever post</label>
                        <textarea name="content" id="editor" rows="8" class="block w-full px-0 text-sm text-gray-800 bg-white border-0 dark:bg-gray-800 focus:ring-0 dark:text-white dark:placeholder-gray-400" placeholder="Escreva seu post aqui..." required></textarea>
                    </div>
                </div>
                <button type="submit" class=" text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full  px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">criar</button>
            </form>

        </div>
    </div>

    <script>
        const fullScreen = () => {
            const focus = document.querySelector("#focusTextarea")
            focus.classList.toggle('focus')
        }
    </script>

    <style>
        .focus {
            position: fixed;
            z-index: 50;
            top: 0;
            left: 0;
            width: calc(100% - 2rem);
            height: calc(100dvh - 2rem);
            margin: 1rem;
            backdrop-filter: blur(10px);
        }

        .focus textarea {
            height: calc(100% - 4rem);
        }
    </style>
</body>

</html>