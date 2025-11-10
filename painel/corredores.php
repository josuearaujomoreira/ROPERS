<?php
require 'inc/session.php';
checkLogin();
require 'inc/config.php';

// CADASTRAR CORREDOR
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nome'])) {
    $nome = trim($_POST['nome']);
    $apelido = trim($_POST['apelido']);
    $cpf = trim($_POST['cpf']);
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $whatsapp = trim($_POST['whatsapp']);
    $cidade = trim($_POST['cidade']);
    $uf = trim($_POST['uf']);
    $handicap_cabeca = $_POST['handicap_cabeca'] ?: 0.0;
    $handicap_pe = $_POST['handicap_pe'] ?: 0.0;

    $foto = '';
    if (!empty($_FILES['foto']['name'])) {
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $novo_nome = uniqid() . '.' . $ext;
        $pasta = 'uploads/';
        if (!is_dir($pasta)) mkdir($pasta, 0777, true);
        move_uploaded_file($_FILES['foto']['tmp_name'], $pasta . $novo_nome);
        $foto = $novo_nome;
    }

    $stmt = $pdo->prepare("INSERT INTO lacadores (nome, apelido, cpf, senha, whatsapp, cidade, uf, handicap_cabeca, handicap_pe, foto) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$nome, $apelido, $cpf, $senha, $whatsapp, $cidade, $uf, $handicap_cabeca, $handicap_pe, $foto]);

    header("Location: corredores.php");
    exit;
}

// EXCLUIR
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM lacadores WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: corredores.php");
    exit;
}

// BUSCA
$busca = isset($_GET['busca']) ? trim($_GET['busca']) : '';
$stmt = $pdo->prepare("SELECT * FROM lacadores WHERE nome LIKE ? OR apelido LIKE ? ORDER BY id DESC");
$stmt->execute(["%$busca%", "%$busca%"]);
$corredores = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Corredores - Corrida de Boi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<style>
    .content {
        flex-grow: 1;
        padding: 20px;
    }

    .toggle-btn {
        cursor: pointer;
        margin-bottom: 20px;
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

            <div class="card p-3 mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h4>Corredores</h4>
                    <button class="btn btn-success" data-bs-toggle="collapse" data-bs-target="#formCorredor">
                        <i class="bi bi-plus-circle"></i> Novo Corredor
                    </button>
                </div>

                <div class="collapse mt-3" id="formCorredor">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Nome</label>
                                <input type="text" name="nome" class="form-control" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Apelido</label>
                                <input type="text" name="apelido" class="form-control" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">CPF</label>
                                <input type="text" name="cpf" class="form-control" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Senha</label>
                                <input type="password" name="senha" class="form-control" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">WhatsApp</label>
                                <input type="text" name="whatsapp" class="form-control">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Cidade</label>
                                <input type="text" name="cidade" class="form-control">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">UF</label>
                                <input type="text" name="uf" maxlength="2" class="form-control">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">Handicap Cabeça</label>
                                <input type="number" step="0.1" name="handicap_cabeca" class="form-control">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">Handicap Pé</label>
                                <input type="number" step="0.1" name="handicap_pe" class="form-control">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Foto</label>
                                <input type="file" name="foto" class="form-control" accept="image/*">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save2"></i> Salvar</button>
                    </form>
                </div>
            </div>

            <!-- LISTAGEM -->
            <div class="card p-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5>Corredores Cadastrados</h5>
                    <form method="GET" class="d-flex">
                        <input type="text" name="busca" class="form-control me-2" placeholder="Buscar..." value="<?= htmlspecialchars($busca) ?>">
                        <button class="btn btn-primary"><i class="bi bi-search"></i></button>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Foto</th>
                                <th>Nome</th>
                                <th>Apelido</th>
                                <th>Cidade</th>
                                <th>UF</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($corredores) > 0): ?>
                                <?php foreach ($corredores as $c): ?>
                                    <tr>
                                        <td>
                                            <?php if ($c['foto']): ?>
                                                <img src="/pwa/app/uploads/<?= htmlspecialchars($c['foto']) ?>" class="rounded" width="60">
                                            <?php else: ?>
                                                <i class="bi bi-person-circle fs-3"></i>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($c['nome']) ?></td>
                                        <td><?= htmlspecialchars($c['apelido']) ?></td>
                                        <td><?= htmlspecialchars($c['cidade']) ?></td>
                                        <td><?= htmlspecialchars($c['uf']) ?></td>
                                        <td>
                                            <button class="btn btn-info btn-sm text-white" data-bs-toggle="modal" data-bs-target="#modalEventos" data-id="<?= $c['id'] ?>"><i class="bi bi-eye-fill"></i></button>
                                            <a href="editar_corredor.php?id=<?= $c['id'] ?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil-fill"></i></a>
                                            <a href="corredores.php?delete=<?= $c['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Excluir este corredor?')"><i class="bi bi-trash-fill"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">Nenhum corredor encontrado.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL EVENTOS -->
    <div class="modal fade" id="modalEventos" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title">Eventos Inscritos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="listaEventos">Carregando...</div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const modalEventos = document.getElementById('modalEventos');
        modalEventos.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');

            fetch('listar_eventos_corredor.php?id=' + id)
                .then(res => res.text())
                .then(html => document.getElementById('listaEventos').innerHTML = html);
        });
    </script>
</body>

</html>