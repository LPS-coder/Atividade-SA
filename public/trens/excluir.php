<?php

require_once __DIR__ . "/../../infra/seguranca.php";
exigirLogin();

require_once __DIR__ . "/../../infra/conexao.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../cadastro_de_sensores_e_trens.php");
    exit;
}

$id = filter_input(INPUT_POST, "id_trem", FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    header("Location: ../cadastro_de_sensores_e_trens.php?erro=trem_nao_encontrado");
    exit;
}

try {
    $sql = "DELETE FROM trens WHERE id_trem = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $stmt->close();
        $conn->close();
        header("Location: ../cadastro_de_sensores_e_trens.php?sucesso=trem_excluido");
        exit;
    }

    $stmt->close();
    $conn->close();
    header("Location: ../cadastro_de_sensores_e_trens.php?erro=trem_nao_encontrado");
    exit;

} catch (mysqli_sql_exception $e) {
    $conn->close();
    header("Location: ../cadastro_de_sensores_e_trens.php?erro=exclusao_trem");
    exit;
}
