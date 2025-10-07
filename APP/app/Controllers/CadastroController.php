 
<?php
// app/Controllers/CadastroController.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $apelido = $_POST['apelido'] ?? '';
    $cpf = $_POST['cpf'] ?? '';
    $whatsapp = $_POST['whatsapp'] ?? '';
    $cidade = $_POST['cidade'] ?? '';
    $uf = $_POST['uf'] ?? '';
    $handicap_cabeca = $_POST['handicap_cabeca'] ?? '';
    $handicap_pe = $_POST['handicap_pe'] ?? '';

    // Validação simples
    if (
        empty($nome) || empty($apelido) || empty($cpf) ||
        empty($whatsapp) || empty($cidade) || empty($uf)
    ) {
        $erro = "Por favor, preencha todos os campos obrigatórios.";
    } else {
        // 🔹 Aqui futuramente você fará a conexão com o banco
        // Por enquanto apenas simula o cadastro:
        $sucesso = "Cadastro de <strong>{$nome}</strong> realizado com sucesso!";
    }
}

// Carrega a view do formulário
require __DIR__ . '/../Views/cadastro.php';