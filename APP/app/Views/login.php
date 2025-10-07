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
            background: url("https://cavalus.com.br/wp-content/uploads/2018/06/LacoPe-Kito-01.jpg") no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(4, 4, 4, 0.55);
            z-index: 0;
        }

        .container {
            position: relative;
            z-index: 1;
            background: rgba(255, 255, 255, 0.92);
            padding: 30px 25px;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 380px;
            text-align: center;
        }

        .logo {
            width: 100px;
            max-width: 40%;
            margin-bottom: 15px;
        }

        h1 {
            margin: 10px 0 20px;
            color: #2c1b18;
            font-size: clamp(20px, 5vw, 26px);
        }

        input {
            width: 100%;
            padding: 14px;
            margin: 10px 0;
            border: 1px solid #aaa6a6;
            border-radius: 10px;
            background: #f9f9f9;
            font-size: 15px;
            outline: none;
            transition: all 0.3s ease;
        }

        input:focus {
            border-color: #f1693c;
            box-shadow: 0 0 6px rgba(241, 105, 60, 0.5);
        }

        button {
            width: 100%;
            padding: 14px;
            margin-top: 12px;
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

        @media (max-width: 480px) {
            .container {
                padding: 20px 15px;
            }

            .logo {
                width: 80px;
            }
        }
    </style>
</head>

<body>
    <div class="overlay"></div>

    <div class="container">


        <img src="/pwa/assets/logo512px_transparente.png" style="margin-bottom: -8px;" alt="Logo" class="logo">

        <h3>Login</h3>
        <form method="POST" action="/pwa/login">
            <input type="text" name="usuario" placeholder="Usuário" required>
            <input type="password" name="senha" placeholder="Senha" required>
            <button type="submit">Entrar</button>
        </form>
        <p class="link">Ainda não tem conta? <a href="/pwa/cadastro">Cadastre-se</a></p>
    </div>
</body>

</html>