<?php
require_once "../../infra/seguranca.php";
exigirLogin();

require_once "../../infra/conexao.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../cadastro_de_sensores_e_trens.php");
    exit;
}

$idSensor = filter_input(INPUT_POST, "id_sensor", FILTER_VALIDATE_INT);

if (!$idSensor || $idSensor < 1) {
    header("Location: ../cadastro_de_sensores_e_trens.php?erro=sensor_nao_encontrado");
    exit;
}

$stmt = $conn->prepare("DELETE FROM sensores WHERE id_sensor = ?");
$stmt->bind_param("i", $idSensor);

try {
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $stmt->close();
        $conn->close();

        header("Location: ../cadastro_de_sensores_e_trens.php?sucesso=sensor_excluido");
        exit;
    }

    $stmt->close();
    $conn->close();

    header("Location: ../cadastro_de_sensores_e_trens.php?erro=sensor_nao_encontrado");
    exit;
} catch (mysqli_sql_exception $e) {
    error_log($e->getMessage());

    $stmt->close();
    $conn->close();

    header("Location: ../cadastro_de_sensores_e_trens.php?erro=exclusao_sensor");
    exit;
}
