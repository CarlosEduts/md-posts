<?php
// Configurações para conexão com o MySQL
$host     = 'localhost';
$dbname   = 'posts';  // Substitua pelo nome do seu banco de dados
$username = 'root';        // Seu usuário do MySQL (ex.: root)
$password = '';          // Sua senha do MySQL

try {
    // Conectando ao MySQL via PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Criando a tabela 'posts' se ela não existir
    $sql = "CREATE TABLE IF NOT EXISTS posts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        image_url VARCHAR(255) NOT NULL,
        content TEXT NOT NULL,
        creation_date DATETIME DEFAULT CURRENT_TIMESTAMP
    )";

    $pdo->exec($sql);

    echo $twig->render('alert.twig.html', ['type' => 'success', 'content' => 'Tabela criada com sucesso!']);
} catch (PDOException $e) {
    echo "Erro ao criar tabela: " . $e->getMessage();
    echo $twig->render('alert.twig.html', ['type' => 'error', 'content' => 'Erro ao criar a tabela!']);
}
?>

