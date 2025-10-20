<?php
require 'inc/session.php';
checkLogin();
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
    <style>
        body {
            background-color: #f5f6f6;
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

        /* Quando sidebar está colapsada, esconder o texto */
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
        }

        .toggle-btn {
            cursor: pointer;
            margin-bottom: 20px;
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

        <div class="content">
            <button class="btn btn-dark toggle-btn" id="toggleSidebar">
                <i class="bi bi-list"></i>
            </button>

            <div class="card p-3 mb-3">
                <h4>Bem-vindo, <?= $_SESSION['nome'] ?></h4>
                <p>Resumo rápido do painel.</p>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card p-3 text-center">
                        <i class="bi bi-calendar-event-fill fs-2"></i>
                        <h5 class="mt-2">Total de Eventos</h5>
                        <p>0</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card p-3 text-center">
                        <i class="bi bi-people-fill fs-2"></i>
                        <h5 class="mt-2">Total de Corredores</h5>
                        <p>0</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        const toggleBtn = document.getElementById('toggleSidebar');
        const sidebar = document.getElementById('sidebar');
        const logoImg = document.getElementById('logoImg');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');

            // Troca a imagem do logo conforme o estado da sidebar
            if (sidebar.classList.contains('collapsed')) {
                logoImg.src = 'img/logofavicon.png'; // sidebar fechada
            } else {
                logoImg.src = 'img/3.png'; // sidebar aberta
            }
        });
    </script>

</body>

</html>