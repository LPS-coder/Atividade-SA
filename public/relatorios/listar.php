<?php
require_once "../../infra/seguranca.php";
exigirLogin();
require_once "../../infra/conexao.php";

$sql = "
    SELECT
        r.id_relatorio,
        r.descricao,
        r.data_geracao,
        f.nome_funcionario,
        s.nome_sensores,
        t.nome_trens,
        t.codigo
    FROM relatorios r
    INNER JOIN funcionario f ON r.id_funcionario = f.id_funcionario
    INNER JOIN sensores s ON r.id_sensor = s.id_sensor
    INNER JOIN trens t ON r.id_trem = t.id_trem
    ORDER BY r.data_geracao DESC
";

$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios Cadastrados | OMNIRAIL</title>
    <link rel="stylesheet" href="../../assets/styles/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body id="body-dashboard">

    <div id="sidebar" class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark">
        <a href="../dashboard.php" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
            <img src="../../assets/images/logo.png" alt="Logo" width="40" height="40" class="me-2">
            <span class="fs-4">OMNIRAIL</span>
        </a>

        <hr>

        <ul class="nav nav-pills flex-column mb-auto">
            <li><a href="../dashboard.php" class="nav-link text-white"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
            <li><a href="../cadastro_de_sensores_e_trens.php" class="nav-link text-white"><i class="bi bi-broadcast me-2"></i>Sensores & Trens</a></li>
            <li><a href="../monitoramento.php" class="nav-link text-white"><i class="bi bi-activity me-2"></i>Monitoramento</a></li>
            <li><a href="../relatorios.php" class="nav-link active" aria-current="page"><i class="bi bi-file-earmark-text me-2"></i>Relatórios</a></li>
            <li><a href="../cadastro_user.php" class="nav-link text-white"><i class="bi bi-people me-2"></i>Cadastrados</a></li>
            <li><a href="../rotas.php" class="nav-link text-white"><i class="bi bi-globe-americas me-2"></i>Rotas</a></li>
        </ul>

        <hr>

        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="../../assets/images/User.png" alt="Usuário" class="rounded-circle me-2" width="32" height="32">
                <strong><?= htmlspecialchars($_SESSION["nome_funcionario"] ?? "Usuário") ?></strong>
            </a>
            <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
                <li><a class="dropdown-item" href="../logout.php">Sair</a></li>
            </ul>
        </div>
    </div>

    <div class="main">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <p class="text-secondary mb-0">Consulte e gerencie os registros operacionais.</p>
                <h1 class="fw-bold mb-0">Relatórios Cadastrados</h1>
            </div>
            <a href="cadastro.php" class="btn btn-danger">
                <i class="bi bi-plus-lg me-1"></i>Novo Relatório
            </a>
        </div>

        <?php if (isset($_GET["sucesso"])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_GET["sucesso"]) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET["erro"])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_GET["erro"]) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">ID</th>
                                <th>Sensor</th>
                                <th>Trem</th>
                                <th>Funcionário</th>
                                <th>Data de geração</th>
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($resultado && $resultado->num_rows > 0): ?>
                                <?php while ($relatorio = $resultado->fetch_assoc()): ?>
                                    <tr>
                                        <td class="ps-4"><?= (int) $relatorio["id_relatorio"] ?></td>
                                        <td><?= htmlspecialchars($relatorio["nome_sensores"]) ?></td>
                                        <td>
                                            <?= htmlspecialchars($relatorio["nome_trens"]) ?>
                                            <small class="text-secondary d-block"><?= htmlspecialchars($relatorio["codigo"]) ?></small>
                                        </td>
                                        <td><?= htmlspecialchars($relatorio["nome_funcionario"]) ?></td>
                                        <td><?= date("d/m/Y H:i", strtotime($relatorio["data_geracao"])) ?></td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-1">
                                                <a href="visualizar.php?id=<?= (int) $relatorio["id_relatorio"] ?>" class="btn btn-sm btn-outline-secondary" title="Visualizar">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="editar.php?id=<?= (int) $relatorio["id_relatorio"] ?>" class="btn btn-sm btn-outline-primary" title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="excluir.php" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este relatório?')">
                                                    <input type="hidden" name="id" value="<?= (int) $relatorio["id_relatorio"] ?>">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Excluir">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <i class="bi bi-file-earmark-text text-secondary display-4 d-block mb-2"></i>
                                        <h5 class="fw-bold">Nenhum relatório cadastrado</h5>
                                        <p class="text-secondary mb-3">Cadastre seu primeiro relatório operacional.</p>
                                        <a href="cadastro.php" class="btn btn-danger">
                                            <i class="bi bi-plus-lg me-1"></i>Cadastrar relatório
                                        </a>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>