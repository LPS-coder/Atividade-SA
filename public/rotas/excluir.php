<?php 
session_start();
include '../INFRA/conexao.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Backend: só o adminstrador logado exclui rotas 

if (!isset($_SESSION['id'])) {
    header('Location: ../login.php');
    exit;
}
if (!isset($_SESSION['cargo']) || strtolower($_SESSION['cargo']) != 'administrador') {
    header('Location: LISTAR.php?msg=acesso_negado');
    exit;
}


// só exclui por formulário (POST), nunca por link ou URL digitada
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: LISTAR.php');
    exit;
}


$id = $_POST['id_rota'] ?? '';
if (!is_string($id) || !ctype_digit($id)) {
    header('Location: LISTAR.php?msg=nao_encontrada');
    exit;
}


try {
    $stmt = $conn->prepare("DELETE FROM rotas WHERE id_rota = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();


    if ($stmt->affected_rows == 0) {
        header('Location: LISTAR.php?msg=nao_encontrada');
    } else {
        header('Location: LISTAR.php?msg=excluida');
    }

    $stmt->close();
} catch (mysqli_sql_exception $e) {
    header('Location: LISTAR.php?msg=erro');
}
exit;