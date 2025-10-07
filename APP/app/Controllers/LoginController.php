<?php
// app/Controllers/LoginController.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    // Exemplo simples (depois conecta no banco)
    if ($email === 'teste@teste.com' && $senha === '123') {
        echo "Login realizado com sucesso!";
        exit;
    } else {
        $erro = "Usuário ou senha inválidos.";
    }
}

require __DIR__ . '/../Views/login.php';
