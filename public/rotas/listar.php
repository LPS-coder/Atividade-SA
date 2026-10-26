```php
<?php
require_once "../../infra/conexao.php";
require_once "../../infra/seguranca.php";

exigirAdministrador();

$sql = "SELECT r.id_rota, r.nome_rota, r.descricao_rota, r.distancia_km, r.tempo_previsto_min, r.data_criacao, t.nome_trens, t.codigo
        FROM rotas r
        INNER JOIN trens t ON r.id_trem = t.id_trem
        ORDER BY r.id_rota DESC";

$resultado = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rotas Cadastradas | OMNIRAIL</title>
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
        <li><a href="../relatorios.php" class="nav-link text-white"><i class="bi bi-file-earmark-text me-2"></i>Relatórios</a></li>
        <li><a href="../cadastro_user.php" class="nav-link text-white"><i class="bi bi-people me-2"></i>Cadastrados</a></li>
        <li><a href="../rotas.php" class="nav-link active"><i class="bi bi-globe-americas me-2"></i>Rotas</a></li>
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
            <h1 class="fw-bold mb-1">Rotas Cadastradas</h1>
            <p class="text-secondary mb-0">Consulte e gerencie as rotas ferroviárias.</p>
        </div>
        <a href="cadastro.php" class="btn btn-danger"><i class="bi bi-plus-lg me-1"></i>Nova Rota</a>
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
                            <th>Nome da rota</th>
                            <th>Distância</th>
                            <th>Tempo previsto</th>
                            <th>Trem associado</th>
                            <th>Data de criação</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($resultado && $resultado->num_rows > 0): ?>
                            <?php while ($rota = $resultado->fetch_assoc()): ?>
                                <tr>
                                    <td class="ps-4"><?= (int) $rota["id_rota"] ?></td>
                                    <td>
                                        <span class="fw-semibold"><?= htmlspecialchars($rota["nome_rota"]) ?></span>
                                    </td>
                                    <td><?= number_format((float) $rota["distancia_km"], 2, ",", ".") ?> km</td>
                                    <td><?= (int) $rota["tempo_previsto_min"] ?> min</td>
                                    <td>
                                        <?= htmlspecialchars($rota["nome_trens"]) ?>
                                        <span class="text-muted small d-block"><?= htmlspecialchars($rota["codigo"]) ?></span>
                                    </td>
                                    <td><?= date("d/m/Y H:i", strtotime($rota["data_criacao"])) ?></td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="visualizar.php?id=<?= (int) $rota["id_rota"] ?>" class="btn btn-sm btn-outline-secondary" title="Visualizar"><i class="bi bi-eye"></i></a>
                                            <a href="editar.php?id=<?= (int) $rota["id_rota"] ?>" class="btn btn-sm btn-outline-primary" title="Editar"><i class="bi bi-pencil"></i></a>
                                            
                                            <form action="excluir.php" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir esta rota?')">
                                                <input type="hidden" name="id" value="<?= (int) $rota["id_rota"] ?>">
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
                                <td colspan="7" class="text-center py-5">
                                    <i class="bi bi-signpost-2 text-muted fs-1 d-block mb-2"></i>
                                    <h5>Nenhuma rota cadastrada</h5>
                                    <p class="text-muted mb-3">Cadastre uma rota para começar.</p>
                                    <a href="cadastro.php" class="btn btn-danger">Cadastrar rota</a>
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
