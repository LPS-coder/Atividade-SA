<?php
require_once "../../infra/seguranca.php";
exigirLogin();

require_once "../../infra/conexao.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../cadastro_de_sensores_e_trens.php");
    exit;
}

$nome = trim($_POST["nome_sensores"] ?? "");
$localizacao = trim($_POST["localizacao"] ?? "");
$tipo = trim($_POST["tipo_dado"] ?? "");
$idTrem = filter_var($_POST["id_trem"] ?? null, FILTER_VALIDATE_INT);

if (
    $nome === "" ||
    $localizacao === "" ||
    $tipo === "" ||
    !$idTrem ||
    $idTrem < 1 ||
    mb_strlen($nome) > 100 ||
    mb_strlen($localizacao) > 150 ||
    mb_strlen($tipo) > 50
) {
    header("Location: ../cadastro_de_sensores_e_trens.php?erro=sensor_vazio");
    exit;
}

$verificar = $conn->prepare(
    "SELECT id_trem FROM trens WHERE id_trem = ?"
);
$verificar->bind_param("i", $idTrem);
$verificar->execute();
$resultado = $verificar->get_result();

if ($resultado->num_rows === 0) {
    $verificar->close();
    header("Location: ../cadastro_de_sensores_e_trens.php?erro=trem_invalido");
    exit;
}

$verificar->close();

$stmt = $conn->prepare(
    "INSERT INTO sensores (nome_sensores, localizacao, tipo_dado, id_trem)
     VALUES (?, ?, ?, ?)"
);

$stmt->bind_param("sssi", $nome, $localizacao, $tipo, $idTrem);

try {
    $stmt->execute();
    $stmt->close();
    $conn->close();

    header("Location: ../cadastro_de_sensores_e_trens.php?sucesso=sensor");
    exit;
} catch (mysqli_sql_exception $e) {
    error_log($e->getMessage());

    $stmt->close();
    $conn->close();

    header("Location: ../cadastro_de_sensores_e_trens.php?erro=cadastro_sensor");
    exit;
}
