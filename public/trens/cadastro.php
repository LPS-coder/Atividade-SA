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
$idRota = filter_input(INPUT_POST, "id_rota", FILTER_VALIDATE_INT);
$idFuncionario = filter_var($_SESSION["id_funcionario"] ?? null, FILTER_VALIDATE_INT);

if ($nome === "" || $codigo === "" || $modelo === "" || !$idRota || $idRota < 1) {
    header("Location: ../cadastro_de_sensores_e_trens.php?erro=trem_vazio");
    exit;
}

if (!$idFuncionario || $idFuncionario < 1) {
    die("Erro: o ID do funcionário não está disponível na sessão.");
}

try {
    $stmt = $conn->prepare("
        INSERT INTO trens (nome_trens, codigo, modelo, id_funcionario, id_rota)
        VALUES (?, ?, ?, ?, ?)
    ");

    $stmt->bind_param("sssii", $nome, $codigo, $modelo, $idFuncionario, $idRota);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location: ../cadastro_de_sensores_e_trens.php?sucesso=trem");
        exit;
    }

} catch (mysqli_sql_exception $e) {
    if (isset($stmt)) {
        $stmt->close();
    }

    error_log("Erro ao cadastrar trem: " . $e->getMessage());
    $conn->close();

    if ($e->getCode() == 1062) {
        header("Location: ../cadastro_de_sensores_e_trens.php?erro=codigo_duplicado");
        exit;
    }

    header("Location: ../cadastro_de_sensores_e_trens.php?erro=cadastro_trem");
    exit;
}

if (isset($stmt)) {
    $stmt->close();
}

$conn->close();
header("Location: ../cadastro_de_sensores_e_trens.php?erro=cadastro_trem");
exit;
