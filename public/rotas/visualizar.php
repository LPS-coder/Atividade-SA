<?php
session_start();
include '../INFRA/conexao.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Backend: precisa estar logado (admin e funcionário podem visualizar)
if (!isset($_SESSION['id'])) {
    header('Location: ../login.php');
    exit;
}
$ehAdmin = isset($_SESSION['cargo']) && strtolower($_SESSION['cargo']) == 'administrador';

$id = $_GET['id'] ?? '';
if (!is_string($id) || !ctype_digit($id)) {
    header('Location: LISTAR.php?msg=nao_encontrada');
    exit;
}

try {
    $stmt = $conn->prepare("SELECT r.nome_rota, r.descricao_rota, r.distancia_km, r.tempo_previsto_min, r.data_criacao, t.nome_trens
                            FROM rotas r
                            LEFT JOIN trens t ON t.id_trem = r.id_trem
                            WHERE r.id_rota = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $rota = $stmt->get_result()->fetch_assoc();
    $stmt->close();
} catch (mysqli_sql_exception $e) {
    header('Location: LISTAR.php?msg=erro');
    exit;
}

if (!$rota) {
    header('Location: LISTAR.php?msg=nao_encontrada');
    exit;
}

$horas = intdiv($rota['tempo_previsto_min'], 60);
$minutos = $rota['tempo_previsto_min'] % 60;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detalhes da rota - OMNIRAIL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../style.css" rel="stylesheet">
</head>
<body>
    <div id="conteudo-principal" class="p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 fw-bold mb-0">Detalhes da rota</h1>
            <a href="LISTAR.php" class="btn btn-outline-secondary">Voltar para as rotas</a>
        </div>

        <div class="card">
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-3">Nome da rota</dt>
                    <dd class="col-sm-9"><?= htmlspecialchars($rota['nome_rota']) ?></dd>

                    <dt class="col-sm-3">Descrição</dt>
                    <dd class="col-sm-9"><?= nl2br(htmlspecialchars($rota['descricao_rota'])) ?></dd>

                    <dt class="col-sm-3">Trem vinculado</dt>
                    <dd class="col-sm-9"><?= htmlspecialchars($rota['nome_trens'] ?? '-') ?></dd>

                    <dt class="col-sm-3">Distância</dt>
                    <dd class="col-sm-9"><?= number_format($rota['distancia_km'], 2, ',', '.') ?> km</dd>

                    <dt class="col-sm-3">Tempo previsto</dt>
                    <dd class="col-sm-9"><?= $horas > 0 ? $horas . 'h ' : '' ?><?= $minutos ?>min</dd>

                    <dt class="col-sm-3">Criada em</dt>
                    <dd class="col-sm-9 mb-0"><?= date('d/m/Y H:i', strtotime($rota['data_criacao'])) ?></dd>
                </dl>
            </div>
        </div>

        <?php if ($ehAdmin) { ?>
            <div class="d-flex gap-2 mt-4">
                <a href="EDITAR.php?id=<?= $id ?>" class="btn btn-danger fw-bold">Editar rota</a>
                <form action="EXCLUIR.php" method="post" onsubmit="return confirm('Deseja excluir esta rota?')">
                    <input type="hidden" name="id_rota" value="<?= $id ?>">
                    <button type="submit" class="btn btn-outline-danger">Excluir rota</button>
                </form>
            </div>
        <?php } ?>
    </div>
</body>
</html>