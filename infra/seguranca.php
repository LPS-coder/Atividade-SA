<?php

function exigirLogin(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    if (!isset($_SESSION["id_funcionario"])) {
        header("Location: login.php");
        exit;
    }
}

function exigirAdministrador(): void
{
    exigirLogin();

    $tipo = strtoupper($_SESSION["tipo_usuario"] ?? "");

    if ($tipo !== "ADMINISTRADOR") {
        header("Location: dashboard.php?erro=acesso_negado");
        exit;
    }
}
?>