<?php
require 'inc/config.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inscricao_id = $_POST['inscricao_id'] ?? 0;
    $status = $_POST['status'] ?? '';

    // Valida o status
    $status_validos = ['Pendente', 'Aprovado', 'Reprovado'];
    if (!in_array($status, $status_validos)) {
        echo json_encode(['success' => false, 'message' => 'Status inválido']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("UPDATE inscricoes SET status = ? WHERE id = ?");
        $stmt->execute([$status, $inscricao_id]);

        echo json_encode(['success' => true, 'message' => 'Status atualizado com sucesso']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Erro ao atualizar: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
}
?>