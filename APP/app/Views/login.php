<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="manifest" href="/pwa/manifest.json">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(180deg, #040404, #282828);
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      color: #f5f6f6;
      padding: 20px;
    }

    .container {
      background: #343434;
      padding: 35px 25px;
      border-radius: 16px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.5);
      width: 100%;
      max-width: 380px;
      text-align: center;
      transition: all 0.3s ease;
    }

    .container:hover {
      transform: scale(1.01);
    }

    .logo {
      width: 90px;
      max-width: 40%;
      margin-bottom: 20px;
      filter: drop-shadow(0 0 8px rgba(255,255,255,0.15));
      border-radius: 10px !important;
    }

    h3 {
      margin-bottom: 20px;
      color: #f5f6f6;
      font-size: clamp(20px, 5vw, 26px);
      font-weight: 600;
    }

    input {
      width: 100%;
      padding: 14px;
      margin: 10px 0;
      border: 1px solid #565656;
      border-radius: 10px;
      background: #282828;
      color: #f5f6f6;
      font-size: 15px;
      outline: none;
      transition: all 0.3s ease;
    }

    input::placeholder {
      color: #8c8c8c;
    }

    input:focus {
      border-color: #8c8c8c;
      box-shadow: 0 0 6px rgba(140, 140, 140, 0.4);
    }

    button {
      width: 100%;
      padding: 14px;
      margin-top: 15px;
      border: none;
      border-radius: 10px;
      background: #565656;
      color: #f5f6f6;
      font-weight: bold;
      font-size: 16px;
      cursor: pointer;
      transition: all 0.3s;
    }

    button:hover {
      background: #747474;
    }

    .link {
      margin-top: 20px;
      font-size: 14px;
      color: #8c8c8c;
    }

    .link a {
      color: #f5f6f6;
      font-weight: bold;
      text-decoration: none;
      transition: 0.3s;
    }

    .link a:hover {
      color: #8c8c8c;
    }

    .erro {
      background: rgba(255, 0, 0, 0.1);
      color: #ff6b6b;
      border: 1px solid #ff6b6b;
      padding: 10px;
      border-radius: 8px;
      margin-bottom: 15px;
      font-size: 14px;
      animation: fadeIn 0.4s ease;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-5px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 480px) {
      .container {
        padding: 25px 15px;
      }
      .logo {
        width: 80px;
      }
    }
  </style>
</head>

<body>
  <div class="container">
    <img src="/pwa/assets/logo512px_new.png" alt="Logo" class="logo">
    <h3>Login</h3>

    <?php if (isset($erro)): ?>
      <div class="erro"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <form method="POST" action="/pwa/login">
      <input type="text" name="cpf" id="cpf" placeholder="CPF (000.000.000-00)" maxlength="14" required>
      <input type="password" name="senha" placeholder="Senha" required>
      <button type="submit">Entrar</button>
    </form>

    <p class="link">Ainda não tem conta? <a href="/pwa/cadastro">Cadastre-se</a></p>
  </div>

  <script>
    const cpfInput = document.getElementById('cpf');

    cpfInput.addEventListener('input', function (e) {
      let value = e.target.value.replace(/\D/g, '');
      if (value.length > 11) value = value.slice(0, 11);

      if (value.length > 9) {
        value = value.replace(/^(\d{3})(\d{3})(\d{3})(\d{0,2})/, '$1.$2.$3-$4');
      } else if (value.length > 6) {
        value = value.replace(/^(\d{3})(\d{3})(\d{0,3})/, '$1.$2.$3');
      } else if (value.length > 3) {
        value = value.replace(/^(\d{3})(\d{0,3})/, '$1.$2');
      }

      e.target.value = value;
    });
  </script>
</body>
</html>
