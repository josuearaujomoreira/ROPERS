<?php
require 'inc/session.php';
checkLogin();
require 'inc/config.php';

// Verifica se o ID foi passado
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: eventos.php");
    exit;
}

$id = intval($_GET['id']);

// Busca dados do evento
$stmt = $pdo->prepare("SELECT * FROM eventos WHERE id = ?");
$stmt->execute([$id]);
$evento = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$evento) {
    echo "<script>alert('Evento não encontrado!'); window.location='eventos.php';</script>";
    exit;
}

// Atualizar evento
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nome'])) {
    $nome = trim($_POST['nome']);
    $data_evento = $_POST['data_evento'];
    $status = $_POST['status'];
    $local = trim($_POST['local']);
    $imagem = $evento['imagem']; // mantém a imagem atual, se não for trocada

    // Upload nova imagem (se houver)
    if (!empty($_FILES['imagem']['name'])) {
        $ext = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
        $novo_nome = uniqid() . '.' . $ext;
        $pasta = 'uploads/';
        if (!is_dir($pasta)) mkdir($pasta, 0777, true);
        move_uploaded_file($_FILES['imagem']['tmp_name'], $pasta . $novo_nome);
        $imagem = $novo_nome;
    }

    $stmt = $pdo->prepare("UPDATE eventos SET nome = ?, data = ?, local = ?, imagem = ?, status = ? WHERE id = ?");
    $stmt->execute([$nome, $data_evento, $local, $imagem, $status,  $id]);

    header("Location: eventos.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Evento - Corrida de Boi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f5f6f6;
            min-height: 100vh;
            margin: 0;
        }

        .sidebar {
            width: 220px;
            transition: width 0.3s;
            background-color: #343434;
            color: #f5f6f6;
            min-height: 100vh;
        }

        .sidebar.collapsed {
            width: 70px;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            padding: 10px 20px;
            color: #f5f6f6;
            text-decoration: none;
            transition: background 0.2s;
        }

        .sidebar a:hover {
            background-color: #565656;
        }

        .sidebar a i {
            margin-right: 10px;
        }

        .sidebar.collapsed a span {
            display: none;
        }

        .content {
            flex-grow: 1;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        .toggle-btn {
            cursor: pointer;
            margin-bottom: 20px;
        }

        .preview-img {
            width: 100%;
            max-width: 250px;
            border-radius: 10px;
            margin-top: 10px;
        }

        .bg-logo {
            position: absolute;
            top: 200px;
            left: 50%;
            transform: translateX(-50%);
            opacity: 0.05;
            z-index: 0;
        }
    </style>
</head>

<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="logo text-center py-3">
                <img id="logoImg" src="img/3.png" alt="Logo" style="width: 100px; border-radius: 10px;">
            </div>
            <a href="dashboard.php"><i class="bi bi-house-door-fill"></i> <span>Dashboard</span></a>
            <a href="eventos.php"><i class="bi bi-calendar-event-fill"></i> <span>Eventos</span></a>
            <a href="corredores.php"><i class="bi bi-person-fill"></i> <span>Corredores</span></a>
            <a href="logout.php"><i class="bi bi-box-arrow-right"></i> <span>Sair</span></a>
        </div>

        <!-- Conteúdo principal -->
        <div class="content">
            <img src="img/1.png" class="bg-logo" alt="">
            <button class="btn btn-dark toggle-btn" id="toggleSidebar"><i class="bi bi-list"></i></button>

            <div class="card p-4" style="position: relative; z-index: 1;">
                <h4><i class="bi bi-pencil-square"></i> Editar Evento</h4>
                <hr>

                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Nome do Evento</label>
                            <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($evento['nome']) ?>" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Data</label>
                            <input type="date" name="data_evento" class="form-control" value="<?= htmlspecialchars($evento['data']) ?>" required>
                        </div>
                        <div class="col-md-5 mb-3">
                            <label class="form-label">Local</label>
                            <input type="text" name="local" class="form-control" value="<?= htmlspecialchars($evento['local']) ?>" required>
                        </div>

                        <div class="col-md-5 mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-control" name="status" id="">
                                <option value="Ativo" <?php echo $evento['status'] == 'Ativo' ? 'Selected' : ''  ?>>Ativo</option>
                                <option value="Desativado"<?php echo $evento['status']=='Desativado' ? 'Selected' : '' ?>>Desativado</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Flyer / Foto do Evento</label>
                            <input type="file" name="imagem" class="form-control" accept="image/*" onchange="previewImage(event)">
                            <img id="preview" class="preview-img" src="uploads/<?= htmlspecialchars($evento['imagem']) ?>" alt="Preview">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary"><i class="bi bi-save2"></i> Salvar Alterações</button>
                    <a href="eventos.php" class="btn btn-secondary ms-2"><i class="bi bi-arrow-left"></i> Voltar</a>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const toggleBtn = document.getElementById('toggleSidebar');
        const sidebar = document.getElementById('sidebar');
        const logoImg = document.getElementById('logoImg');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            logoImg.src = sidebar.classList.contains('collapsed') ? 'img/logofavicon.png' : 'img/3.png';
        });

        function previewImage(event) {
            const preview = document.getElementById('preview');
            preview.src = URL.createObjectURL(event.target.files[0]);
            preview.style.display = 'block';
        }
    </script>
</body>

</html>