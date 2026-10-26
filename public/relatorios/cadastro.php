<?php
require_once "../../infra/seguranca.php";
exigirLogin();
require_once "../../infra/conexao.php";

$idFuncionario = $_SESSION["id_funcionario"] ?? null;

if (!$idFuncionario) {
    header("Location: ../login.php");
    exit;
}

$sensores = $conn->query("
    SELECT id_sensor, nome_sensores, id_trem
    FROM sensores
    ORDER BY nome_sensores
");

$trens = $conn->query("
    SELECT id_trem, nome_trens, codigo
    FROM trens
    ORDER BY nome_trens
");

$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $idSensor = filter_input(INPUT_POST, "id_sensor", FILTER_VALIDATE_INT);
    $idTrem = filter_input(INPUT_POST, "id_trem", FILTER_VALIDATE_INT);
    $descricao = trim($_POST["descricao"] ?? "");

    if (!$idSensor || !$idTrem || $descricao === "") {
        $erro = "Preencha todos os campos corretamente.";
    } else {
        $stmt = $conn->prepare("
            SELECT id_sensor
            FROM sensores
            WHERE id_sensor = ? AND id_trem = ?
        ");

        $stmt->bind_param("ii", $idSensor, $idTrem);
        $stmt->execute();
        $sensorValido = $stmt->get_result()->num_rows > 0;
        $stmt->close();

        if (!$sensorValido) {
            $erro = "O sensor selecionado não pertence ao trem informado.";
        } else {
            $stmt = $conn->prepare("
                INSERT INTO relatorios (id_funcionario, id_sensor, id_trem, descricao)
                VALUES (?, ?, ?, ?)
            ");

            $stmt->bind_param("iiis", $idFuncionario, $idSensor, $idTrem, $descricao);

            if ($stmt->execute()) {
                $stmt->close();
                header("Location: ../relatorios.php?sucesso=relatorio_cadastrado");
                exit;
            }

            $stmt->close();
            $erro = "Não foi possível cadastrar o relatório.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Relatório | OMNIRAIL</title>
    <link rel="stylesheet" href="../../assets/styles/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body id="body-dashboard">

    <div id="sidebar" class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark">
        <a href="../dashboard.php" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
            <img src="../../assets/images/logo.png" alt="Logo" width="40" height="40" class="me-2">
            <span class="fs-4">OMNIRAIL</span>
        </a>

        <hr>

        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="../dashboard.php" class="nav-link text-white">
                    <i class="bi bi-speedometer2 me-2"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <a href="../cadastro_de_sensores_e_trens.php" class="nav-link text-white">
                    <i class="bi bi-broadcast me-2"></i>
                    Sensores & Trens
                </a>
            </li>
            <li>
                <a href="../monitoramento.php" class="nav-link text-white">
                    <i class="bi bi-activity me-2"></i>
                    Monitoramento
                </a>
            </li>
            <li>
                <a href="../relatorios.php" class="nav-link active" aria-current="page">
                    <i class="bi bi-file-earmark-text me-2"></i>
                    Relatórios
                </a>
            </li>
            <li>
                <a href="../cadastro_user.php" class="nav-link text-white">
                    <i class="bi bi-people me-2"></i>
                    Cadastrados
                </a>
            </li>

            <li>
                <a href="../rotas.php" class="nav-link text-white">
                    <i class="bi bi-globe-americas me-2"></i>
                    Rotas
                </a>
            </li>
        </ul>

        <hr>

        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="../../assets/images/User.png" alt="Usuário" class="rounded-circle me-2" width="32" height="32">
                <strong><?= htmlspecialchars($_SESSION["nome_funcionario"] ?? "Usuário") ?></strong>
            </a>
            <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
                <li>
                    <a class="dropdown-item" href="../logout.php">Sair</a>
                </li>
            </ul>
        </div>
    </div>

    <main class="main">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <p class="text-secondary mb-0">Registre as informações do relatório operacional.</p>
                <h1 class="fw-bold mb-0">Novo Relatório</h1>
            </div>
            <a href="../relatorios.php" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                Voltar
            </a>
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
                        <label for="id_sensor" class="form-label fw-semibold">Sensor</label>
                        <select name="id_sensor" id="id_sensor" class="form-select" required>
                            <option value="">Selecione um sensor</option>
                            <?php while ($sensor = $sensores->fetch_assoc()): ?>
                                <option value="<?= (int) $sensor["id_sensor"] ?>" data-trem="<?= (int) $sensor["id_trem"] ?>" <?= (string) ($_POST["id_sensor"] ?? "") === (string) $sensor["id_sensor"] ? "selected" : "" ?>>
                                    <?= htmlspecialchars($sensor["nome_sensores"]) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="id_trem" class="form-label fw-semibold">Trem</label>
                        <select name="id_trem" id="id_trem" class="form-select" required>
                            <option value="">Selecione um trem</option>
                            <?php while ($trem = $trens->fetch_assoc()): ?>
                                <option value="<?= (int) $trem["id_trem"] ?>" <?= (string) ($_POST["id_trem"] ?? "") === (string) $trem["id_trem"] ? "selected" : "" ?>>
                                    <?= htmlspecialchars($trem["nome_trens"] . " (" . $trem["codigo"] . ")") ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="descricao" class="form-label fw-semibold">Descrição</label>
                        <textarea name="descricao" id="descricao" class="form-control" rows="5" placeholder="Descreva o relatório operacional" required><?= htmlspecialchars($_POST["descricao"] ?? "") ?></textarea>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="../relatorios.php" class="btn btn-light px-4">Cancelar</a>
                        <button type="submit" class="btn btn-danger px-4 fw-bold">
                            <i class="bi bi-file-earmark-check me-1"></i>
                            Cadastrar Relatório
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8NlM6XUeP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>