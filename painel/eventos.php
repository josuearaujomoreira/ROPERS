<?php
/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */
require 'inc/session.php';
checkLogin();
require 'inc/config.php';


function limparValor($valor) {
    $valor = str_replace(['R$', ' ', '.'], '', $valor);
    $valor = str_replace(',', '.', $valor);
    return floatval($valor);
}
// CADASTRAR EVENTO
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nome'])) {

    $nome = trim($_POST['nome']);
    $data_evento = $_POST['data_evento'];
    $status = $_POST['status'];
    $local = trim($_POST['local']);
    $imagem = '';
    $file = '';

    // Upload da imagem
    if (!empty($_FILES['imagem']['name'])) {
        $ext = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
        $novo_nome = uniqid() . '.' . $ext;
        $pasta = 'uploads/';
        if (!is_dir($pasta)) mkdir($pasta, 0777, true);
        move_uploaded_file($_FILES['imagem']['tmp_name'], $pasta . $novo_nome);
        $imagem = $novo_nome;
    }

    // Upload do PDF regulamento
    if (!empty($_FILES['Arquivo']['name'])) {
        $ext = pathinfo($_FILES['Arquivo']['name'], PATHINFO_EXTENSION);
        $novo_nome = uniqid() . '.' . $ext;
        $pasta = 'uploads/';
        if (!is_dir($pasta)) mkdir($pasta, 0777, true);
        move_uploaded_file($_FILES['Arquivo']['tmp_name'], $pasta . $novo_nome);
        $file = $novo_nome;
    }

    // 👉 SALVA O EVENTO
    $stmt = $pdo->prepare("INSERT INTO eventos (nome, data, local, imagem, status, file)
                           VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$nome, $data_evento, $local, $imagem, $status, $file]);

    // ID do evento recém cadastrado
    $id_evento = $pdo->lastInsertId();

    // 👉 RECEBE AS CATEGORIAS
    if (isset($_POST['categorias_nome']) && isset($_POST['categorias_valor'])) {

        $nomes = $_POST['categorias_nome'];
        $valores = $_POST['categorias_valor'];

        // Loop para salvar cada categoria
        for ($i = 0; $i < count($nomes); $i++) {

            // ignora itens vazios
            if (empty(trim($nomes[$i]))) continue;

            $categoria_nome = trim($nomes[$i]);
            $categoria_valor = limparValor($valores[$i]); // R$ 50,00 → 50.00

            $stmt2 = $pdo->prepare("INSERT INTO categorias_evento (id_evento, nome, valor)
                                    VALUES (?, ?, ?)");
            $stmt2->execute([
                $id_evento,
                $categoria_nome,
                $categoria_valor
            ]);
        }
    }

    // Redireciona
    header("Location: eventos.php");
    exit;
}


// EXCLUIR EVENTO
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM eventos WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: eventos.php");
    exit;
}

// BUSCA
$busca = isset($_GET['busca']) ? trim($_GET['busca']) : '';
$stmt = $pdo->prepare("SELECT * FROM eventos WHERE nome LIKE ? ORDER BY id DESC");
$stmt->execute(["%$busca%"]);
$eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);
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

        .event-img {
            width: 80px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
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
            <button class="btn btn-dark toggle-btn" id="toggleSidebar"><i class="bi bi-list"></i></button>

            <!-- BOTÃO DE CADASTRAR -->
            <div class="card p-3 mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h4>Eventos</h4>
                    <button class="btn btn-success" data-bs-toggle="collapse" data-bs-target="#formEvento">
                        <i class="bi bi-plus-circle"></i> Novo Evento
                    </button>
                </div>

                <!-- FORMULÁRIO (COLLAPSE) -->
                <div class="collapse mt-3" id="formEvento">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Nome do Evento</label>
                                <input type="text" name="nome" class="form-control" required>
                            </div>
                            <div class="col-md-2 mb-2">
                                <label class="form-label">Data</label>
                                <input type="date" name="data_evento" class="form-control" required>
                            </div>
                            <div class="col-md-2 mb-2">
                                <label class="form-label">Status</label>
                                <select class="form-control" name="status" id="">
                                    <option value="Ativo">Ativo</option>
                                    <option value="Desativado">Desativado</option>
                                </select>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label">PDF regulamento (Opcional)</label>
                                <input type="file" name="Arquivo" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8 mb-6">
                                <label class="form-label">Local</label>
                                <input type="text" name="local" class="form-control" required>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <label class="form-label">Categorias</label>

                            <div id="categorias-container">

                                <!-- Primeiro item -->
                                <div class="categoria-item mb-2 row">
                                    <div class="col-md-6">
                                        <input type="text" name="categorias_nome[]" class="form-control" placeholder="Categoria (ex: Pacote 01)" required>
                                    </div>

                                    <div class="col-md-4">
                                        <input type="text" name="categorias_valor[]" class="form-control moeda" placeholder="R$ 0,00" required oninput="formatarMoeda(this)">
                                    </div>

                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-danger w-100" onclick="removerCategoria(this)">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </div>
                                </div>

                            </div>

                            <button type="button" class="btn btn-success mt-2" onclick="adicionarCategoria()">
                                <i class="bi bi-plus-lg"></i> Adicionar categoria
                            </button>
                        </div>

                        <script>
                            // Função de máscara de moeda
                            function formatarMoeda(campo) {
                                let valor = campo.value.replace(/\D/g, "");

                                valor = (valor / 100).toFixed(2) + "";
                                valor = valor.replace(".", ",");
                                valor = "R$ " + valor.replace(/\B(?=(\d{3})+(?!\d))/g, ".");

                                campo.value = valor;
                            }

                            function adicionarCategoria() {
                                const container = document.getElementById('categorias-container');

                                const div = document.createElement('div');
                                div.classList.add('categoria-item', 'mb-2', 'row');

                                div.innerHTML = `
                        <div class="col-md-6">
                            <input type="text" name="categorias_nome[]" class="form-control" placeholder="Categoria (ex: Pacote 01)" required>
                        </div>

                        <div class="col-md-4">
                            <input type="text" name="categorias_valor[]" class="form-control moeda" placeholder="R$ 0,00" required oninput="formatarMoeda(this)">
                        </div>

                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger w-100" onclick="removerCategoria(this)">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                       `;

                                container.appendChild(div);
                            }

                            function removerCategoria(btn) {
                                btn.closest('.categoria-item').remove();
                            }
                        </script>

                        <br>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Flyer / Foto do Evento</label>
                                <input type="file" name="imagem" class="form-control" accept="image/*" onchange="previewImage(event)">
                                <img id="preview" class="preview-img" style="display:none;">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success"><i class="bi bi-save2"></i> Salvar Evento</button>
                    </form>
                </div>
            </div>

            <!-- LISTAGEM DE EVENTOS -->
            <div class="card p-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5>Eventos Cadastrados</h5>
                    <form method="GET" class="d-flex">
                        <input type="text" name="busca" class="form-control me-2" placeholder="Buscar evento..." value="<?= htmlspecialchars($busca) ?>">
                        <button class="btn btn-primary"><i class="bi bi-search"></i></button>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Imagem</th>
                                <th>Nome</th>
                                <th>Data</th>
                                <th>Local</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($eventos) > 0): ?>
                                <?php foreach ($eventos as $e): ?>
                                    <tr>
                                        <td><img src="uploads/<?= htmlspecialchars($e['imagem']) ?>" class="event-img" alt=""></td>
                                        <td><?= htmlspecialchars($e['nome']) ?></td>
                                        <td><?= date('d/m/Y', strtotime($e['data'])) ?></td>
                                        <td><?= htmlspecialchars($e['local']) ?></td>
                                        <td>
                                            <a href="editar_evento.php?id=<?= $e['id'] ?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil-fill"></i></a>
                                            <a href="ver_evento.php?id=<?= $e['id'] ?>" class="btn btn-info btn-sm text-white"><i class="bi bi-eye-fill"></i></a>
                                            <a href="eventos.php?delete=<?= $e['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apagar este evento?')"><i class="bi bi-trash-fill"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">Nenhum evento encontrado.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
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
            preview.style.display = 'block';
            preview.src = URL.createObjectURL(event.target.files[0]);
        }
    </script>
</body>

</html>