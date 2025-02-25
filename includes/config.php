<?php
// Obter conexão PDO
function getDBConnection()
{
    try {
        $dbPath = "posts.sqlite";

        $pdo = new PDO("sqlite: $dbPath");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        echo "Erro ao conectar ao banco de dados:" . $e->getMessage();
    }
}
