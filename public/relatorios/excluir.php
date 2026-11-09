<?php
require_once "../../infra/seguranca.php";
exigirLogin();
require_once "../../infra/conexao.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: listar.php");
    exit;
}

$idRelatorio = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

if (!$idRelatorio) {
    header("Location: listar.php?erro=id_invalido");
    exit;
}

$stmt = $conn->prepare("DELETE FROM relatorios WHERE id_relatorio = ?");
$stmt->bind_param("i", $idRelatorio);

if ($stmt->execute() && $stmt->affected_rows > 0) {
    $stmt->close();
    header("Location: listar.php?sucesso=relatorio_excluido");
    exit;
}

$stmt->close();
header("Location: listar.php?erro=exclusao_falhou");
exit;
?>
