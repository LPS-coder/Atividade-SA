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
$idFuncionario = (int) $_SESSION["id_funcionario"];

if ($nome === "" || $codigo === "" || $modelo === "") {
    header("Location: ../cadastro_de_sensores_e_trens.php?erro=trem_vazio");
    exit;
}

$sql = "INSERT INTO trens (nome_trens, codigo, modelo, id_funcionario)
        VALUES (?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssi", $nome, $codigo, $modelo, $idFuncionario);

if ($stmt->execute()) {
    header("Location: ../cadastro_de_sensores_e_trens.php?sucesso=trem");
} elseif ($stmt->errno === 1062) {
    header("Location: ../cadastro_de_sensores_e_trens.php?erro=codigo_duplicado");
} else {
    header("Location: ../cadastro_de_sensores_e_trens.php?erro=cadastro_trem");
}

$stmt->close();
$conn->close();
exit;
