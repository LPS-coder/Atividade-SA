```php
<?php
require_once "../infra/seguranca.php";
exigirLogin();

require_once "../infra/conexao.php";

$sql = "SELECT
            s.id_sensor,
            s.nome_sensores,
            s.localizacao,
            s.tipo_dado,
            t.nome_trens,
            t.codigo
        FROM sensores s
        INNER JOIN trens t ON s.id_trem = t.id_trem
        ORDER BY s.id_sensor DESC";

$resultado = $conn->query($sql);

$totalSensores = $resultado->num_rows;

$tipos = [
    "Velocidade" => "primary",
    "Temperatura" => "warning",
    "Energia" => "success",
    "Falha" => "danger",
    "Localização" => "info"
];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoramento | OMNIRAIL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/styles/style.css">
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
                <a href="monitoramento.php" class="nav-link active" aria-current="page">
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
        <div class="container-fluid">


            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <p class="text-secondary mb-0">
                        Acompanhe os sensores e consulte informações da operação ferroviária.
                    </p>

                    <h1 class="fw-bold mb-0">
                        Monitoramento
                    </h1>
                </div>

                <a href="cadastro_de_sensores_e_trens.php" class="btn btn-danger fw-bold">
                    <i class="bi bi-plus-lg me-1"></i>
                    Cadastrar sensor
                </a>
            </div>


            <div class="row g-3 mb-4">

                <div class="col-12 col-md-6 col-xl-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="rounded p-3 bg-danger bg-opacity-10 me-3">
                                <i class="bi bi-broadcast text-white fs-3"></i>
                            </div>

                            <div>
                                <p class="text-secondary mb-1">Sensores cadastrados</p>
                                <h3 class="fw-bold mb-0"><?= $totalSensores ?></h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-xl-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="rounded p-3 bg-primary bg-opacity-10 me-3">
                                <i class="bi bi-tags-fill text-white fs-3"></i>
                            </div>

                            <div>
                                <p class="text-secondary mb-1">Tipos de sensores</p>
                                <h3 class="fw-bold mb-0">
                                    <?php
                                    $resultado->data_seek(0);
                                    $tiposEncontrados = [];

                                    while ($sensor = $resultado->fetch_assoc()) {
                                        $tiposEncontrados[$sensor["tipo_dado"]] = true;
                                    }

                                    echo count($tiposEncontrados);
                                    $resultado->data_seek(0);
                                    ?>
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-xl-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="rounded p-3 bg-success bg-opacity-10 me-3">
                                <i class="bi bi-database-check text-white fs-3"></i>
                            </div>

                            <div>
                                <p class="text-secondary mb-1">Sensores vinculados a trens</p>
                                <h3 class="fw-bold mb-0"><?= $totalSensores ?></h3>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0">Sensores monitorados</h5>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 w-100">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Sensor</th>
                                    <th>Localização</th>
                                    <th>Tipo de dado</th>
                                    <th>Trem associado</th>
                                    <th>Cadastro</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php if ($totalSensores > 0): ?>
                                    <?php while ($sensor = $resultado->fetch_assoc()): ?>
                                        <tr>
                                            <td><?= (int)$sensor["id_sensor"] ?></td>

                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-broadcast text-danger fs-5 me-2"></i>
                                                    <span><?= htmlspecialchars($sensor["nome_sensores"]) ?></span>
                                                </div>
                                            </td>

                                            <td>
                                                <i class="bi bi-geo-alt text-muted me-1"></i>
                                                <?= htmlspecialchars($sensor["localizacao"]) ?>
                                            </td>

                                            <td>
                                                <?php
                                                $tipo = $sensor["tipo_dado"];
                                                $cor = $tipos[$tipo] ?? "secondary";
                                                ?>
                                                <span class="badge bg-<?= $cor ?>">
                                                    <?= htmlspecialchars($tipo) ?>
                                                </span>
                                            </td>

                                            <td>
                                                <div><?= htmlspecialchars($sensor["nome_trens"]) ?></div>
                                                <small class="text-muted">
                                                    <?= htmlspecialchars($sensor["codigo"]) ?>
                                                </small>
                                            </td>

                                            <td>
                                                <span class="badge bg-secondary">
                                                    Cadastrado
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <i class="bi bi-broadcast fs-2 text-muted d-block mb-2"></i>
                                            <p class="mb-1">Nenhum sensor cadastrado.</p>
                                            <a href="cadastro_de_sensores_e_trens.php" class="btn btn-sm btn-danger mt-2">
                                                Cadastrar primeiro sensor
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
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
```