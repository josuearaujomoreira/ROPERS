<?php

require __DIR__ . '/../Config/database.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_cliente = $_SESSION['usuario_id'] ?? null;
    if (!$id_cliente) {
        echo json_encode(['success' => false, 'message' => 'Usuário não autenticado']);
        exit;
    }

    $nome = trim($_POST['nome'] ?? '');
    $whatsapp = trim($_POST['whatsapp'] ?? '');
    $cidade = trim($_POST['cidade'] ?? '');
    $uf = trim($_POST['uf'] ?? '');

    if (empty($nome) || empty($whatsapp) || empty($cidade) || empty($uf)) {
        echo json_encode(['success' => false, 'message' => 'Preencha todos os campos']);
        exit;
    }

    $stmt = $pdo->prepare("UPDATE lacadores 
                           SET nome = :nome, whatsapp = :whatsapp, cidade = :cidade, uf = :uf 
                           WHERE id = :id");
    $stmt->execute([
        ':nome' => $nome,
        ':whatsapp' => $whatsapp,
        ':cidade' => $cidade,
        ':uf' => $uf,
        ':id' => $id_cliente
    ]);

    $_SESSION['nome'] = $nome; // Atualiza o nome na sessão também

    echo json_encode(['success' => true, 'message' => 'Perfil atualizado com sucesso!']);
}
