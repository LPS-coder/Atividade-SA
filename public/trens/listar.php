<?php
session_start();
require_once '../../INFRA/conexao.php';

if (!isset($_SESSION['id'])) {
    header('Location: ../login.php');
    exit;
}

// administrador gerencia; os demais só visualizam
$admin = $_SESSION['cargo'] == 'administrador';

$mensagens = [
    'cadastrado' => 'Trem cadastrado com sucesso!',
    'editado' => 'Trem atualizado com sucesso!',
    'excluido' => 'Trem excluído com sucesso!',
    'vinculado' => 'Este trem tem sensores ou relatórios vinculados e não pode ser excluído.',
    'naoencontrado' => 'Trem não encontrado.',
    'semacesso' => 'Você não tem permissão para essa ação.',
    'erro' => 'Não foi possível concluir a operação.'
];
$msg = $_GET['msg'] ?? '';
$tipo = in_array($msg, ['cadastrado', 'editado', 'excluido']) ? 'success' : 'danger';

$trens = mysqli_query($conn, "SELECT t.id_trem, t.nome_trens, t.codigo, t.modelo, f.nome_funcionario
    FROM trens t
    JOIN funcionario f ON f.id_funcionario = t.id_funcionario
    ORDER BY t.nome_trens");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Trens</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 fw-bold mb-0">Trens</h1>
        <?php if ($admin) { ?>
            <a href="cadastro.php" class="btn btn-danger">Cadastrar trem</a>
        <?php } ?>
    </div>

    <?php if (isset($mensagens[$msg])) { ?>
        <div class="alert alert-<?= $tipo ?>"><?= $mensagens[$msg] ?></div>
    <?php } ?>

    <?php if (mysqli_num_rows($trens) == 0) { ?>
        <p class="text-muted">Nenhum trem cadastrado ainda.</p>
    <?php } else { ?>
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Código</th>
                        <th>Modelo</th>
                        <?php if ($admin) { ?><th>Responsável</th><?php } ?>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($t = mysqli_fetch_assoc($trens)) { ?>
                    <tr>
                        <td><?= htmlspecialchars($t['nome_trens']) ?></td>
                        <td><?= htmlspecialchars($t['codigo']) ?></td>
                        <td><?= htmlspecialchars($t['modelo']) ?></td>
                        <?php if ($admin) { ?>
                            <td><?= htmlspecialchars($t['nome_funcionario']) ?></td>
                        <?php } ?>
                        <td class="text-end">
                            <a href="ver.php?id=<?= $t['id_trem'] ?>" class="btn btn-sm btn-outline-secondary">Ver</a>
                            <?php if ($admin) { ?>
                                <a href="editar.php?id=<?= $t['id_trem'] ?>" class="btn btn-sm btn-outline-primary">Editar</a>
                                <form action="excluir.php" method="post" class="d-inline" onsubmit="return confirm('Deseja excluir este trem?')">
                                    <input type="hidden" name="id" value="<?= $t['id_trem'] ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Excluir</button>
                                </form>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } ?>
</div>
</body>
</html>