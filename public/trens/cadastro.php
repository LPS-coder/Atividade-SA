<?php

require_once "../../infra/seguranca.php";
exigirLogin();

require_once "../../infra/conexao.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../cadastro_de_sensores_e_trens.php");
    exit;
}

$nome = trim($_POST["nome_trens"] ?? "");
$codigo = trim($_POST["codigo"] ?? "");
$modelo = trim($_POST["modelo"] ?? "");

if ($nome === "" || $codigo === "" || $modelo === "") {
    header("Location: ../cadastro_de_sensores_e_trens.php?erro=trem_vazio");
    exit;
}

try {
    $sql = "INSERT INTO trens (nome_trens, codigo, modelo)
            VALUES (?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $nome, $codigo, $modelo);
    $stmt->execute();

    $stmt->close();
    $conn->close();

    header("Location: ../cadastro_de_sensores_e_trens.php?sucesso=trem");
    exit;

} catch (mysqli_sql_exception $e) {

    if ($e->getCode() === 1062) {
        header("Location: ../cadastro_de_sensores_e_trens.php?erro=codigo_duplicado");
        exit;
    }

    error_log("Erro ao cadastrar trem: " . $e->getMessage());

    header("Location: ../cadastro_de_sensores_e_trens.php?erro=cadastro_trem");
    exit;
}
