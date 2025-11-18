<?php
// app/Controllers/InscricaoController.php

$id_evento = $_GET['id'] ?? null;

if (!$id_evento) {
    echo "Evento não encontrado!";
    exit;
}

require __DIR__ . '/../Views/inscricao.php';
