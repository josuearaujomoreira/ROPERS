<?php
require __DIR__ . '/../Config/database.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

print_r($_POST);
exit;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validação básica
    if (
        empty($_POST['id_evento']) ||
        empty($_POST['id_lacador']) ||
        empty($_POST['id_categoria']) ||
        empty($_POST['tipo'])
    ) {
        echo "
        <script>
            alert('Erro: dados incompletos enviados! Tente novamente.');
            window.location.href = '/pwa/painel-cliente';
        </script>";
        exit;
    }

    $id_evento    = $_POST['id_evento'];
    $id_lacador   = $_POST['id_lacador'];
    $id_categoria = $_POST['id_categoria'];
    $tipo         = $_POST['tipo'];

    // Converte valor BR → decimal SQL (50,00 → 50.00)
    $valor_br = $_POST['valor'] ?? "0,00";
    $valor = str_replace(['.', ','], ['', '.'], $valor_br);

    try {
        $stmt = $pdo->prepare("
            INSERT INTO inscricoes 
            (id_lacador, id_evento, id_categoria, valor, tipo, status)
            VALUES (?, ?, ?, ?, ?, 'Pendente')
        ");

        $salvo = $stmt->execute([
            $id_lacador,
            $id_evento,
            $id_categoria,
            $valor,
            $tipo
        ]);

        if ($salvo) {
            echo "
            <script>
                alert('Inscrição realizada com sucesso! Aguarde aprovação.');
                window.location.href = '/pwa/meu_evento';
            </script>";
        } else {
            echo "
            <script>
                alert('Erro ao salvar sua inscrição, tente novamente!');
                window.location.href = '/pwa/painel-cliente';
            </script>";
        }

    } catch (PDOException $e) {
        echo "
        <script>
            alert('Erro no banco de dados: " . addslashes($e->getMessage()) . "');
            window.location.href = '/pwa/painel-cliente';
        </script>";
    }

    exit;
}
?>
