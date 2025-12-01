<?php
require __DIR__ . '/../Config/database.php';
session_start();

// Verificar login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: /pwa/login");
    exit();
}

$id_lacador = $_SESSION['usuario_id'];
$nome = $_SESSION['nome'] ?? "Laçador";

// Buscar inscrições + dados dos eventos
$stmt = $pdo->prepare("
    SELECT i.*, e.nome AS evento_nome, e.data AS evento_data, e.local AS evento_local, e.imagem, e.obs
    FROM inscricoes i
    JOIN eventos e ON e.id = i.id_evento
    WHERE i.id_lacador = ?
    ORDER BY i.id DESC
");
$stmt->execute([$id_lacador]);
$inscricoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Eventos</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <style>
        :root {
            --bg-light: #f5f6f6;
            --gray7: #343434;
            --gray5: #565656;
            --gray8: #282828;
        }

        body {
            font-family: 'Segoe UI';
            background: var(--bg-light);
            margin: 0;
            padding-bottom: 100px;
        }

        header {
            background: var(--gray7);
            color: white;
            padding: 14px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            position: sticky;
            top: 0;
            z-index: 20;
        }

        header img {
            height: 38px;
            border-radius: 8px;
        }

        .evento-card {
            background: white;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0, 0, 0, .12);
            margin-bottom: 18px;
        }

        .evento-card img {
            width: 100%;
            height: 160px;
            object-fit: cover;
        }

        .status-badge {
            padding: 6px 12px;
            font-size: 13px;
            border-radius: 8px;
            color: white;
            font-weight: 600;
        }

        .pendente {
            background: #d9822b;
        }

        .aprovado {
            background: #2b8f42;
        }

        .cancelado {
            background: #c62828;
        }

        .reprovado {
            background: #c62828;
        }

        footer {
            background: #fff;
            border-top: 1px solid #ddd;
            padding: 22px 0;
            display: flex;
            justify-content: space-around;
            position: fixed;
            bottom: 0;
            width: 100%;
            z-index: 10;
        }

        footer a {
            background: none;
            color: var(--gray5);
            font-size: 20px;
            text-decoration: none;
            text-align: center;
            transition: 0.1s;
            padding: 8px 12px;
            border-radius: 8px;
        }

        footer a:active {
            transform: scale(.92);
            opacity: .6;
            background: rgba(0,0,0,0.05);
        }
    </style>
</head>

<body>

<header>
    <img src="/pwa/assets/logo512px_new.png">
    <h2 style="font-size: 18px; margin: 0;">Meus Eventos</h2>
</header>

<div class="container mt-3">

    <?php if (empty($inscricoes)): ?>
        <p class="text-center mt-4">Você ainda não está inscrito em nenhum evento.</p>
    <?php endif; ?>

    <?php foreach ($inscricoes as $i): ?>
        <?php
        $data_formatada = date("d/m/Y", strtotime($i['evento_data']));
        $imgPath = "/pwa_painel/uploads/" . htmlspecialchars($i['imagem']);
        ?>
        <div class="evento-card">

            <img src="<?= $imgPath ?>" alt="Imagem do evento">

            <div class="p-3">
                <h5 class="mb-1"><?= htmlspecialchars($i['evento_nome']) ?></h5>
                <p class="mb-1"><strong>📅 Data:</strong> <?= $data_formatada ?></p>
                <p class="mb-1"><strong>📍 Local:</strong> <?= htmlspecialchars($i['evento_local']) ?></p>
                <p class="mb-1"><strong>🎯 Modalidade:</strong> <?= htmlspecialchars($i['tipo']) ?></p>
                <?php if (!empty($i['obs'])): ?>
                    <p class="mb-1"><strong>📝 Observação:</strong> <?= htmlspecialchars($i['obs']) ?></p>
                <?php endif; ?>

                <!-- STATUS COLORIDO -->
                <?php
                $statusClass = strtolower($i['status']);
                ?>
                <span class="status-badge <?= $statusClass ?>">
                    <?= strtoupper($i['status']) ?>
                </span>

            </div>

        </div>
    <?php endforeach; ?>

</div>

<footer>
    <a href="/pwa/painel-cliente"><i class="bi bi-house"></i> Início</a>
    <a href="/pwa/app/Views/meus_eventos.php"><i class="bi bi-calendar-check"></i> Meus Eventos</a>
</footer>

</body>
</html>
