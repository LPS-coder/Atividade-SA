
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

    if (($_SESSION["tipo_usuario"] ?? "") !== "Administrador") {
        header("Location: dashboard.php?erro=acesso_negado");
        exit;
    }
}