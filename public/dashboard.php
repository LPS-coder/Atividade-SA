<?php
require_once "../infra/seguranca.php";
exigirLogin();

require_once "../infra/conexao.php";

function contarRegistros(mysqli $conn, string $tabela): int
{
    $tabelasPermitidas = ["trens", "sensores", "rotas", "funcionario"];

    if (!in_array($tabela, $tabelasPermitidas, true)) {
        return 0;
    }

    $resultado = $conn->query("SELECT COUNT(*) AS total FROM `$tabela`");

    return $resultado ? (int) $resultado->fetch_assoc()["total"] : 0;
}

$totalTrens = contarRegistros($conn, "trens");
$totalSensores = contarRegistros($conn, "sensores");
$totalRotas = contarRegistros($conn, "rotas");
$totalFuncionarios = contarRegistros($conn, "funcionario");

$tiposSensores = $conn->query("
    SELECT tipo_dado, COUNT(*) AS total
    FROM sensores
    GROUP BY tipo_dado
    ORDER BY total DESC
");

$atividades = $conn->query("
    SELECT 'Sensor' AS categoria, nome_sensores AS nome, id_sensor AS identificador
    FROM sensores
    UNION ALL
    SELECT 'Trem', nome_trens, id_trem
    FROM trens
    UNION ALL
    SELECT 'Rota', nome_rota, id_rota
    FROM rotas
    ORDER BY identificador DESC
    LIMIT 6
");

$nomeFuncionario = $_SESSION["nome_funcionario"] ?? "Usuário";
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | OMNIRAIL</title>
    <link rel="stylesheet" href="../assets/styles/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body id="body-dashboard">

    <div id="sidebar" class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark">

        <a
            href="dashboard.php"
            class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none"
        >
            <img
                src="../assets/images/logo.png"
                alt="Logo"
                width="40"
                height="40"
                class="me-2"
            >

            <span class="fs-4">
                OMNIRAIL
            </span>
        </a>

        <hr>

        <ul class="nav nav-pills flex-column mb-auto">

            <li class="nav-item">
                <a href="dashboard.php" class="nav-link active">
                    <i class="bi bi-speedometer2 me-2"></i>
                    Dashboard
                </a>
            </li>

            <li>
                <a href="cadastro_de_sensores_e_trens.php" class="nav-link text-white">
                    <i class="bi bi-broadcast me-2"></i>
                    Sensores & Trens
                </a>
            </li>

            <li>
                <a href="monitoramento.php" class="nav-link text-white" aria-current="page">
                    <i class="bi bi-activity me-2"></i>
                    Monitoramento
                </a>
            </li>

            <li>
                <a href="relatorios.php" class="nav-link text-white">
                    <i class="bi bi-file-earmark-text me-2"></i>
                    Relatórios
                </a>
            </li>

            <li>
                <a href="cadastro_user.php" class="nav-link text-white">
                    <i class="bi bi-people me-2"></i>
                    Cadastrados
                </a>
            </li>

            <li>
                <a href="../public/rotas.php" class="nav-link text-white">
                    <i class="bi bi-globe-americas me-2"></i>
                    Rotas
                </a>
            </li>
        </ul>

        <hr>

        <div class="dropdown">

            <a
                href="#"
                class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
                id="dropdownUser1"
                data-bs-toggle="dropdown"
                aria-expanded="false"
            >
                <img
                    src="../assets/images/User.png"
                    alt="Usuário"
                    class="rounded-circle me-2"
                    width="32"
                    height="32"
                >

                <strong>
                    <?= htmlspecialchars($_SESSION["nome_funcionario"] ?? "Usuário") ?>
                </strong>
            </a>

            <ul
                class="dropdown-menu dropdown-menu-dark text-small shadow"
                aria-labelledby="dropdownUser1"
            >
                <li>
                    <a class="dropdown-item" href="logout.php">
                        Sair
                    </a>
                </li>
            </ul>

        </div>

    </div>

    <main class="main">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
            <div>
                <p class="text-secondary mb-1">Bem-vindo de volta,</p>
                <h1 class="fw-bold mb-0"><?= htmlspecialchars($nomeFuncionario) ?></h1>
                <p class="text-secondary mt-2 mb-0">Visão geral da infraestrutura ferroviária.</p>
            </div>

            <a href="cadastro_de_sensores_e_trens.php" class="btn btn-novo-sensor px-4 py-2 rounded-3 fw-bold">
                <i class="bi bi-plus-lg me-2"></i> Novo cadastro
            </a>
        </div>

        <div class="row g-3 mb-4">

            <div class="col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 p-3 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-secondary mb-1">Trens cadastrados</p>
                            <h2 class="fw-bold mb-0"><?= $totalTrens ?></h2>
                        </div>
                        <div class="dashboard-icon text-danger">
                            <i class="bi bi-train-front-fill"></i>
                        </div>
                    </div>
                    <a href="cadastro_de_sensores_e_trens.php" class="small text-decoration-none text-danger mt-3">Ver trens <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 p-3 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-secondary mb-1">Sensores cadastrados</p>
                            <h2 class="fw-bold mb-0"><?= $totalSensores ?></h2>
                        </div>
                        <div class="dashboard-icon text-danger">
                            <i class="bi bi-broadcast"></i>
                        </div>
                    </div>
                    <a href="cadastro_de_sensores_e_trens.php" class="small text-decoration-none text-danger mt-3">Ver sensores <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 p-3 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-secondary mb-1">Rotas cadastradas</p>
                            <h2 class="fw-bold mb-0"><?= $totalRotas ?></h2>
                        </div>
                        <div class="dashboard-icon text-danger">
                            <i class="bi bi-signpost-2"></i>
                        </div>
                    </div>
                    <a href="rotas.php" class="small text-decoration-none text-danger mt-3">Ver rotas <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 p-3 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-secondary mb-1">Funcionários</p>
                            <h2 class="fw-bold mb-0"><?= $totalFuncionarios ?></h2>
                        </div>
                        <div class="dashboard-icon text-danger">
                            <i class="bi bi-people"></i>
                        </div>
                    </div>
                    <a href="cadastro_user.php" class="small text-decoration-none text-danger mt-3">Ver funcionários <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>

        </div>

        <div class="row g-4">

            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0">Distribuição de sensores</h5>
                        <i class="bi bi-pie-chart text-danger fs-4"></i>
                    </div>

                    <?php if ($tiposSensores && $tiposSensores->num_rows > 0): ?>
                        <?php while ($tipo = $tiposSensores->fetch_assoc()): ?>
                            <?php $porcentagem = $totalSensores > 0 ? ($tipo["total"] / $totalSensores) * 100 : 0; ?>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span><?= htmlspecialchars($tipo["tipo_dado"]) ?></span>
                                    <strong><?= (int) $tipo["total"] ?></strong>
                                </div>
                                <div class="progress" style="height: 9px;">
                                    <div class="progress-bar bg-danger" role="progressbar" style="width: <?= $porcentagem ?>%"></div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="text-secondary mb-0">Nenhum sensor cadastrado.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0">Estrutura do sistema</h5>
                        <i class="bi bi-diagram-3 text-danger fs-4"></i>
                    </div>

                    <div class="d-flex align-items-center justify-content-between border-bottom py-3">
                        <span><i class="bi bi-train-front me-2 text-danger"></i>Trens</span>
                        <strong><?= $totalTrens ?></strong>
                    </div>
                    <div class="d-flex align-items-center justify-content-between border-bottom py-3">
                        <span><i class="bi bi-broadcast me-2 text-danger"></i>Sensores</span>
                        <strong><?= $totalSensores ?></strong>
                    </div>
                    <div class="d-flex align-items-center justify-content-between border-bottom py-3">
                        <span><i class="bi bi-signpost-2 me-2 text-danger"></i>Rotas</span>
                        <strong><?= $totalRotas ?></strong>
                    </div>
                    <div class="d-flex align-items-center justify-content-between py-3">
                        <span><i class="bi bi-people me-2 text-danger"></i>Funcionários</span>
                        <strong><?= $totalFuncionarios ?></strong>
                    </div>
                </div>
            </div>

        </div>

        <div class="card border-0 shadow-sm rounded-4 p-4 mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Cadastros recentes</h5>
                <span class="text-secondary small">Últimos registros</span>
            </div>

            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Tipo</th>
                            <th>Nome</th>
                            <th>Identificação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($atividades && $atividades->num_rows > 0): ?>
                            <?php while ($atividade = $atividades->fetch_assoc()): ?>
                                <tr>
                                    <td>
                                        <span class="badge bg-light text-dark"><?= htmlspecialchars($atividade["categoria"]) ?></span>
                                    </td>
                                    <td><?= htmlspecialchars($atividade["nome"]) ?></td>
                                    <td>#<?= (int) $atividade["identificador"] ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center text-secondary py-4">Nenhum cadastro encontrado.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
