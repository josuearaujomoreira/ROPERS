<?php
require 'inc/session.php';
checkLogin();
require 'inc/config.php';

// Verificar se ID foi passado
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: corredores.php");
    exit;
}

$id = $_GET['id'];

// Buscar dados do corredor
$stmt = $pdo->prepare("SELECT * FROM lacadores WHERE id = ?");
$stmt->execute([$id]);
$corredor = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$corredor) {
    header("Location: corredores.php");
    exit;
}

// ATUALIZAR CORREDOR
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $apelido = trim($_POST['apelido']);
    $cpf = trim($_POST['cpf']);
    $whatsapp = trim($_POST['whatsapp']);
    $cidade = trim($_POST['cidade']);
    $uf = trim($_POST['uf']);
    $handicap_cabeca = $_POST['handicap_cabeca'] ?: 0.0;
    $handicap_pe = $_POST['handicap_pe'] ?: 0.0;

    // Verificar se senha foi alterada
    $senha = $corredor['senha'];
    if (!empty($_POST['senha'])) {
        $senha = $_POST['senha'];
    }

    $foto = $corredor['foto'];
    if (!empty($_FILES['foto']['name'])) {
        // Remover foto antiga se existir
        if ($foto && file_exists('uploads/' . $foto)) {
            unlink('uploads/' . $foto);
        }

        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $novo_nome = uniqid() . '.' . $ext;
        $pasta = 'uploads/';
        if (!is_dir($pasta)) mkdir($pasta, 0777, true);
        move_uploaded_file($_FILES['foto']['tmp_name'], $pasta . $novo_nome);
        $foto = $novo_nome;
    }

    $stmt = $pdo->prepare("UPDATE lacadores SET nome = ?, apelido = ?, cpf = ?, senha = ?, whatsapp = ?, cidade = ?, uf = ?, handicap_cabeca = ?, handicap_pe = ?, foto = ? WHERE id = ?");
    $stmt->execute([$nome, $apelido, $cpf, $senha, $whatsapp, $cidade, $uf, $handicap_cabeca, $handicap_pe, $foto, $id]);

    echo "<script>alert('Corredor atualizado com sucesso!'); window.location.href = 'corredores.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Editar Corredor - Corrida de Boi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<style>
    .content {
        flex-grow: 1;
        padding: 20px;
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
</style>

<body>
    <div class="d-flex">
        <div class="sidebar" id="sidebar">
            <div class="logo text-center py-3">
                <img id="logoImg" src="img/3.png" alt="Logo" style="width: 100px; border-radius: 10px;">
            </div>
            <a href="dashboard.php"><i class="bi bi-house-door-fill"></i> <span>Dashboard</span></a>
            <a href="eventos.php"><i class="bi bi-calendar-event-fill"></i> <span>Eventos</span></a>
            <a href="corredores.php"><i class="bi bi-person-fill"></i> <span>Corredores</span></a>
            <a href="logout.php"><i class="bi bi-box-arrow-right"></i> <span>Sair</span></a>
        </div>

        <div class="content">
            <button class="btn btn-dark toggle-btn" id="toggleSidebar"><i class="bi bi-list"></i></button>

            <div class="card p-4">
                <h4 class="mb-4">Editar Corredor</h4>

                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Nome</label>
                            <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($corredor['nome']) ?>" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Apelido</label>
                            <input type="text" name="apelido" class="form-control" value="<?= htmlspecialchars($corredor['apelido']) ?>" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">CPF</label>
                            <input type="text" name="cpf" class="form-control" value="<?= htmlspecialchars($corredor['cpf']) ?>" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Nova Senha (deixe em branco para manter)</label>
                            <input type="password" name="senha" class="form-control">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">WhatsApp</label>
                            <input type="text" name="whatsapp" class="form-control" value="<?= htmlspecialchars($corredor['whatsapp']) ?>">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Cidade</label>
                            <input type="text" name="cidade" class="form-control" value="<?= htmlspecialchars($corredor['cidade']) ?>">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">UF</label>
                            <input type="text" name="uf" maxlength="2" class="form-control" value="<?= htmlspecialchars($corredor['uf']) ?>">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Handicap Cabeça</label>
                            <input type="number" step="0.1" name="handicap_cabeca" class="form-control" value="<?= htmlspecialchars($corredor['handicap_cabeca']) ?>">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Handicap Pé</label>
                            <input type="number" step="0.1" name="handicap_pe" class="form-control" value="<?= htmlspecialchars($corredor['handicap_pe']) ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Foto Atual</label>
                            <div class="mb-2">
                                <?php if ($corredor['foto']): ?>
                                    <img src="/pwa/app/uploads/<?= htmlspecialchars($corredor['foto']) ?>" class="rounded" width="100">
                                <?php else: ?>
                                    <i class="bi bi-person-circle fs-1"></i>
                                <?php endif; ?>
                            </div>
                            <label class="form-label">Nova Foto (opcional)</label>
                            <input type="file" name="foto" class="form-control" accept="image/*">
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save2"></i> Atualizar</button>
                        <a href="corredores.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Voltar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle Sidebar
        document.getElementById('toggleSidebar').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('collapsed');
        });
    </script>
</body>

</html>
