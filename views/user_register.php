<?php

// Página de registrar usuários
require_once __DIR__ . '/../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $password_ckeck = $_POST['password-check'];

    if ($password != $password_ckeck) {
        echo $twig->render('alert.twig.html', ['type' => 'error', 'content' => 'As senhas devem ser iguais, verifique!']);
    } else {
        try {
            $pdo = getDBConnection();
            $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT)]);

            echo $twig->render('alert.twig.html', ['type' => 'success', 'content' => 'Usuário criado com sucesso!']);

            header("Location: /login");
            exit;
        } catch (PDOException $e) {
            echo $twig->render('alert.twig.html', ['type' => 'error', 'content' => 'Erro ao criar uruário: ' . $e->getMessage()]);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<?= $twig->render('head.twig.html', ['title' => 'Criar Conta']) ?>

<body class="light flex-col bg-gray-50 dark:bg-gray-700 w-full h-dvh flex items-center justify-center">
    <h1 class="text-gray-900 dark:text-white text-2xl mb-3">Criar conta</h1>
    <div class="max-w-xs w-full p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
        <form method="POST">
            <div class="mb-5">
                <label for="username" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nome de usuário:</label>
                <input type="username" name="username" id="username" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="my_user_name534" required />
            </div>
            <div class="mb-5">
                <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Senha:</label>
                <input type="password" name="password" id="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
            </div>
            <div class="mb-5">
                <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Digite novamente a senha:</label>
                <input type="password" name="password-check" id="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
            </div>
            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Cadastrar</button>
            <p class="text-gray-900 dark:text-white text-sm opacity-80 mt-2">Já tem uma conta? <a href="/login" class="text-blue-500">Entre.</a></p>
        </form>
    </div>
</body>

</html>