<?php
session_start();
require_once '../../INFRA/conexao.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// só administrador logado exclui trem
if (!isset($_SESSION['id'])) {
    header('Location: ../login.php');
    exit;
}
if ($_SESSION['cargo'] != 'administrador') {
    header('Location: listar.php?msg=semacesso');
    exit;
}

// a exclusão só vale vindo do botão da listagem (POST), nunca por link direto
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: listar.php');
    exit;
}

$id = (int) ($_POST['id'] ?? 0);

try {
    $stmt = mysqli_prepare($conn, "DELETE FROM trens WHERE id_trem = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $msg = mysqli_stmt_affected_rows($stmt) > 0 ? 'excluido' : 'naoencontrado';
} catch (mysqli_sql_exception $e) {
    // 1451 = trem referenciado por sensores ou relatórios
    $msg = $e->getCode() == 1451 ? 'vinculado' : 'erro';
}

header('Location: listar.php?msg=' . $msg);
exit;