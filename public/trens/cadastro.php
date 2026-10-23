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
$idFuncionario = filter_var(
    $_SESSION["id_funcionario"] ?? null,
    FILTER_VALIDATE_INT
);

if ($nome === "" || $codigo === "" || $modelo === "") {
    header("Location: ../cadastro_de_sensores_e_trens.php?erro=trem_vazio");
    exit;
}

if (!$idFuncionario || $idFuncionario < 1) {
    die("Erro: o ID do funcionário não está disponível na sessão.");
}

$sql = "INSERT INTO trens
        (nome_trens, codigo, modelo, id_funcionario)
        VALUES (?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssi", $nome, $codigo, $modelo, $idFuncionario);

try {
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();

        header("Location: ../cadastro_de_sensores_e_trens.php?sucesso=trem");
        exit;
    }
} catch (mysqli_sql_exception $e) {
    die("Erro MySQL (" . $e->getCode() . "): " . $e->getMessage());
}

$stmt->close();
$conn->close();

die("Erro: não foi possível cadastrar o trem.");