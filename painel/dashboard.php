<?php
require 'inc/session.php';
checkLogin();
require 'inc/config.php';

//Eventos contagem
$stmt = $pdo->prepare("SELECT * FROM eventos ORDER BY id DESC");
$stmt->execute();
$eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);
$qtd_eventos = count($eventos);

//Eventos Corredores/laçadores
$stmt = $pdo->prepare("SELECT * FROM `lacadores` ORDER BY id DESC");
$stmt->execute();
$corredores = $stmt->fetchAll(PDO::FETCH_ASSOC);
$qtd_corredores = count($corredores);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Corrida de Boi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color: #f5f6f6;
            position: relative;
            min-height: 100vh;
        }

        .sidebar {
            width: 220px;
            transition: width 0.3s;
            overflow: hidden;
            background-color: #343434;
            color: #f5f6f6;
        }

        .sidebar.collapsed {
            width: 70px;
        }

        .sidebar .logo {
            font-size: 1.5rem;
            font-weight: bold;
            padding: 15px;
            text-align: center;
            white-space: nowrap;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            padding: 10px 20px;
            color: #f5f6f6;
            text-decoration: none;
            white-space: nowrap;
            transition: background 0.2s;
        }

        .sidebar a:hover {
            background-color: #565656;
        }

        .sidebar a i {
            font-size: 1.2rem;
            margin-right: 10px;
        }

        .sidebar.collapsed a span {
            display: none;
        }

        .sidebar.collapsed .logo {
            font-size: 1.5rem;
            padding: 15px 0;
        }

        .content {
            flex-grow: 1;
            padding: 20px;
            transition: margin-left 0.3s;
            position: relative;
            z-index: 1;
            /* garante que o conteúdo fique acima do fundo */
        }

        .toggle-btn {
            cursor: pointer;
            margin-bottom: 20px;
        }

        /* BLOCO DE FUNDO REUTILIZÁVEL */
        .background-image {
            position: absolute;

            /* 1. Correção: Remover aspas e usar ; */
            overflow: hidden;

            top: 350px;

            /* 2. Otimização: Não precisa de 'height: 100%' nem 'bottom: 0px' se já usa 'top' e 'position: absolute' */
            /* Se a intenção é que ocupe todo o restante da altura visível: */
            /* top: 150px; bottom: 0; */

            bottom: 0px;
            /* Mantido para garantir que ocupe o restante da altura até a base */
            left: 220px;

            /* ajusta conforme a largura da sidebar */
            width: calc(100% - 220px);

            /* height: 100%; (Removido, pois 'bottom: 0px' e 'top: 150px' já definem a altura) */

            background-image: url('img/1.png');
            background-size: cover;
            background-position: center;
            opacity: 0.1;
            /* transparência */
            z-index: 0;
            pointer-events: none;
            /* evita interferir em cliques */
            transition: left 0.3s, width 0.3s;
        }

        .sidebar.collapsed~.background-image {
            left: 70px;
            width: calc(100% - 70px);
        }
    </style>
</head>

<body>

    <div class="d-flex">
        <div class="sidebar" id="sidebar">
            <div class="logo">
                <img id="logoImg" src="img/3.png" alt="Logo" style="width: 100px; border-radius: 10px;">
            </div>
            <a href="dashboard.php"><i class="bi bi-house-door-fill"></i> <span>Dashboard</span></a>
            <a href="eventos.php"><i class="bi bi-calendar-event-fill"></i> <span>Eventos</span></a>
            <a href="corredores.php"><i class="bi bi-person-fill"></i> <span>Corredores</span></a>
            <a href="logout.php"><i class="bi bi-box-arrow-right"></i> <span>Sair</span></a>
        </div>

        <!-- BLOCO DE FUNDO -->
        <div class="background-image" id="backgroundImage"></div>

        <div class="content">
            <button class="btn btn-dark toggle-btn" id="toggleSidebar">
                <i class="bi bi-list"></i>
            </button>

            <div class="card p-3 mb-3 d-flex justify-content-between align-items-center">
                <div>
                    <h4>Bem-vindo, <?= $_SESSION['nome'] ?></h4>
                    <p>Resumo rápido do painel.</p>
                </div>
                <button class="btn btn-primary" id="exportBtn">
                    <i class="bi bi-download"></i> Exportar Dados
                </button>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card p-3 text-center">
                        <i class="bi bi-calendar-event-fill fs-2"></i>
                        <h5 class="mt-2">Total de Eventos</h5>
                        <p><?= $qtd_eventos; ?></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card p-3 text-center">
                        <i class="bi bi-people-fill fs-2"></i>
                        <h5 class="mt-2">Total de Corredores</h5>
                        <p><?= $qtd_corredores; ?></p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        const toggleBtn = document.getElementById('toggleSidebar');
        const sidebar = document.getElementById('sidebar');
        const logoImg = document.getElementById('logoImg');
        const background = document.getElementById('backgroundImage');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');

            // Troca a imagem do logo conforme o estado da sidebar
            if (sidebar.classList.contains('collapsed')) {
                logoImg.src = 'img/logofavicon.png';
            } else {
                logoImg.src = 'img/3.png';
            }
        });
    </script>

    <!-- Modal for Export -->
    <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exportModalLabel">Exportar Dados</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="spinner" class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Processando...</span>
                        </div>
                        <p>Processando dados...</p>
                    </div>
                    <div id="exportContent" style="display: none;">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6>Dados Exportados</h6>
                            <button id="downloadBtn" class="btn btn-success btn-sm">
                                <i class="bi bi-download"></i> Baixar JSON
                            </button>
                        </div>
                        <div style="max-height: 400px; overflow-y: auto; border: 1px solid #dee2e6; border-radius: 0.375rem; padding: 10px;">
                            <pre id="jsonData" style="margin: 0; font-size: 0.875rem;"></pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('exportBtn').addEventListener('click', function() {
            const modal = new bootstrap.Modal(document.getElementById('exportModal'));
            modal.show();

            // Show spinner
            document.getElementById('spinner').style.display = 'block';
            document.getElementById('exportContent').style.display = 'none';

            // Fetch data
            fetch('export_data.php')
                .then(response => response.json())
                .then(data => {
                    // Hide spinner
                    document.getElementById('spinner').style.display = 'none';
                    document.getElementById('exportContent').style.display = 'block';

                    // Display JSON
                    document.getElementById('jsonData').textContent = JSON.stringify(data, null, 2);

                    // Setup download button
                    document.getElementById('downloadBtn').addEventListener('click', function() {
                        const blob = new Blob([JSON.stringify(data, null, 2)], {
                            type: 'application/json'
                        });
                        const url = URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.href = url;
                        a.download = 'dados_exportados.json';
                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);
                        URL.revokeObjectURL(url);
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('spinner').innerHTML = '<p>Erro ao processar dados.</p>';
                });
        });
    </script>

</body>

</html>