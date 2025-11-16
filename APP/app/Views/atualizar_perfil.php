<?php
require __DIR__ . '/../Config/database.php';

$id = $_POST['id'];
$nome = $_POST['nome'];
$whatsapp = $_POST['whatsapp'];
$apelido = $_POST['apelido'];
$handicap_cabeca = $_POST['handicap_cabeca'];
$handicap_pe = $_POST['handicap_pe'];

$stmt = $pdo->prepare("
    UPDATE lacadores SET 
        nome = ?, 
        whatsapp = ?, 
        apelido = ?, 
        handicap_cabeca = ?, 
        handicap_pe = ?
    WHERE id = ?
");

if ($stmt->execute([$nome, $whatsapp, $apelido, $handicap_cabeca, $handicap_pe, $id])) {
    echo "Perfil atualizado com sucesso!";
} else {
    echo "Erro ao atualizar!";
}
?>
