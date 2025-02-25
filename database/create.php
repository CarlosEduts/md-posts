<?php
// Arquivo de criação da base de dados

$dbPath = "posts.sqlite";
try {
    $pdo = new PDO("sqlite: $dbPath");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Criado tabela de posts
    $pdo->exec("CREATE TABLE IF NOT EXISTS posts (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        image_url TEXT NOT NULL,
        content NOT NULL,
        creation_date DATE TIME DEFAULT CURRENT_TIMESTAMP
    )");

    echo $twig->render('alert.twig.html', ['type' => 'success', 'content' => 'Banco de dados criado com sucesso!']);
} catch (PDOException $e) {
    echo "Erro ao criar banco de dados:" . $e->getMessage();
    echo $twig->render('alert.twig.html', ['type' => 'error', 'content' => 'Banco de dados criado com sucesso!']);
}
