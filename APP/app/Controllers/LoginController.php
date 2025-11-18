<?php
// app/Controllers/LoginController.php
require __DIR__ . '/../../app/Config/database.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cpf = $_POST['cpf'] ?? '';
    $senha = $_POST['senha'] ?? '';

    if (empty($cpf) || empty($senha)) {
        $erro = "Por favor, preencha CPF e senha.";
    } else {
        // Busca usuário no banco
        $stmt = $pdo->prepare("SELECT id, nome, senha FROM lacadores WHERE cpf = :cpf LIMIT 1");
        $stmt->execute([':cpf' => $cpf]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && $usuario['senha'] === $senha) {
            // Cria sessão
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['nome'] = $usuario['nome'];

            // Redireciona para o painel
            header('Location: /pwa/painel-cliente');
            exit;
        } else {
            $erro = "CPF ou senha inválidos.";
        }
    }
}

// Carrega a view de login
require __DIR__ . '/../Views/login.php';
