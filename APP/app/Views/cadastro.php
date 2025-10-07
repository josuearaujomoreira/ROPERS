<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastro - Laçador</title>
  <link rel="manifest" href="/pwa/manifest.json">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      font-family: 'Segoe UI', sans-serif;
      background: url("https://cavalus.com.br/wp-content/uploads/2018/06/LacoPe-Kito-01.jpg") no-repeat center center fixed;
      background-size: cover;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      padding: 20px;
      position: relative;
    }

    .overlay {
      position: absolute;
      inset: 0;
      background: rgba(4,4,4,0.55);
      z-index: 0;
    }

    .container {
      position: relative;
      z-index: 1;
      background: rgba(255,255,255,0.95);
      padding: 30px 25px;
      border-radius: 16px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.3);
      width: 100%;
      max-width: 450px;
      text-align: center;
    }

    .logo {
      width: 100px;
      max-width: 40%;
      margin-bottom: 20px;
    }

    h1 {
      color: #2c1b18;
      font-size: clamp(20px, 5vw, 26px);
      margin-bottom: 15px;
    }

    form {
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    input, select {
      width: 100%;
      padding: 12px;
      border: 1px solid #aaa6a6;
      border-radius: 10px;
      background: #f9f9f9;
      font-size: 15px;
      outline: none;
      transition: all 0.3s ease;
    }

    input:focus, select:focus {
      border-color: #f1693c;
      box-shadow: 0 0 6px rgba(241,105,60,0.5);
    }

    button {
      width: 100%;
      padding: 14px;
      border: none;
      border-radius: 10px;
      background: #f1693c;
      color: white;
      font-weight: bold;
      font-size: 16px;
      cursor: pointer;
      transition: background 0.3s;
    }

    button:hover {
      background: #d8572b;
    }

    .preview {
      display: flex;
      flex-direction: column;
      align-items: center;
      margin-bottom: 10px;
    }

    .preview img {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      object-fit: cover;
      margin-top: 10px;
      border: 2px solid #f1693c;
    }

    .link {
      margin-top: 18px;
      font-size: 14px;
      color: #3c3c3c;
    }

    .link a {
      color: #f1693c;
      font-weight: bold;
      text-decoration: none;
    }

    .mensagem {
      margin-bottom: 10px;
      color: red;
      font-weight: bold;
    }

    @media (max-width: 480px) {
      .container { padding: 20px 15px; }
      .logo { width: 80px; }
    }
  </style>
</head>
<body>
  <div class="overlay"></div>
  <div class="container">
    <img src="/pwa/assets/logo512px_transparente.png" alt="Logo" class="logo">
    <h1>Cadastro de Laçador</h1>

    <?php if (!empty($erro)): ?>
      <p class="mensagem"><?= $erro ?></p>
    <?php elseif (!empty($sucesso)): ?>
      <p class="mensagem" style="color:green;"><?= $sucesso ?></p>
    <?php endif; ?>

    <form method="POST" action="/pwa/cadastro" enctype="multipart/form-data">
      <div class="preview">
        <label>Foto do Laçador:</label>
        <input type="file" id="foto" name="foto" accept="image/*">
        <img id="previewImg" src="" alt="Prévia da Foto" style="display:none;">
      </div>

      <input type="text" name="nome" placeholder="Nome completo" required>
      <input type="text" name="apelido" placeholder="Apelido" required>
    <input type="text" id="cpf" name="cpf" placeholder="CPF" maxlength="14" required>
<p id="cpfErro" style="color:red; font-size:13px; display:none; margin-top:-5px;">CPF inválido</p>

      <input type="text" id="whatsapp" name="whatsapp" placeholder="Whatsapp" maxlength="15" required>
      <input type="text" name="cidade" placeholder="Cidade" required>
      <select name="uf" required>
        <option value="">UF</option>
        <?php
        $ufs = ['AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO'];
        foreach ($ufs as $uf) echo "<option value='$uf'>$uf</option>";
        ?>
      </select>

      <input type="text" name="handicap_cabeca" placeholder="Handicap Cabeça (ex: 4)">
      <input type="text" name="handicap_pe" placeholder="Handicap Pé (ex: 3)">
      <button type="submit">Cadastrar</button>
    </form>

    <p class="link">Já tem conta? <a href="/pwa/login">Entrar</a></p>
  </div>

  <script>
    // Preview da foto
    const fotoInput = document.getElementById('foto');
    const previewImg = document.getElementById('previewImg');
    fotoInput.addEventListener('change', function(e) {
      const file = e.target.files[0];
      if (file) {
        previewImg.src = URL.createObjectURL(file);
        previewImg.style.display = 'block';
      }
    });

    
    // Máscara WhatsApp
    const wppInput = document.getElementById('whatsapp');
    wppInput.addEventListener('input', () => {
      let v = wppInput.value.replace(/\D/g, '');
      if (v.length > 2 && v.length <= 7)
        v = v.replace(/(\d{2})(\d+)/, '($1) $2');
      else if (v.length > 7)
        v = v.replace(/(\d{2})(\d{5})(\d+)/, '($1) $2-$3');
      wppInput.value = v.slice(0, 15);
    });

  // Máscara CPF
const cpfInput = document.getElementById('cpf');
const cpfErro = document.getElementById('cpfErro');

cpfInput.addEventListener('input', () => {
  let v = cpfInput.value.replace(/\D/g, '');
  if (v.length > 3 && v.length <= 6)
    v = v.replace(/(\d{3})(\d+)/, '$1.$2');
  else if (v.length > 6 && v.length <= 9)
    v = v.replace(/(\d{3})(\d{3})(\d+)/, '$1.$2.$3');
  else if (v.length > 9)
    v = v.replace(/(\d{3})(\d{3})(\d{3})(\d+)/, '$1.$2.$3-$4');
  cpfInput.value = v.slice(0, 14);

  // Oculta mensagem de erro ao digitar
  cpfErro.style.display = 'none';
});

// Função de validação
function validarCPF(cpf) {
  cpf = cpf.replace(/[^\d]+/g, '');
  if (cpf.length !== 11 || /^(\d)\1+$/.test(cpf)) return false;
  let soma = 0, resto;
  for (let i = 1; i <= 9; i++) soma += parseInt(cpf.substring(i-1, i)) * (11 - i);
  resto = (soma * 10) % 11;
  if (resto === 10 || resto === 11) resto = 0;
  if (resto !== parseInt(cpf.substring(9, 10))) return false;
  soma = 0;
  for (let i = 1; i <= 10; i++) soma += parseInt(cpf.substring(i-1, i)) * (12 - i);
  resto = (soma * 10) % 11;
  if (resto === 10 || resto === 11) resto = 0;
  return resto === parseInt(cpf.substring(10, 11));
}

// Mostra erro apenas quando o campo perde o foco
cpfInput.addEventListener('blur', () => {
  if (cpfInput.value && !validarCPF(cpfInput.value)) {
    cpfErro.style.display = 'block';
  } else {
    cpfErro.style.display = 'none';
  }
});
  </script>
</body>
</html>