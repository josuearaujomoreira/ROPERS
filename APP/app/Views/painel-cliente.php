<?php
require __DIR__ . '/../Config/database.php';
session_start();
if (!isset($_SESSION['nome'])) {
  header("Location: /pwa/login");
  exit();
}
$nome = $_SESSION['nome'] ?? 'Laçador';
$id_cliente = $_SESSION['usuario_id'];

$dados_lancadores = "SELECT * FROM `lacadores` where `id` = '$id_cliente'";

$stmt = $pdo->prepare("SELECT * FROM lacadores WHERE id = ?");
$stmt->execute([$id_cliente]);

$lacador = $stmt->fetch(PDO::FETCH_ASSOC);


?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Painel do Laçador</title>
  <link rel="manifest" href="/pwa/manifest.json">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
  <style>
    :root {
      --bg-light: #f5f6f6;
      --gray1: #8c8c8c;
      --gray2: #848484;
      --gray3: #7c7c7c;
      --gray4: #747474;
      --gray5: #565656;
      --gray6: #3a3c3c;
      --gray7: #343434;
      --gray8: #282828;
      --black: #040404;
      --accent: #565656;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      background: var(--bg-light);
      color: var(--gray8);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      overflow-x: hidden;
    }

    header {
      background: var(--gray7);
      color: #fff;
      padding: 12px 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      position: sticky;
      top: 0;
      z-index: 20;
    }

    .navbar-left {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .navbar-left img {
      height: 38px;
      border-radius: 8px;
    }

    header h1 {
      font-size: 18px;
      font-weight: 500;
    }

    .profile-btn {
      background: var(--gray6);
      border: none;
      color: white;
      padding: 8px 12px;
      border-radius: 10px;
      cursor: pointer;
      transition: background 0.3s;
    }

    .profile-btn:hover {
      background: var(--gray5);
    }

    main {
      flex: 1;
      padding: 20px;
    }

    main h2 {
      color: var(--gray7);
      margin-bottom: 10px;
    }

    .carousel-container {
      position: relative;
      padding: 10px 0;
    }

    .carousel {
      display: flex;
      overflow-x: auto;
      gap: 15px;
      scroll-snap-type: x mandatory;
      scrollbar-width: none;
      scroll-behavior: smooth;
      padding: 0 10px;
    }

    .carousel::-webkit-scrollbar {
      display: none;
    }

    .event-card {
      flex: 0 0 85%;
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
      overflow: hidden;
      scroll-snap-align: start;
      transition: transform 0.3s;
      position: relative;
      max-width: 400px;
      margin: 0 auto;
    }

    .event-card:hover {
      transform: scale(1.03);
    }

    .event-card img {
      width: 100%;
      height: 180px;
      object-fit: cover;
      cursor: pointer;
      transition: transform 0.3s ease;
    }

    .event-card img:hover {
      transform: scale(1.02);
    }

    .event-info {
      padding: 15px;
    }

    .event-info h3 {
      font-size: 18px;
      color: var(--gray7);
      margin-bottom: 5px;
    }

    .event-info p {
      font-size: 14px;
      color: var(--gray3);
    }

    .event-info button {
      margin-top: 10px;
      width: 100%;
      padding: 10px;
      background: var(--gray7);
      color: #fff;
      border: none;
      border-radius: 8px;
      font-weight: bold;
      cursor: pointer;
      transition: background 0.3s;
    }

    .event-info button:hover {
      background: var(--gray6);
    }

    .carousel-btn {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      background: rgba(52, 52, 52, 0.9);
      color: #fff;
      border: none;
      font-size: 24px;
      width: 42px;
      height: 42px;
      border-radius: 50%;
      cursor: pointer;
      transition: opacity 0.3s;
      display: none;
      z-index: 15;
    }

    .carousel-btn:hover {
      opacity: 1;
      background: rgba(52, 52, 52, 1);
    }

    .carousel-btn.prev {
      left: 10px;
    }

    .carousel-btn.next {
      right: 10px;
    }

    @media (min-width: 768px) {
      .carousel-btn {
        display: flex;
        align-items: center;
        justify-content: center;
      }
    }

    footer {
      background: #fff;
      border-top: 1px solid #ddd;
      display: flex;
      justify-content: space-around;
      padding: 10px 0;
      position: fixed;
      bottom: 0;
      width: 100%;
      z-index: 10;
    }

    footer button {
      background: none;
      border: none;
      color: var(--gray4);
      font-size: 14px;
      cursor: pointer;
      display: flex;
      flex-direction: column;
      align-items: center;
      transition: color 0.3s;
    }

    footer button.active,
    footer button:hover {
      color: var(--gray7);
    }

    footer i {
      font-size: 20px;
      margin-bottom: 4px;
    }

    /* MODAL PERFIL */
    .modal {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.6);
      align-items: center;
      justify-content: center;
      z-index: 100;
      animation: fadeIn 0.3s;
    }

    .modal-content {
      background: white;
      padding: 20px;
      border-radius: 12px;
      width: 90%;
      max-width: 400px;
      box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
      animation: slideUp 0.3s;
    }

    /* MODAL DE IMAGEM FULLSCREEN */
    .image-modal {
      display: none;
      position: fixed;
      z-index: 9999;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      background-color: rgba(0, 0, 0, 0.9);
      justify-content: center;
      align-items: center;
      animation: fadeIn 0.3s ease;
    }

    .image-modal img {
      max-width: 95%;
      max-height: 85%;
      border-radius: 10px;
      animation: zoomIn 0.3s ease;
    }

    .close-btn {
      position: absolute;
      top: 20px;
      right: 25px;
      font-size: 35px;
      color: white;
      cursor: pointer;
      font-weight: bold;
      background: rgba(0, 0, 0, 0.3);
      border-radius: 50%;
      padding: 5px 15px;
    }

    @keyframes zoomIn {
      from {
        transform: scale(0.8);
        opacity: 0;
      }

      to {
        transform: scale(1);
        opacity: 1;
      }
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
      }

      to {
        opacity: 1;
      }
    }

    @keyframes slideUp {
      from {
        transform: translateY(20px);
        opacity: 0;
      }

      to {
        transform: translateY(0);
        opacity: 1;
      }
    }
  </style>
</head>

<body>
  <header>
    <div class="navbar-left">
      <img src="/pwa/assets/logo512px_new.png" alt="Logo">
      <h1>Bem-vindo, <?= htmlspecialchars($nome) ?> 👋</h1>
    </div>
    <button class="profile-btn" onclick="abrirModal()">Editar Perfil</button>
  </header>

  <main>
    <h2>🎯 Eventos Disponíveis</h2>

    <div class="carousel-container">
      <button class="carousel-btn prev" onclick="scrollCarousel(-1)">⟵</button>
      <div class="carousel" id="eventCarousel">
        <?php
        $sql = "SELECT id, nome, data, local, imagem, file FROM `eventos` WHERE status = 'Ativo'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        while ($evento = $stmt->fetch(PDO::FETCH_ASSOC)) {
          $data_formatada = 'N/D';
          if (!empty($evento['data'])) {
            try {
              $data_objeto = new DateTime($evento['data']);
              $data_formatada = $data_objeto->format('d/m/Y');
            } catch (Exception $e) {
              $data_formatada = 'Data Inválida';
            }
          }

          $imagemPath = '/pwa_painel/uploads/' . htmlspecialchars($evento['imagem']);
          $filePath = !empty($evento['file']) ? '/pwa_painel/uploads/' . htmlspecialchars($evento['file']) : null;
        ?>

          <div class="event-card">
            <img src="<?= $imagemPath ?>" alt="<?= htmlspecialchars($evento['nome']) ?>" onclick="openImageModal('<?= $imagemPath ?>')">

            <div class="event-info">
              <h3><?= htmlspecialchars($evento['nome']) ?></h3>
              <p>Data: <?= $data_formatada ?> - Local: <?= htmlspecialchars($evento['local'] ?? 'N/D') ?></p>

              <?php if ($filePath): ?>
                <p style="color: blue;">📄 <a href="<?= $filePath ?>" target="_blank" style="color:#343434;text-decoration:none;">Ver Regulamento</a></p>
              <?php endif; ?>
              <a href="/pwa/inscricao?id=<?= htmlspecialchars($evento['id']) ?>">
                <button>Inscrever-se</button>
              </a>

            </div>
          </div>

        <?php }
        $stmt->closeCursor(); ?>
      </div>
      <button class="carousel-btn next" onclick="scrollCarousel(1)">⟶</button>
    </div>
  </main>

  <footer>
    <button class="active"><i>🏠</i>Início</button>
    <button onclick="window.location='/pwa/meu_evento'">
      <i>🗓️</i> Meus Eventos
    </button>
    <button onclick="abrirModal()"><i>👤</i>Perfil</button>
  </footer>

  <!-- MODAL PERFIL -->
  <div class="modal" id="modalPerfil">
    <div class="modal-content">
      <h2>Editar Perfil</h2>

      <input type="hidden" id="id" value="<?= $lacador['id'] ?>">

      <label>Nome completo</label>
      <input type="text" class="form-control" id="nome"
        value="<?= $lacador['nome'] ?>">

      <label>WhatsApp</label>
      <input type="text" class="form-control" id="whatsapp"
        value="<?= $lacador['whatsapp'] ?>">

      <label>Apelido</label>
      <input type="text" class="form-control" id="apelido"
        value="<?= $lacador['apelido'] ?>">

      <label>Handicap Cabeça</label>
      <input type="text" class="form-control" id="handicap_cabeca"
        value="<?= $lacador['handicap_cabeca'] ?>">

      <label>Handicap Pé</label>
      <input type="text" class="form-control" id="handicap_pe"
        value="<?= $lacador['handicap_pe'] ?>">

      <br>

      <button class="btn btn-sm btn-success" onclick="salvarPerfil()">Salvar Alterações</button>
      <hr>
      <a class="btn btn-sm btn-danger" href="/pwa/login">Sair</a>
    </div>
  </div>


  <!-- MODAL IMAGEM FULLSCREEN -->
  <div id="imageModal" class="image-modal" onclick="closeImageModal()">
    <span class="close-btn" onclick="closeImageModal(event)">×</span>
    <img id="modalImage" src="" alt="Imagem do evento">
  </div>
  <script>
    function salvarPerfil() {
      let formData = new FormData();

      formData.append('id', document.getElementById('id').value);
      formData.append('nome', document.getElementById('nome').value);
      formData.append('whatsapp', document.getElementById('whatsapp').value);
      formData.append('apelido', document.getElementById('apelido').value);
      formData.append('handicap_cabeca', document.getElementById('handicap_cabeca').value);
      formData.append('handicap_pe', document.getElementById('handicap_pe').value);

      fetch('/pwa/app/Views/atualizar_perfil.php', {
          method: 'POST',
          body: formData
        })
        .then(r => r.text())
        .then(resp => {
          alert(resp);
          fecharModal();
        });
    }
  </script>


  <script>
    const modal = document.getElementById('modalPerfil');
    const carousel = document.getElementById('eventCarousel');



    function abrirModal() {
      modal.style.display = 'flex';
    }

    function fecharModal() {
      modal.style.display = 'none';
    }

    window.onclick = (e) => {
      if (e.target === modal) fecharModal();
    };

    function scrollCarousel(direction) {
      const scrollAmount = carousel.clientWidth * 0.8;
      carousel.scrollBy({
        left: scrollAmount * direction,
        behavior: 'smooth'
      });
    }

    // 🖼️ Modal de imagem fullscreen
    function openImageModal(src) {
      const modal = document.getElementById('imageModal');
      const modalImg = document.getElementById('modalImage');
      modalImg.src = src;
      modal.style.display = 'flex';
    }

    function closeImageModal(e) {
      if (e) e.stopPropagation();
      document.getElementById('imageModal').style.display = 'none';
    }
  </script>
</body>

</html>