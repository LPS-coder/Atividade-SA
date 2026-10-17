<?php
session_start();
require_once '../../INFRA/conexao.php';

if (!isset($_SESSION['id'])) {
    header('Location: ../login.php');
    exit;
}

$admin = $_SESSION['cargo'] == 'administrador';

$id = (int) ($_GET['id'] ?? 0);

$stmt = mysqli_prepare($conn, "SELECT t.nome_trens, t.codigo, t.modelo, t.id_trem, f.nome_funcionario
    FROM trens t
    JOIN funcionario f ON f.id_funcionario = t.id_funcionario
    WHERE t.id_trem = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$trem = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$trem) {
    header('Location: listar.php?msg=naoencontrado');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Informações do trem</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h1 class="h4 fw-bold mb-3"><?= htmlspecialchars($trem['nome_trens']) ?></h1>
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Código</dt>
                        <dd class="col-sm-8"><?= htmlspecialchars($trem['codigo']) ?></dd>
                        <dt class="col-sm-4">Modelo</dt>
                        <dd class="col-sm-8"><?= htmlspecialchars($trem['modelo']) ?></dd>
                        <?php if ($admin) { ?>
                            <dt class="col-sm-4">Usuário responsável</dt>
                            <dd class="col-sm-8"><?= htmlspecialchars($trem['nome_funcionario']) ?></dd>
                        <?php } ?>
                    </dl>
                </div>
            </div>
            <div class="mt-3">
                <a href="listar.php" class="btn btn-outline-secondary">Voltar</a>
                <?php if ($admin) { ?>
                    <a href="editar.php?id=<?= $trem['id_trem'] ?>" class="btn btn-danger">Editar trem</a>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>