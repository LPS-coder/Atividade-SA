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

try {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $nome, $codigo, $modelo, $idFuncionario);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();

        header("Location: ../cadastro_de_sensores_e_trens.php?sucesso=trem");
        exit;
    }

} catch (mysqli_sql_exception $e) {

    if ($e->getCode() == 1062) {
        if (isset($stmt)) {
            $stmt->close();
        }

        $conn->close();

        header("Location: ../cadastro_de_sensores_e_trens.php?erro=codigo_duplicado");
        exit;
    }

    error_log("Erro ao cadastrar trem: " . $e->getMessage());

    if (isset($stmt)) {
        $stmt->close();
    }

    $conn->close();

    header("Location: ../cadastro_de_sensores_e_trens.php?erro=cadastro_trem");
    exit;
}

if (isset($stmt)) {
    $stmt->close();
}

$conn->close();

header("Location: ../cadastro_de_sensores_e_trens.php?erro=cadastro_trem");
exit;