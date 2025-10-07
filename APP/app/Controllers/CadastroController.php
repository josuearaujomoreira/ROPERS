<?php
require __DIR__ . '/../Config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $apelido = $_POST['apelido'] ?? '';
    $cpf = $_POST['cpf'] ?? '';
    $whatsapp = $_POST['whatsapp'] ?? '';
    $cidade = $_POST['cidade'] ?? '';
    $uf = $_POST['uf'] ?? '';
    $handicap_cabeca = $_POST['handicap_cabeca'] ?? 0;
    $handicap_pe = $_POST['handicap_pe'] ?? 0;

    // Upload da foto
    $fotoNome = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $fotoNome = uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['foto']['tmp_name'], __DIR__ . "/../uploads/$fotoNome");
    }

    // Validação simples
    if (
        empty($nome) || empty($apelido) || empty($cpf) ||
        empty($whatsapp) || empty($cidade) || empty($uf)
    ) {
        $erro = "Por favor, preencha todos os campos obrigatórios.";
    } elseif (!preg_match('/^\d{3}\.\d{3}\.\d{3}\-\d{2}$/', $cpf)) {
        $erro = "CPF inválido.";
    } else {
        // Salvar no banco
        $stmt = $pdo->prepare("
            INSERT INTO lacadores (nome, apelido, cpf, whatsapp, cidade, uf, handicap_cabeca, handicap_pe, foto)
            VALUES (:nome, :apelido, :cpf, :whatsapp, :cidade, :uf, :handicap_cabeca, :handicap_pe, :foto)
        ");

        try {
            $stmt->execute([
                ':nome' => $nome,
                ':apelido' => $apelido,
                ':cpf' => $cpf,
                ':whatsapp' => $whatsapp,
                ':cidade' => $cidade,
                ':uf' => $uf,
                ':handicap_cabeca' => $handicap_cabeca,
                ':handicap_pe' => $handicap_pe,
                ':foto' => $fotoNome
            ]);

            // Redireciona para o painel do cliente (mesmo que a view ainda não exista)
            header('Location: /pwa/painel-cliente?id=' . $pdo->lastInsertId());
            exit;

        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $erro = "CPF já cadastrado.";
            } else {
                $erro = "Erro ao cadastrar: " . $e->getMessage();
            }
        }
    }
}

// Carrega a view do formulário
require __DIR__ . '/../Views/cadastro.php';
