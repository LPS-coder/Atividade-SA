<?php
session_start();
require_once '../../INFRA/conexao.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// só administrador logado cadastra trem
if (!isset($_SESSION['id'])) {
    header('Location: ../login.php');
    exit;
}
if ($_SESSION['cargo'] != 'administrador') {
    header('Location: listar.php?msg=semacesso');
    exit;
}

// funcionários cadastrados, para escolher o responsável
$funcionarios = mysqli_query($conn, "SELECT id_funcionario, nome_funcionario FROM funcionario ORDER BY nome_funcionario");

$erro = '';
$nome = '';
$codigo = '';
$modelo = '';
$responsavel = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $codigo = trim($_POST['codigo'] ?? '');
    $modelo = trim($_POST['modelo'] ?? '');
    $responsavel = (int) ($_POST['responsavel'] ?? 0);

    if ($nome == '' || $codigo == '' || $modelo == '') {
        $erro = 'Preencha nome, código e modelo do trem.';
    } elseif (mb_strlen($nome) > 100 || mb_strlen($codigo) > 20 || mb_strlen($modelo) > 100) {
        $erro = 'Algum campo passou do tamanho máximo.';
    } elseif ($responsavel <= 0) {
        $erro = 'Selecione o usuário responsável pelo trem.';
    } else {
        try {
            $stmt = mysqli_prepare($conn, "INSERT INTO trens (nome_trens, codigo, modelo, id_funcionario) VALUES (?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "sssi", $nome, $codigo, $modelo, $responsavel);
            mysqli_stmt_execute($stmt);
            header('Location: listar.php?msg=cadastrado');
            exit;
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) {
                $erro = 'Já existe um trem com esse código.';
            } elseif ($e->getCode() == 1452) {
                $erro = 'Usuário responsável inválido.';
            } else {
                $erro = 'Erro ao salvar o trem. Tente novamente.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cadastrar trem</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1 class="h3 fw-bold mb-3">Cadastrar trem</h1>

            <?php if ($erro != '') { ?>
                <div class="alert alert-danger"><?= $erro ?></div>
            <?php } ?>

            <form method="post">
                <div class="mb-3">
                    <label for="nome" class="form-label fw-bold">Nome do trem</label>
                    <input type="text" class="form-control" id="nome" name="nome" maxlength="100" value="<?= htmlspecialchars($nome) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="codigo" class="form-label fw-bold">Código de identificação</label>
                    <input type="text" class="form-control" id="codigo" name="codigo" maxlength="20" value="<?= htmlspecialchars($codigo) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="modelo" class="form-label fw-bold">Modelo</label>
                    <input type="text" class="form-control" id="modelo" name="modelo" maxlength="100" value="<?= htmlspecialchars($modelo) ?>" required>
                </div>
                <div class="mb-4">
                    <label for="responsavel" class="form-label fw-bold">Usuário responsável</label>
                    <select class="form-select" id="responsavel" name="responsavel" required>
                        <option value="">Selecione um usuário</option>
                        <?php while ($f = mysqli_fetch_assoc($funcionarios)) { ?>
                            <option value="<?= $f['id_funcionario'] ?>" <?= $responsavel == $f['id_funcionario'] ? 'selected' : '' ?>><?= htmlspecialchars($f['nome_funcionario']) ?></option>
                        <?php } ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-danger">Cadastrar trem</button>
                <a href="listar.php" class="btn btn-outline-secondary">Voltar</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>