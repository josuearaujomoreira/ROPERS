<?php
// index.php (roteador simples)

$request = trim($_SERVER['REQUEST_URI'], '/');

// Remove query string ?...
if (strpos($request, '?') !== false) {
    $request = strstr($request, '?', true);
}

switch ($request) {
    case '':
    case 'login':
        require __DIR__ . '/../app/Controllers/LoginController.php';
        break;

    case 'cadastro':
        require __DIR__ . '/../app/Controllers/CadastroController.php';
        break;

    default:
        http_response_code(404);
        echo "Página não encontrada!";
        break;
}
    