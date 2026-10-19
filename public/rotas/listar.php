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

// mensagens que chegam pela URL (?msg=...)
$mensagens = [
    'cadastrada' => ['success', 'Rota cadastrada com sucesso.'],
    'atualizada' => ['success', 'Rota atualizada com sucesso.'],
    'excluida' => ['success', 'Rota excluída com sucesso.'],
    'nao_encontrada' => ['warning', 'Rota não encontrada.'],
    'acesso_negado' => ['danger', 'Apenas o administrador pode cadastrar, editar ou excluir rotas.'],
    'erro' => ['danger', 'Não foi possível concluir a operação. Tente novamente.'],
];
$msg = $_GET['msg'] ?? '';
if (!is_string($msg)) {
    $msg = '';
}

$rotas = [];
$erro = '';
try {
    $resultado = $conn->query("SELECT r.id_rota, r.nome_rota, r.distancia_km, r.tempo_previsto_min, r.data_criacao, t.nome_trens
                               FROM rotas r
                               LEFT JOIN trens t ON t.id_trem = r.id_trem
                               ORDER BY r.id_rota DESC");
    $rotas = $resultado->fetch_all(MYSQLI_ASSOC);
} catch (mysqli_sql_exception $e) {
    $erro = 'Não foi possível carregar as rotas.';
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rotas - OMNIRAIL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../style.css" rel="stylesheet">
</head>
<body>
    <div id="conteudo-principal" class="p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 fw-bold mb-0">Rotas</h1>
            <?php if ($ehAdmin) { ?>
                <a href="CADASTRO.php" class="btn btn-danger fw-bold">Nova rota</a>
            <?php } ?>
        </div>

        <?php if (isset($mensagens[$msg])) { ?>
            <div class="alert alert-<?= $mensagens[$msg][0] ?>"><?= $mensagens[$msg][1] ?></div>
        <?php } ?>

        <?php if ($erro != '') { ?>
            <div class="alert alert-danger"><?= $erro ?></div>
        <?php } elseif (count($rotas) == 0) { ?>
            <div class="alert alert-info">Nenhuma rota cadastrada ainda.</div>
        <?php } else { ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Rota</th>
                            <th>Trem</th>
                            <th>Distância</th>
                            <th>Tempo previsto</th>
                            <th>Criada em</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rotas as $r) {
                            $horas = intdiv($r['tempo_previsto_min'], 60);
                            $minutos = $r['tempo_previsto_min'] % 60;
                        ?>
                            <tr>
                                <td class="fw-bold"><?= htmlspecialchars($r['nome_rota']) ?></td>
                                <td><?= htmlspecialchars($r['nome_trens'] ?? '-') ?></td>
                                <td><?= number_format($r['distancia_km'], 2, ',', '.') ?> km</td>
                                <td><?= $horas > 0 ? $horas . 'h ' : '' ?><?= $minutos ?>min</td>
                                <td><?= date('d/m/Y H:i', strtotime($r['data_criacao'])) ?></td>
                                <td class="text-end text-nowrap">
                                    <a href="DETALHES.php?id=<?= $r['id_rota'] ?>" class="btn btn-sm btn-outline-secondary">Detalhes</a>
                                    <?php if ($ehAdmin) { ?>
                                        <a href="EDITAR.php?id=<?= $r['id_rota'] ?>" class="btn btn-sm btn-outline-secondary">Editar</a>
                                        <form action="EXCLUIR.php" method="post" class="d-inline" onsubmit="return confirm('Deseja excluir esta rota?')">
                                            <input type="hidden" name="id_rota" value="<?= $r['id_rota'] ?>">
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