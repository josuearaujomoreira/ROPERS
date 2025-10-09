<?php
// painel-cliente.php
session_start();

// Exemplo: pega o nome do laçador logado (você pode ajustar depois)
$nome = $_SESSION['nome'] ?? 'Laçador';
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Painel do Laçador</title>
  <link rel="manifest" href="/pwa/manifest.json">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f8f8f8;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      overflow-x: hidden;
    }

    header {
      background: linear-gradient(135deg, #f1693c, #d35400);
      color: #fff;
      padding: 15px 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      position: sticky;
      top: 0;
      z-index: 10;
    }

    header h1 {
      font-size: 20px;
    }

    .profile-btn {
      background: rgba(255, 255, 255, 0.15);
      border: none;
      color: white;
      padding: 8px 12px;
      border-radius: 10px;
      cursor: pointer;
      transition: background 0.3s;
    }

    .profile-btn:hover {
      background: rgba(255, 255, 255, 0.3);
    }

    main {
      flex: 1;
      padding: 20px;
    }

    .carousel {
      display: flex;
      overflow-x: auto;
      gap: 15px;
      scroll-snap-type: x mandatory;
      scrollbar-width: none;
    }

    .carousel::-webkit-scrollbar {
      display: none;
    }

    .event-card {
      flex: 0 0 85%;
      background: white;
      border-radius: 16px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      overflow: hidden;
      scroll-snap-align: start;
      transition: transform 0.3s;
      position: relative;
    }

    .event-card:hover {
      transform: scale(1.03);
    }

    .event-card img {
      width: 100%;
      height: 180px;
      object-fit: cover;
    }

    .event-info {
      padding: 15px;
    }

    .event-info h3 {
      font-size: 18px;
      color: #333;
      margin-bottom: 5px;
    }

    .event-info p {
      font-size: 14px;
      color: #666;
    }

    .event-info button {
      margin-top: 10px;
      width: 100%;
      padding: 10px;
      background: #f1693c;
      color: #fff;
      border: none;
      border-radius: 8px;
      font-weight: bold;
      cursor: pointer;
      transition: background 0.3s;
    }

    .event-info button:hover {
      background: #d35400;
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
      color: #666;
      font-size: 14px;
      cursor: pointer;
      display: flex;
      flex-direction: column;
      align-items: center;
      transition: color 0.3s;
    }

    footer button.active,
    footer button:hover {
      color: #f1693c;
    }

    footer i {
      font-size: 20px;
      margin-bottom: 4px;
    }

    /* Modal Editar Dados */
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

    .modal-content h2 {
      margin-bottom: 10px;
      color: #333;
    }

    .modal-content input {
      width: 100%;
      padding: 10px;
      margin: 6px 0;
      border: 1px solid #ccc;
      border-radius: 8px;
    }

    .modal-content button {
      width: 100%;
      padding: 12px;
      margin-top: 10px;
      background: #f1693c;
      color: white;
      border: none;
      border-radius: 8px;
      font-weight: bold;
      cursor: pointer;
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
    <h1>Bem-vindo, <?= htmlspecialchars($nome) ?> 👋</h1>
    <button class="profile-btn" onclick="abrirModal()">Editar Perfil</button>
  </header>

  <main>
    <h2 style="margin-bottom: 10px;">🎯 Eventos Disponíveis</h2>

    <div class="carousel" id="eventCarousel">
      <!-- Exemplo de evento -->
      <div class="event-card">
        <img src="https://cavalus.com.br/wp-content/uploads/2018/06/LacoPe-Kito-01.jpg" alt="Evento 1">
        <div class="event-info">
          <h3>Laço do Sertão</h3>
          <p>Data: 15/10/2025 - Local: Fazenda Boa Vista</p>
          <button>Inscrever-se</button>
        </div>
      </div>

      <div class="event-card">
        <img src="https://i.ytimg.com/vi/R5W0U-2gDxU/maxresdefault.jpg" alt="Evento 2">
        <div class="event-info">
          <h3>Desafio dos Campeões</h3>
          <p>Data: 20/10/2025 - Local: Haras Estrela Dourada</p>
          <button>Inscrever-se</button>
        </div>
      </div>
    </div>
  </main>

  <footer>
    <button class="active"><i>🏠</i>Início</button>
    <button><i>🗓️</i>Meus Eventos</button>
    <button onclick="abrirModal()"><i>👤</i>Perfil</button>
  </footer>

  <!-- Modal Editar Dados -->
  <div class="modal" id="modalPerfil">
    <div class="modal-content">
      <h2>Editar Perfil</h2>
      <input type="text" placeholder="Nome completo">
      <input type="text" placeholder="WhatsApp">
      <input type="text" placeholder="Cidade">
      <input type="text" placeholder="UF">
      <button onclick="fecharModal()">Salvar Alterações</button>
    </div>
  </div>

  <script>
    const modal = document.getElementById('modalPerfil');

    function abrirModal() {
      modal.style.display = 'flex';
    }

    function fecharModal() {
      modal.style.display = 'none';
    }

    // Fecha modal clicando fora
    window.onclick = (e) => {
      if (e.target === modal) fecharModal();
    };
  </script>

</body>
</html>
