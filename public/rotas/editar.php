<?php
require_once "../../infra/conexao.php";
require_once "../../infra/seguranca.php";

exigirAdministrador();

$id_rota = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id_rota || $id_rota < 1) {
    header("Location: listar.php?erro=" . urlencode("ID da rota inválido."));
    exit;
}

$stmt = $conn->prepare("SELECT * FROM rotas WHERE id_rota = ?");
$stmt->bind_param("i", $id_rota);
$stmt->execute();
$rota = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$rota) {
    header("Location: listar.php?erro=" . urlencode("Rota não encontrada."));
    exit;
}

$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST["nome_rota"] ?? "");
    $descricao = trim($_POST["descricao_rota"] ?? "");
    $distancia = str_replace(",", ".", trim($_POST["distancia_km"] ?? ""));
    $tempo = trim($_POST["tempo_previsto_min"] ?? "");
    $id_trem = filter_input(INPUT_POST, "id_trem", FILTER_VALIDATE_INT);

    if (
        $nome === "" ||
        $descricao === "" ||
        mb_strlen($nome) > 100 ||
        mb_strlen($descricao) > 500 ||
        !is_numeric($distancia) ||
        (float) $distancia <= 0 ||
        filter_var($tempo, FILTER_VALIDATE_INT) === false ||
        (int) $tempo <= 0 ||
        !$id_trem
    ) {
        $erro = "Preencha todos os campos com valores válidos.";
    } else {
        $verificarTrem = $conn->prepare("SELECT id_trem FROM trens WHERE id_trem = ?");
        $verificarTrem->bind_param("i", $id_trem);
        $verificarTrem->execute();
        $tremExiste = $verificarTrem->get_result()->num_rows > 0;
        $verificarTrem->close();

        if (!$tremExiste) {
            $erro = "O trem selecionado não existe.";
        } else {
            $sql = "UPDATE rotas SET nome_rota = ?, descricao_rota = ?, distancia_km = ?, tempo_previsto_min = ?, id_trem = ? WHERE id_rota = ?";
            $stmt = $conn->prepare($sql);
            $distancia = (float) $distancia;
            $tempo = (int) $tempo;
            $stmt->bind_param("ssdiii", $nome, $descricao, $distancia, $tempo, $id_trem, $id_rota);

            if ($stmt->execute()) {
                header("Location: visualizar.php?id=" . $id_rota);
                exit;
            } else {
                $erro = $stmt->errno === 1062
                    ? "Já existe outra rota com esse nome."
                    : "Não foi possível atualizar a rota.";
            }

            $stmt->close();
        }
    }

    $rota["nome_rota"] = $nome;
    $rota["descricao_rota"] = $descricao;
    $rota["distancia_km"] = $distancia;
    $rota["tempo_previsto_min"] = $tempo;
    $rota["id_trem"] = $id_trem;
}

$trens = $conn->query("SELECT id_trem, nome_trens, codigo FROM trens ORDER BY nome_trens");
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Rota | OMNIRAIL</title>
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
            <h1 class="fw-bold mb-1">Editar Rota</h1>
            <p class="text-secondary mb-0">Atualize as informações da rota ferroviária.</p>
        </div>
        <a href="listar.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Voltar</a>
    </div>

    <?php if ($erro !== ""): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($erro) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">
            <form method="POST">
                <div class="mb-3">
                    <label for="nome_rota" class="form-label">Nome da rota</label>
                    <input type="text" class="form-control" id="nome_rota" name="nome_rota" maxlength="100" value="<?= htmlspecialchars((string) $rota["nome_rota"]) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="descricao_rota" class="form-label">Descrição</label>
                    <textarea class="form-control" id="descricao_rota" name="descricao_rota" rows="4" maxlength="500" required><?= htmlspecialchars((string) $rota["descricao_rota"]) ?></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="distancia_km" class="form-label">Distância (km)</label>
                        <input type="number" class="form-control" id="distancia_km" name="distancia_km" min="0.01" step="0.01" value="<?= htmlspecialchars((string) $rota["distancia_km"]) ?>" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="tempo_previsto_min" class="form-label">Tempo previsto (minutos)</label>
                        <input type="number" class="form-control" id="tempo_previsto_min" name="tempo_previsto_min" min="1" step="1" value="<?= htmlspecialchars((string) $rota["tempo_previsto_min"]) ?>" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="id_trem" class="form-label">Trem associado</label>
                    <select class="form-select" id="id_trem" name="id_trem" required>
                        <option value="">Selecione um trem</option>
                        <?php while ($trem = $trens->fetch_assoc()): ?>
                            <option value="<?= (int) $trem["id_trem"] ?>" <?= (string) $rota["id_trem"] === (string) $trem["id_trem"] ? "selected" : "" ?>>
                                <?= htmlspecialchars($trem["nome_trens"] . " — " . $trem["codigo"]) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-danger"><i class="bi bi-check-lg me-1"></i>Salvar alterações</button>
                    <a href="visualizar.php?id=<?= (int) $id_rota ?>" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
