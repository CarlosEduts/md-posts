<?php

// Configuração do twig tamplate
require_once 'vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('views/components');
$twig = new \Twig\Environment($loader, ['cache' => false]);

// incluindo arquivos de conexão e configuração
include './database/create.php';
include './includes/config.php';

// Configurando roteamento
$request = $_SERVER['REQUEST_URI'];
$request = parse_url($request, PHP_URL_PATH);

$route = match (true) {
    // Rotas dinâmicas
    preg_match('/^\/post\/(\d+)$/', $request) === 1 => 'views/post_page.php',

    // Rotas estáticas
    $request === '/' => 'views/home.php',
    $request === '/create-post' => 'views/create_post.php',
    $request === '/login' => 'views/user_login.php',
    $request === '/register' => 'views/user_register.php',
    $request === '/recent' => 'views/recent_page.php',
    $request === '/relevent' => '',
    $request === '/my-folder' => 'views/my_folder.php',
    $request === '/my-folder-posts-liked' => 'views/my_folder_posts_liked.php',

    // Rota padrão
    default => 'views/home.php'
};

require $route;
