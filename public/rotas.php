<?php
require_once "../infra/conexao.php";
require_once "../infra/seguranca.php";

exigirAdministrador();

$sql = "SELECT COUNT(*) AS total_rotas,
               COALESCE(SUM(distancia_km), 0) AS distancia_total,
               COALESCE(AVG(tempo_previsto_min), 0) AS tempo_medio
        FROM rotas";

$resultado = $conn->query($sql);
$estatisticas = $resultado->fetch_assoc();

$sqlRecentes = "SELECT r.id_rota, r.nome_rota, r.distancia_km, r.tempo_previsto_min, t.nome_trens, t.codigo
                FROM rotas r
                INNER JOIN trens t ON r.id_trem = t.id_trem
                ORDER BY r.id_rota DESC
                LIMIT 5";

$rotasRecentes = $conn->query($sqlRecentes);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rotas | OMNIRAIL</title>
    <link rel="stylesheet" href="../assets/styles/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body id="body-dashboard">
<div id="sidebar" class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark">
    <a href="dashboard.php" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        <img src="../assets/images/logo.png" alt="Logo" width="40" height="40" class="me-2">
        <span class="fs-4">OMNIRAIL</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <li><a href="dashboard.php" class="nav-link text-white"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
        <li><a href="cadastro_de_sensores_e_trens.php" class="nav-link text-white"><i class="bi bi-broadcast me-2"></i>Sensores & Trens</a></li>
        <li><a href="monitoramento.php" class="nav-link text-white"><i class="bi bi-activity me-2"></i>Monitoramento</a></li>
        <li><a href="relatorios.php" class="nav-link text-white"><i class="bi bi-file-earmark-text me-2"></i>Relatórios</a></li>
        <li><a href="cadastro_user.php" class="nav-link text-white"><i class="bi bi-people me-2"></i>Cadastrados</a></li>
        <li><a href="rotas.php" class="nav-link active"><i class="bi bi-globe-americas me-2"></i>Rotas</a></li>
    </ul>
    <hr>
    <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="../assets/images/User.png" alt="Usuário" class="rounded-circle me-2" width="32" height="32">
            <strong><?= htmlspecialchars($_SESSION["nome_funcionario"] ?? "Usuário") ?></strong>
        </a>
        <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
            <li><a class="dropdown-item" href="logout.php">Sair</a></li>
        </ul>
    </div>
</div>

<div class="main">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="fw-bold mb-1">Rotas Ferroviárias</h1>
            <p class="text-secondary mb-0">Gerencie e consulte as rotas do sistema ferroviário.</p>
        </div>
        <a href="rotas/cadastro.php" class="btn btn-danger py-2">
            <i class="bi bi-plus-lg me-1"></i>Nova Rota
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

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-secondary mb-2">Rotas cadastradas</p>
                        <h2 class="fw-bold mb-0"><?= (int) $estatisticas["total_rotas"] ?></h2>
                    </div>
                    <div class="rounded-3 p-3 bg-danger bg-opacity-10 text-danger">
                        <i class="bi bi-globe-americas fs-2 text-white"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-secondary mb-2">Distância total</p>
                        <h2 class="fw-bold mb-0"><?= number_format((float) $estatisticas["distancia_total"], 2, ",", ".") ?> <small class="fs-6">km</small></h2>
                    </div>
                    <div class="rounded-3 p-3 bg-success bg-opacity-10 text-success">
                        <i class="bi bi-signpost-2 fs-2 text-white"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-secondary mb-2">Tempo médio previsto</p>
                        <h2 class="fw-bold mb-0"><?= number_format((float) $estatisticas["tempo_medio"], 0, ",", ".") ?> <small class="fs-6">min</small></h2>
                    </div>
                    <div class="rounded-3 p-3 bg-warning bg-opacity-10 text-warning">
                        <i class="bi bi-clock-history fs-2 text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold mb-1">Rotas recentes</h4>
                    <p class="text-secondary mb-0">Últimas rotas adicionadas ao sistema.</p>
                </div>
                <a href="rotas/listar.php" class="btn btn-outline-danger">
                    Ver todas <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Rota</th>
                            <th>Distância</th>
                            <th>Tempo previsto</th>
                            <th>Trem associado</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($rotasRecentes && $rotasRecentes->num_rows > 0): ?>
                            <?php while ($rota = $rotasRecentes->fetch_assoc()): ?>
                                <tr>
                                    <td>
                                        <span class="fw-semibold"><?= htmlspecialchars($rota["nome_rota"]) ?></span>
                                        <small class="text-secondary d-block">ID #<?= (int) $rota["id_rota"] ?></small>
                                    </td>
                                    <td><?= number_format((float) $rota["distancia_km"], 2, ",", ".") ?> km</td>
                                    <td><?= (int) $rota["tempo_previsto_min"] ?> min</td>
                                    <td>
                                        <?= htmlspecialchars($rota["nome_trens"]) ?>
                                        <small class="text-secondary d-block"><?= htmlspecialchars($rota["codigo"]) ?></small>
                                    </td>
                                    <td class="text-center">
                                        <a href="rotas/visualizar.php?id=<?= (int) $rota["id_rota"] ?>" class="btn btn-sm btn-outline-secondary" title="Visualizar">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="rotas/editar.php?id=<?= (int) $rota["id_rota"] ?>" class="btn btn-sm btn-outline-primary" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <i class="bi bi-signpost-2 text-secondary display-4 d-block mb-2"></i>
                                    <h5 class="fw-bold">Nenhuma rota cadastrada</h5>
                                    <p class="text-secondary mb-3">Cadastre sua primeira rota ferroviária.</p>
                                    <a href="rotas/cadastro.php" class="btn btn-danger">
                                        <i class="bi bi-plus-lg me-1"></i>Cadastrar rota
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