<?php
// Obter conexão PDO com MySQL
function getDBConnection()
{
    try {
        // Configurações para conexão com o MySQL
        $host = getenv('DB_HOST') ?: 'localhost';
        $dbname   = getenv('DB_NAME') ?: 'posts';
        $username = getenv('DB_USER') ?: 'root';
        $password = getenv('DB_PASS') ?: '';

        // Conectando ao MySQL via PDO
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        echo "Erro ao conectar ao banco de dados: " . $e->getMessage();
    }
}
