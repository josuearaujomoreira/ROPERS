<?php
require 'inc/config.php';

header('Content-Type: application/json');

// Base URL for images (adjust according to your hosting)
$base_url = 'https://teste.hosthp.com.br/pwa/app/uploads/';

// Fetch all eventos
$stmt = $pdo->prepare("SELECT * FROM eventos ORDER BY id DESC");
$stmt->execute();
$eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Add full URL to images in eventos
foreach ($eventos as &$evento) {
    if (!empty($evento['imagem'])) {
        $evento['imagem_url'] = $base_url . $evento['imagem'];
    }
}

// Fetch all corredores (lacadores)
$stmt = $pdo->prepare("SELECT * FROM lacadores ORDER BY id DESC");
$stmt->execute();
$corredores = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all inscricoes
$stmt = $pdo->prepare("SELECT * FROM inscricoes ORDER BY id DESC");
$stmt->execute();
$inscricoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Prepare the data array
$data = [
    'eventos' => $eventos,
    'corredores' => $corredores,
    'inscricoes' => $inscricoes
];

// Return JSON
echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>
