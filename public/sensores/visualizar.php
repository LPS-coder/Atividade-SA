<?php
require_once "../../infra/seguranca.php";
exigirLogin();

require_once "../../infra/conexao.php";

$idSensor = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$idSensor || $idSensor < 1) {
    header("Location: ../cadastro_de_sensores_e_trens.php?erro=sensor_nao_encontrado");
    exit;
}

$sql = "SELECT s.id_sensor, s.nome_sensores, s.localizacao,
               s.tipo_dado, s.id_trem, t.nome_trens, t.codigo
        FROM sensores s
        INNER JOIN trens t ON s.id_trem = t.id_trem
        WHERE s.id_sensor = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idSensor);
$stmt->execute();

$resultado = $stmt->get_result();
$sensor = $resultado->fetch_assoc();

$stmt->close();
$conn->close();

if (!$sensor) {
    header("Location: ../cadastro_de_sensores_e_trens.php?erro=sensor_nao_encontrado");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar Sensor | OMNIRAIL</title>
    <link rel="stylesheet" href="../../assets/styles/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body id="body-dashboard">
    <div id="sidebar" class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark">
        <a href="../dashboard.php" class="d-flex align-items-center mb-3 text-white text-decoration-none">
            <img src="../../assets/images/logo.png" alt="Logo" width="40" height="40" class="me-2">
            <span class="fs-4">OMNIRAIL</span>
        </a>

        <hr>

        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="../dashboard.php" class="nav-link text-white">
                    <i class="bi bi-speedometer2 me-2"></i>Dashboard
                </a>
            </li>
            <li>
                <a href="../cadastro_de_sensores_e_trens.php" class="nav-link active">
                    <i class="bi bi-broadcast me-2"></i>Sensores & Trens
                </a>
            </li>
            <li>
                <a href="../monitoramento.php" class="nav-link text-white">
                    <i class="bi bi-activity me-2"></i>Monitoramento
                </a>
            </li>
            <li>
                <a href="../relatorios.php" class="nav-link text-white">
                    <i class="bi bi-file-earmark-text me-2"></i>Relatórios
                </a>
            </li>
            <li>
                <a href="../cadastro_user.php" class="nav-link text-white">
                    <i class="bi bi-people me-2"></i>Cadastrados
                </a>
            </li>
        </ul>

        <hr>

        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
               id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="../../assets/images/User.png" alt="Usuário" class="rounded-circle me-2" width="32" height="32">
                <strong><?= htmlspecialchars($_SESSION["nome_funcionario"] ?? "Usuário") ?></strong>
            </a>
            <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
                <li><a class="dropdown-item" href="../logout.php">Sair</a></li>
            </ul>
        </div>
    </div>

    <div class="main">
        <div class="mb-4">
            <p class="text-secondary mb-1">Informações do sensor cadastrado.</p>
            <h1 class="fw-bold mb-0">Visualizar Sensor</h1>
        </div>

        <div class="card shadow-sm border-0 rounded-4 p-4">
            <div class="card-body">
                <div class="d-flex align-items-center mb-4">
                    <i class="bi bi-broadcast text-danger fs-2 me-3"></i>
                    <div>
                        <h4 class="fw-bold mb-1">
                            <?= htmlspecialchars($sensor["nome_sensores"]) ?>
                        </h4>
                        <span class="text-secondary">
                            Sensor ID: <?= (int) $sensor["id_sensor"] ?>
                        </span>
                    </div>
                </div>

                <hr>

                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label text-secondary">Nome do sensor</label>
                        <p class="fw-bold">
                            <?= htmlspecialchars($sensor["nome_sensores"]) ?>
                        </p>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-secondary">Localização</label>
                        <p class="fw-bold">
                            <?= htmlspecialchars($sensor["localizacao"]) ?>
                        </p>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-secondary">Tipo de dado monitorado</label>
                        <p class="fw-bold">
                            <?= htmlspecialchars($sensor["tipo_dado"]) ?>
                        </p>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-secondary">Trem vinculado</label>
                        <p class="fw-bold">
                            <?= htmlspecialchars($sensor["nome_trens"]) ?>
                            (<?= htmlspecialchars($sensor["codigo"]) ?>)
                        </p>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <a href="../cadastro_de_sensores_e_trens.php" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Voltar
                    </a>

                    <a href="editar.php?id=<?= (int) $sensor["id_sensor"] ?>" class="btn btn-danger">
                        <i class="bi bi-pencil-square me-2"></i>Editar Sensor
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
