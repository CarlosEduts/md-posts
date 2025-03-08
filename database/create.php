<?php
// Configurações para conexão com o MySQL
$host = getenv('DB_HOST') ?: 'localhost';
$dbname   = getenv('DB_NAME') ?: 'posts';
$username = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASS') ?: '';

try {
    // Conectando ao MySQL via PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Criando tabela de usuarios se não existir
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username TEXT NOT NULL UNIQUE,
                password TEXT NOT NULL
            )"
    );

    // Criando a tabela 'posts' se ela não existir
    $pdo->exec("CREATE TABLE IF NOT EXISTS posts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        image_url VARCHAR(255) NOT NULL,
        content TEXT NOT NULL,
        creation_date DATETIME DEFAULT CURRENT_TIMESTAMP,
        user_id INT NOT NULL,
        FOREIGN KEY (user_id) REFERENCES users(id)
    )");

    echo $twig->render('alert.twig.html', ['type' => 'success', 'content' => 'Tabela criada com sucesso!']);
} catch (PDOException $e) {
    echo "Erro ao criar tabela: " . $e->getMessage();
    echo $twig->render('alert.twig.html', ['type' => 'error', 'content' => 'Erro ao criar a tabela!']);
}
