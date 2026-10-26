<?php
require_once "../../infra/conexao.php";
require_once "../../infra/seguranca.php";

exigirAdministrador();

$id_rota = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id_rota || $id_rota < 1) {
    header("Location: listar.php?erro=" . urlencode("ID da rota inválido."));
    exit;
}

$sql = "SELECT r.id_rota, r.nome_rota, r.descricao_rota, r.distancia_km, r.tempo_previsto_min, r.data_criacao, t.nome_trens, t.codigo
        FROM rotas r
        INNER JOIN trens t ON r.id_trem = t.id_trem
        WHERE r.id_rota = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_rota);
$stmt->execute();
$resultado = $stmt->get_result();
$rota = $resultado->fetch_assoc();
$stmt->close();

if (!$rota) {
    header("Location: listar.php?erro=" . urlencode("Rota não encontrada."));
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar Rota | OMNIRAIL</title>
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
            <h1 class="fw-bold mb-1">Detalhes da Rota</h1>
            <p class="text-secondary mb-0">Consulte as informações da rota ferroviária.</p>
        </div>
        <a href="listar.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Voltar</a>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="bg-danger bg-opacity-10 text-danger rounded-3 p-3">
                    <i class="bi bi-globe-americas fs-2 text-white"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-1"><?= htmlspecialchars($rota["nome_rota"]) ?></h3>
                    <span class="text-muted">Rota #<?= (int) $rota["id_rota"] ?></span>
                </div>
            </div>

            <hr>

            <div class="row g-4">
                <div class="col-md-6">
                    <p class="text-muted mb-1">Descrição</p>
                    <p class="fw-semibold"><?= nl2br(htmlspecialchars($rota["descricao_rota"])) ?></p>
                </div>
                <div class="col-md-6">
                    <p class="text-muted mb-1">Trem associado</p>
                    <p class="fw-semibold mb-0"><?= htmlspecialchars($rota["nome_trens"]) ?></p>
                    <span class="text-muted small">Código: <?= htmlspecialchars($rota["codigo"]) ?></span>
                </div>
                <div class="col-md-4">
                    <p class="text-muted mb-1">Distância</p>
                    <h4 class="fw-bold"><?= number_format((float) $rota["distancia_km"], 2, ",", ".") ?> km</h4>
                </div>
                <div class="col-md-4">
                    <p class="text-muted mb-1">Tempo previsto</p>
                    <h4 class="fw-bold"><?= (int) $rota["tempo_previsto_min"] ?> minutos</h4>
                </div>
                <div class="col-md-4">
                    <p class="text-muted mb-1">Data de criação</p>
                    <h5 class="fw-bold"><?= date("d/m/Y H:i", strtotime($rota["data_criacao"])) ?></h5>
                </div>
            </div>

            <hr>

            <div class="d-flex gap-2">
                <a href="editar.php?id=<?= (int) $rota["id_rota"] ?>" class="btn btn-danger"><i class="bi bi-pencil me-1"></i>Editar rota</a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
