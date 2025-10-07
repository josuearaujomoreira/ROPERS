<?php
// index.php (roteador simples)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$request = trim($_SERVER['REQUEST_URI'], '/');

// Remove query string ?...
if (strpos($request, '?') !== false) {
    $request = strstr($request, '?', true);
}

switch ($request) {
    case '':
    case 'login':
        require __DIR__ . '/app/Controllers/LoginController.php';
        break;
    case 'pwa/login':
        require __DIR__ . '/app/Controllers/LoginController.php';
        break;

    case 'cadastro':
        require __DIR__ . '/app/Controllers/CadastroController.php';
        break;
    case 'pwa/cadastro':
        require __DIR__ . '/app/Controllers/CadastroController.php';
        break;
    case 'pwa/painel-cliente':
        require __DIR__ . '/app/Controllers/PainelController.php';
        break;
     
    default:
        http_response_code(404);
        echo "2321Página não encontrada!".$request;
        break;
}