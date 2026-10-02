<?php
session_start();
require_once '../INFRA/conexao.php'; 

if (!isset($_SESSION['id_funcionario'])) {
    header('Location: login.php');
    exit;
}

// Só aceita POST (proteção contra exclusão por link/URL)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location:listar-usuario.php');
    exit;
}

$token = $_POST['token'] ?? '';
if (!isset($_SESSION['token']) || !hash_equals($_SESSION['token'], $token)) {
    header('Location:listar-usuario.php?erro=token');
    exit;
}

// Validação da identificação recebida
$id_usuario = filter_var($_POST['id_usuario'] ?? '', FILTER_VALIDATE_INT);
$etapa = (int) ($_POST['etapa'] ?? 1);

if ($id_usuario === false || $id_usuario <= 0) {
    header('Location:listar-usuario.php?erro=id');
    exit;
}

// Confirma se o usuário existe
$stmt = $conexao->prepare("SELECT nome_usuario FROM usuario WHERE id_usuario = ?");
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();
$usuario = $resultado->fetch_assoc();
$stmt->close();

if (!$usuario) {
    header('Location:listar-usuario.php?erro=inexistente');
    exit;
}


if ($etapa === 3) {
    try {
        $stmt = $conexao->prepare("DELETE FROM usuario WHERE id_usuario = ?");
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $apagou = $stmt->affected_rows;
        $stmt->close();

        if ($apagou > 0) {
            header('Location:listar-usuario.php?msg=excluido');
        } else {
            header('Location:listar-usuario.php?erro=falha');
        }
    } catch (mysqli_sql_exception $e) {
        header('Location:listar-usuario.php?erro=falha');
    }
    exit;
}

$nome = htmlspecialchars($usuario['nome_usuario']);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Excluir usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="card mx-auto shadow-sm text-center" style="max-width: 480px;">
            <div class="card-body p-4">

                <?php if ($etapa === 1) { ?>

                    <h4 class="mb-3">Deseja deletar esse usuário?</h4>
                    <p class="mb-4"><strong><?php echo $nome; ?></strong></p>

                    <form method="post" action="excluir_usuario.php" class="d-flex justify-content-center gap-2">
                        <input type="hidden" name="id_usuario" value="<?php echo $id_usuario; ?>">
                        <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
                        <input type="hidden" name="etapa" value="2">
                        <button type="submit" class="btn btn-warning">Sim, deletar</button>
                        <a href="listar_usuarios.php" class="btn btn-secondary">Cancelar</a>
                    </form>

                <?php } else { ?>

                    <h4 class="mb-3 text-danger">DESEJA MESMO DELETAR ESSE USUÁRIO?</h4>
                    <p class="mb-1"><strong><?php echo $nome; ?></strong></p>
                    <p class="text-muted mb-4">Todos os dados dele serão apagados e isso não pode ser desfeito.</p>

                    <form method="post" action="excluir_usuario.php" class="d-flex justify-content-center gap-2">
                        <input type="hidden" name="id_usuario" value="<?php echo $id_usuario; ?>">
                        <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
                        <input type="hidden" name="etapa" value="3">
                        <button type="submit" class="btn btn-danger">Deletar</button>
                        <a href="listar_usuarios.php" class="btn btn-secondary">Cancelar</a>
                    </form>

                <?php } ?>

            </div>
        </div>
    </div>
</body>
</html>