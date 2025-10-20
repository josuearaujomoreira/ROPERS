<?php
require 'inc/session.php';
require 'inc/config.php';
checkLogin();

// Deletar evento
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM eventos WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: eventos.php");
    exit;
}

// Cadastro de evento
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nome_evento'])){
    $nome_evento = $_POST['nome_evento'];
    $data_evento = $_POST['data_evento'];
    $stmt = $pdo->prepare("INSERT INTO eventos (nome, data) VALUES (?, ?)");
    $stmt->execute([$nome_evento, $data_evento]);
}

// Busca
$busca = $_GET['busca'] ?? '';
$stmt = $pdo->prepare("SELECT * FROM eventos WHERE nome LIKE ? ORDER BY data DESC");
$stmt->execute(["%$busca%"]);
$eventos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Eventos - Corrida de Boi</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<link rel="stylesheet" href="css/style.css">
<style>
.sidebar { width: 220px; transition: width 0.3s; overflow: hidden; background-color: #343434; color: #f5f6f6; }
.sidebar.collapsed { width: 70px; }
.sidebar .logo { font-size: 1.5rem; font-weight: bold; padding: 15px; text-align: center; white-space: nowrap; }
.sidebar a { display: flex; align-items: center; padding: 10px 20px; color: #f5f6f6; text-decoration: none; white-space: nowrap; transition: background 0.2s; }
.sidebar a:hover { background-color: #565656; }
.sidebar a i { font-size: 1.2rem; margin-right: 10px; }
.sidebar.collapsed a span { display: none; }
.sidebar.collapsed .logo { font-size: 1.5rem; padding: 15px 0; }
.content { flex-grow: 1; padding: 20px; transition: margin-left 0.3s; }
.toggle-btn { cursor: pointer; margin-bottom: 20px; }
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
        <button class="btn btn-dark toggle-btn" id="toggleSidebar"><i class="bi bi-list"></i></button>

        <div class="card p-3 mb-3">
            <h4>Cadastrar Evento</h4>
            <form method="POST" class="row g-3">
                <div class="col-md-6">
                    <input type="text" name="nome_evento" placeholder="Nome do Evento" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <input type="date" name="data_evento" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100">Cadastrar</button>
                </div>
            </form>
        </div>

        <div class="card p-3 mt-3">
            <h4>Eventos Cadastrados</h4>
            <!-- Barra de busca -->
            <form method="GET" class="mb-3 d-flex">
                <input type="text" name="busca" class="form-control me-2" placeholder="Buscar evento..." value="<?= htmlspecialchars($busca) ?>">
                <button class="btn btn-secondary">Buscar</button>
            </form>

            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Data</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($eventos as $e): ?>
                        <tr>
                            <td><?= $e['id'] ?></td>
                            <td><?= $e['nome'] ?></td>
                            <td><?= $e['data'] ?></td>
                            <td>
                                <a href="cadastrados.php?evento=<?= $e['id'] ?>" class="btn btn-success btn-sm">
                                    <i class="bi bi-person-lines-fill"></i> Entrar
                                </a>
                                <a href="editar_evento.php?id=<?= $e['id'] ?>" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil-fill"></i> Editar
                                </a>
                                <a href="eventos.php?delete=<?= $e['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Deseja realmente apagar este evento?');">
                                    <i class="bi bi-trash-fill"></i> Apagar
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if(count($eventos) === 0): ?>
                        <tr>
                            <td colspan="4" class="text-center">Nenhum evento encontrado</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

<script>
const toggleBtn = document.getElementById('toggleSidebar');
const sidebar = document.getElementById('sidebar');
const logoImg = document.getElementById('logoImg');

toggleBtn.addEventListener('click', () => {
    sidebar.classList.toggle('collapsed');
    logoImg.src = sidebar.classList.contains('collapsed') ? 'img/logofavicon.png' : 'img/3.png';
});
</script>

</body>
</html>
