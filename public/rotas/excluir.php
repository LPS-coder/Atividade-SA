<?php
require_once "../../infra/conexao.php";
require_once "../../infra/seguranca.php";

exigirAdministrador();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: listar.php");
    exit;
}

$id_rota = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

if (!$id_rota || $id_rota < 1) {
    header("Location: listar.php?erro=" . urlencode("ID da rota inválido."));
    exit;
}

$stmt = $conn->prepare("DELETE FROM rotas WHERE id_rota = ?");
$stmt->bind_param("i", $id_rota);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        $mensagem = "Rota excluída com sucesso.";
        $destino = "sucesso";
    } else {
        $mensagem = "Rota não encontrada.";
        $destino = "erro";
    }
} else {
    $mensagem = "Não foi possível excluir a rota. Verifique se ela está vinculada a outros registros.";
    $destino = "erro";
}

$stmt->close();

header("Location: listar.php?" . $destino . "=" . urlencode($mensagem));
exit;
