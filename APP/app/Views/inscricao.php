<?php
require __DIR__ . '/../Config/database.php';
session_start();

// Verificar login
if (!isset($_SESSION['nome'])) {
  header("Location: /pwa/login");
  exit();
}

$nome = $_SESSION['nome'] ?? 'Laçador';
$id_cliente = $_SESSION['usuario_id'];

// Pegar ID do evento
$id_evento = $_GET['id'] ?? null;
if (!$id_evento) {
  echo "Evento não encontrado!";
  exit;
}

// Buscar dados do laçador
$stmt = $pdo->prepare("SELECT * FROM lacadores WHERE id = ?");
$stmt->execute([$id_cliente]);
$lacador = $stmt->fetch(PDO::FETCH_ASSOC);

// Buscar dados do evento
$stmt = $pdo->prepare("SELECT * FROM eventos WHERE id = ?");
$stmt->execute([$id_evento]);
$evento = $stmt->fetch(PDO::FETCH_ASSOC);

// Caminho da imagem
$imagemPath = '/pwa_painel/uploads/' . htmlspecialchars($evento['imagem']);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inscrição no Evento</title>

  <link rel="manifest" href="/pwa/manifest.json">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <style>
    .valor-box {
      background: #f8f9fa;
      border-radius: 10px;
      padding: 12px 15px;
      display: flex;
      align-items: center;
      gap: 12px;
      border: 1px solid #e3e6ea;
      max-width: 300px;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.07);
    }

    .valor-label {
      font-weight: bold;
      color: #333;
      font-size: 15px;
    }

    .valor-input {
      border: none;
      background: transparent;
      font-size: 20px;
      font-weight: bold;
      color: #28a745;
      outline: none;
      width: 120px;
      text-align: right;
    }

    :root {
      --bg-light: #f5f6f6;
      --gray7: #343434;
      --gray5: #565656;
      --gray8: #282828;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      background: var(--bg-light);
      color: var(--gray8);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    header {
      background: var(--gray7);
      color: white;
      padding: 12px 20px;
      display: flex;
      align-items: center;
      position: sticky;
      top: 0;
      z-index: 10;
    }

    header img {
      height: 38px;
      border-radius: 8px;
    }

    main {
      flex: 1;
      padding: 20px;
      padding-bottom: 90px;
    }

    .card-inscricao {
      background: white;
      border-radius: 16px;
      box-shadow: 0 3px 12px rgba(0, 0, 0, 0.15);
      overflow: hidden;
    }

    .card-inscricao img {
      width: 100%;
      height: 200px;
      object-fit: cover;
      display: block;
      cursor: pointer;
      transition: opacity .2s;
    }

    .card-inscricao img:hover {
      opacity: 0.9;
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
      border: none;
      color: var(--gray5);
      font-size: 14px;
      text-decoration: none;
      text-align: center;
      transition: transform 0.1s ease, opacity 0.1s ease;
      padding: 8px 12px;
      border-radius: 8px;
    }

    /* Efeito ao tocar (mobile friendly) */
    footer a:active {
      transform: scale(0.92);
      opacity: 0.6;
      background: rgba(0, 0, 0, 0.05);
    }

    /* MODAL FULLSCREEN */
    #imgModal {
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, .85);
      display: none;
      justify-content: center;
      align-items: center;
      z-index: 99999;
      padding: 20px;
    }

    #imgModal img {
      max-width: 100%;
      max-height: 90vh;
      border-radius: 8px;
      object-fit: contain;
    }

    #closeModal {
      position: absolute;
      top: 25px;
      right: 25px;
      color: white;
      background: rgba(0, 0, 0, .4);
      border: none;
      font-size: 26px;
      width: 45px;
      height: 45px;
      border-radius: 50%;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    #closeModal:hover {
      background: rgba(0, 0, 0, .7);
    }

    #btnSubmit.loading #btnText {
      opacity: 0.6;
    }

    #btnSubmit.loading {
      pointer-events: none;
      cursor: not-allowed;
    }
  </style>
</head>

<body>

  <header>
    <div class="d-flex align-items-center gap-2">
      <img src="/pwa/assets/logo512px_new.png">
      <h1 style="font-size: 18px; margin: 0;">Olá, <?= htmlspecialchars($nome) ?></h1>
    </div>
  </header>

  <main>
    <h2 class="mb-3">🎯 Confirmar Inscrição</h2>

    <div class="card-inscricao">

      <!-- IMAGEM CLICÁVEL -->
      <img id="openImage" src="<?= $imagemPath ?>" alt="Imagem do evento">

      <div class="p-3">
        <p><strong>Evento:</strong> <?= htmlspecialchars($evento['nome']) ?></p>
        <?php
        $data_formatada = date("d/m/Y", strtotime($evento['data']));
        ?>
        <p><strong>Data:</strong> <?= $data_formatada ?></p>

        <p><strong>Local:</strong> <?= htmlspecialchars($evento['local']) ?></p>
        <!-- VALOR -->
        <div class="valor-box mt-3">
          <span class="valor-currency">R$</span>

          <!-- Mostra na tela -->
          <input type="text" disabled id="inputValor" class="valor-input" value="0,00">

        
        </div>

        <form method="POST" action="/pwa/app/Views/salvar_inscricao.php">
          <input type="hidden" name="id_evento" value="<?= $id_evento ?>">
          <input type="hidden" name="id_lacador" value="<?= $id_cliente ?>">
            <!-- Envia no POST -->
          <input type="hidden" name="valor" id="valorHidden" value="0,00">

          <!-- SOMATÓRIA / CATEGORIA -->
          <label class="mt-3"><b>Escolher Somatória:</b></label>

          <select class="form-select mt-1" name="id_categoria" id="selectCategoria" required>
            <option value="">Escolha uma opção</option>

            <?php
            $stmt = $pdo->prepare("SELECT * FROM categorias_evento WHERE id_evento = ?");
            $stmt->execute([$id_evento]);
            $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($categorias as $cat) {
              $valor_formatado = number_format($cat['valor'], 2, ',', '.');
              echo '<option value="' . $cat['id'] . '" data-valor="' . $valor_formatado . '">'
                . $cat['nome'] . ' - R$ ' . $valor_formatado .
                '</option>';
            }
            ?>
          </select>

          <script>
            document.getElementById('selectCategoria').addEventListener('change', function() {
              const selected = this.options[this.selectedIndex];
              const valor = selected.getAttribute('data-valor') || "0,00";

              // Atualiza a tela
              document.getElementById('inputValor').value = valor;

              // Envia no POST
              document.getElementById('valorHidden').value = valor;
            });
          </script>

          <!-- TIPO -->
          <label class="mt-3"><b>Escolher modalidade:</b></label>
          <select class="form-select mt-1" name="tipo" required>
            <option value="">Escolha uma opção</option>
            <option value="Pé">🔰 Pé</option>
            <option value="Cabeça">🔰 Cabeça</option>
            <option value="Ambos">🔰 Ambos</option>
          </select>

          <button id="btnSubmit" type="submit" class="btn btn-dark w-100 mt-4">
            <span id="btnText">Confirmar Inscrição</span>
            <div id="spinner" class="spinner-border spinner-border-sm" style="display:none; position:absolute; right:15px; top:50%; transform:translateY(-50%);"></div>
          </button>
        </form>



      </div>
    </div>

  </main>

  <footer>
    <a href="/pwa/painel-cliente"><i class="bi bi-house"></i> Início</a>
    <a href="/pwa/app/Views/meus_eventos.php"><i class="bi bi-calendar-check"></i> Meus Eventos</a>
  </footer>

  <!-- MODAL FULLSCREEN -->
  <div id="imgModal">
    <button id="closeModal">&times;</button>
    <img id="modalImg" src="">
  </div>

  <script>
    const openImage = document.getElementById("openImage");
    const imgModal = document.getElementById("imgModal");
    const modalImg = document.getElementById("modalImg");
    const closeModal = document.getElementById("closeModal");

    openImage.onclick = () => {
      modalImg.src = openImage.src;
      imgModal.style.display = "flex";
    };

    closeModal.onclick = () => {
      imgModal.style.display = "none";
    };

    imgModal.onclick = (e) => {
      if (e.target === imgModal) imgModal.style.display = "none";
    };

    const form = document.querySelector("form");
    const btnSubmit = document.getElementById("btnSubmit");
    const spinner = document.getElementById("spinner");
    const btnText = document.getElementById("btnText");

    form.addEventListener("submit", () => {
      btnSubmit.classList.add("loading");
      spinner.style.display = "inline-block";
      btnText.textContent = "Enviando...";
    });
  </script>



</body>

</html>