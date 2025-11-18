<?php
require __DIR__ . '/../Config/database.php';
session_start();

// Verificar login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: /pwa/login");
    exit();
}

$id_lacador = $_POST['id_lacador'] ?? null;
$id_evento  = $_POST['id_evento'] ?? null;
$modalidade = $_POST['modalidade'] ?? null;

// Validação simples
if (!$id_lacador || !$id_evento || !$modalidade) {
    echo "Dados inválidos!";
    exit();
}

// 1️⃣ Verificar se já existe inscrição deste laçador no mesmo evento
$stmt = $pdo->prepare("SELECT id FROM inscricoes WHERE id_lacador = ? AND id_evento = ?");
$stmt->execute([$id_lacador, $id_evento]);
$jaExiste = $stmt->fetch();

if ($jaExiste) {
    echo "
        <script>
            alert('Você já está inscrito neste evento!');
            window.location.href = '/pwa/meu_evento';
        </script>
    ";
    exit();
}

// 2️⃣ Inserir nova inscrição
$stmt = $pdo->prepare("
    INSERT INTO inscricoes (id_lacador, id_evento, tipo, status, created_at)
    VALUES (?, ?, ?, 'Pendente', NOW())
");

$salvo = $stmt->execute([$id_lacador, $id_evento, $modalidade]);

// 3️⃣ Confirmar operação
if ($salvo) {
    echo "
        <script>
            alert('Inscrição realizada com sucesso! Aguarde aprovação.');
            window.location.href = '/pwa/meu_evento';
        </script>
    ";
} else {
    echo "
        <script>
            alert('Erro ao salvar sua inscrição, tente novamente!');
            window.location.href = '/pwa/painel-cliente';
        </script>
    ";
}
