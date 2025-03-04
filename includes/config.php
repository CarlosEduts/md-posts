<?php
// Obter conexão PDO com MySQL
function getDBConnection()
{
    try {
        // Configurações para conexão com o MySQL
        $host     = 'localhost';
        $dbname   = 'posts'; // Substitua pelo nome do seu banco de dados
        $username = 'root';          // Usuário do MySQL (por exemplo, root)
        $password = '';              // Senha do MySQL

        // Conectando ao MySQL via PDO
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        echo "Erro ao conectar ao banco de dados: " . $e->getMessage();
    }
}
?>
