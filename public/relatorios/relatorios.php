<?php
require_once "../infra/seguranca.php";
exigirLogin();
require_once "../infra/conexao.php";

$totalRelatorios = $conn->query("SELECT COUNT(*) AS total FROM relatorios")->fetch_assoc()["total"];
$totalSensores = $conn->query("SELECT COUNT(DISTINCT id_sensor) AS total FROM relatorios")->fetch_assoc()["total"];
$totalTrens = $conn->query("SELECT COUNT(DISTINCT id_trem) AS total FROM relatorios")->fetch_assoc()["total"];

$relatorios = $conn->query("
    SELECT
        r.id_relatorio,
        r.data_geracao,
        r.descricao,
        s.nome_sensores,
        s.tipo_dado,
        t.nome_trens,
        t.codigo
    FROM relatorios r
    INNER JOIN sensores s ON r.id_sensor = s.id_sensor
    INNER JOIN trens t ON r.id_trem = t.id_trem
    ORDER BY r.data_geracao DESC
    LIMIT 5
");
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios Operacionais | OMNIRAIL</title>
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
                <a href="dashboard.php" class="nav-link text-white">
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
                <a href="relatorios.php" class="nav-link active">
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

    <div class="main">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
            <div>
                <p class="text-secondary mb-0">Gerencie e consulte os registros da operação ferroviária.</p>
                <h1 class="fw-bold mb-0">Relatórios Operacionais</h1>
            </div>

            <a href="relatorios/cadastro.php" class="btn btn-novo-sensor fw-bold px-4 py-2">
                <i class="bi bi-plus-lg me-2"></i>Novo Relatório
            </a>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm border-0 rounded-4 h-100">
                    <div class="card-body p-4 d-flex align-items-center gap-3">
                        <div class="bg-danger rounded-3 p-3">
                            <i class="bi bi-file-earmark-text text-white fs-3"></i>
                        </div>
                        <div>
                            <p class="text-secondary mb-1">Total de Relatórios</p>
                            <h2 class="fw-bold mb-0"><?= (int) $totalRelatorios ?></h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-0 rounded-4 h-100">
                    <div class="card-body p-4 d-flex align-items-center gap-3">
                        <div class="bg-danger rounded-3 p-3">
                            <i class="bi bi-broadcast text-white fs-3"></i>
                        </div>
                        <div>
                            <p class="text-secondary mb-1">Sensores Registrados</p>
                            <h2 class="fw-bold mb-0"><?= (int) $totalSensores ?></h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-0 rounded-4 h-100">
                    <div class="card-body p-4 d-flex align-items-center gap-3">
                        <div class="bg-danger rounded-3 p-3">
                            <i class="bi bi-train-front text-white fs-3"></i>
                        </div>
                        <div>
                            <p class="text-secondary mb-1">Trens Vinculados</p>
                            <h2 class="fw-bold mb-0"><?= (int) $totalTrens ?></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                    <div>
                        <h4 class="fw-bold mb-1">Relatórios Recentes</h4>
                        <p class="text-secondary mb-0">Últimos registros adicionados ao sistema.</p>
                    </div>

                    <a href="relatorios/listar.php" class="btn btn-outline-danger fw-semibold">
                        Ver todos <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Sensor</th>
                                <th>Trem</th>
                                <th>Data de Geração</th>
                                <th class="text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($relatorios && $relatorios->num_rows > 0): ?>
                                <?php while ($relatorio = $relatorios->fetch_assoc()): ?>
                                    <tr>
                                        <td class="fw-semibold">#<?= (int) $relatorio["id_relatorio"] ?></td>
                                        <td>
                                            <div class="fw-semibold"><?= htmlspecialchars($relatorio["nome_sensores"]) ?></div>
                                            <span class="badge bg-secondary"><?= htmlspecialchars($relatorio["tipo_dado"]) ?></span>
                                        </td>
                                        <td>
                                            <div class="fw-semibold"><?= htmlspecialchars($relatorio["nome_trens"]) ?></div>
                                            <small class="text-secondary"><?= htmlspecialchars($relatorio["codigo"]) ?></small>
                                        </td>
                                        <td><?= date("d/m/Y H:i", strtotime($relatorio["data_geracao"])) ?></td>
                                        <td>
                                            <div class="d-flex justify-content-end gap-2">
                                                <a href="relatorios/visualizar.php?id=<?= (int) $relatorio["id_relatorio"] ?>" class="btn btn-sm btn-outline-secondary" title="Visualizar">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="relatorios/editar.php?id=<?= (int) $relatorio["id_relatorio"] ?>" class="btn btn-sm btn-outline-danger" title="Editar">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <i class="bi bi-file-earmark-x text-secondary display-5"></i>
                                        <p class="fw-semibold mt-3 mb-1">Nenhum relatório cadastrado</p>
                                        <p class="text-secondary mb-3">Cadastre seu primeiro relatório para começar.</p>
                                        <a href="relatorios/cadastro.php" class="btn btn-danger">
                                            <i class="bi bi-plus-lg me-1"></i>Novo Relatório
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
