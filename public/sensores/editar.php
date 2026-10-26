<?php
require_once "../../infra/seguranca.php";
exigirLogin();

require_once "../../infra/conexao.php";

$idSensor = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$idSensor || $idSensor < 1) {
    header("Location: ../cadastro_de_sensores_e_trens.php?erro=sensor_nao_encontrado");
    exit;
}

$stmt = $conn->prepare(
    "SELECT id_sensor, nome_sensores, localizacao, tipo_dado, id_trem
     FROM sensores
     WHERE id_sensor = ?"
);
$stmt->bind_param("i", $idSensor);
$stmt->execute();
$resultado = $stmt->get_result();
$sensor = $resultado->fetch_assoc();
$stmt->close();

if (!$sensor) {
    $conn->close();
    header("Location: ../cadastro_de_sensores_e_trens.php?erro=sensor_nao_encontrado");
    exit;
}

$trens = $conn->query(
    "SELECT id_trem, nome_trens, codigo
     FROM trens
     ORDER BY nome_trens"
);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST["nome_sensores"] ?? "");
    $localizacao = trim($_POST["localizacao"] ?? "");
    $tipoDado = trim($_POST["tipo_dado"] ?? "");
    $idTrem = filter_input(INPUT_POST, "id_trem", FILTER_VALIDATE_INT);

    $tiposPermitidos = [
        "Velocidade",
        "Temperatura",
        "Energia",
        "Falha",
        "Localização"
    ];

    if (
        $nome === "" ||
        $localizacao === "" ||
        !in_array($tipoDado, $tiposPermitidos, true) ||
        !$idTrem ||
        $idTrem < 1 ||
        strlen($nome) > 100 ||
        strlen($localizacao) > 150
    ) {
        $erro = "Preencha todos os campos corretamente.";
    } else {
        $stmtTrem = $conn->prepare(
            "SELECT id_trem FROM trens WHERE id_trem = ?"
        );
        $stmtTrem->bind_param("i", $idTrem);
        $stmtTrem->execute();
        $tremExiste = $stmtTrem->get_result()->num_rows > 0;
        $stmtTrem->close();

        if (!$tremExiste) {
            $erro = "O trem selecionado não existe.";
        } else {
            try {
                $stmt = $conn->prepare(
                    "UPDATE sensores
                     SET nome_sensores = ?, localizacao = ?, tipo_dado = ?, id_trem = ?
                     WHERE id_sensor = ?"
                );
                $stmt->bind_param(
                    "sssii",
                    $nome,
                    $localizacao,
                    $tipoDado,
                    $idTrem,
                    $idSensor
                );
                $stmt->execute();
                $stmt->close();
                $conn->close();

                header("Location: ../cadastro_de_sensores_e_trens.php?sucesso=sensor_editado");
                exit;
            } catch (mysqli_sql_exception $e) {
                error_log($e->getMessage());
                $erro = "Não foi possível atualizar o sensor.";
            }
        }
    }

    $sensor["nome_sensores"] = $nome;
    $sensor["localizacao"] = $localizacao;
    $sensor["tipo_dado"] = $tipoDado;
    $sensor["id_trem"] = $idTrem ?: 0;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Sensor | OMNIRAIL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/styles/style.css">
</head>
<body id="body-dashboard">

    <div id="sidebar" class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark">

        <a
            href="../dashboard.php"
            class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none"
        >
            <img
                src="../../assets/images/logo.png"
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
                <a href="../dashboard.php" class="nav-link text-white">
                    <i class="bi bi-speedometer2 me-2"></i>
                    Dashboard
                </a>
            </li>

            <li>
                <a href="../cadastro_de_sensores_e_trens.php" class="nav-link active" aria-current="page">
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
                <a href="../relatorios.php" class="nav-link text-white">
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
                    src="../../assets/images/User.png"
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
                    <a class="dropdown-item" href="../logout.php">
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
                    <h1 class="h3 mb-1">Editar Sensor</h1>
                    <p class="text-muted mb-0">Atualize as informações do sensor cadastrado.</p>
                </div>
                <a href="../cadastro_de_sensores_e_trens.php" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Voltar
                </a>
            </div>

            <?php if (isset($erro)): ?>
                <div class="alert alert-danger" role="alert">
                    <?= htmlspecialchars($erro) ?>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form method="POST" action="editar.php?id=<?= (int)$idSensor ?>">
                        <div class="mb-3">
                            <label for="nome_sensores" class="form-label">Nome do sensor</label>
                            <input
                                type="text"
                                class="form-control"
                                id="nome_sensores"
                                name="nome_sensores"
                                maxlength="100"
                                value="<?= htmlspecialchars($sensor["nome_sensores"]) ?>"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label for="localizacao" class="form-label">Localização</label>
                            <input
                                type="text"
                                class="form-control"
                                id="localizacao"
                                name="localizacao"
                                maxlength="150"
                                value="<?= htmlspecialchars($sensor["localizacao"]) ?>"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label for="tipo_dado" class="form-label">Tipo de dado</label>
                            <select class="form-select" id="tipo_dado" name="tipo_dado" required>
                                <option value="">Selecione o tipo</option>
                                <?php foreach (["Velocidade", "Temperatura", "Energia", "Falha", "Localização"] as $tipo): ?>
                                    <option value="<?= htmlspecialchars($tipo) ?>" <?= $sensor["tipo_dado"] === $tipo ? "selected" : "" ?>>
                                        <?= htmlspecialchars($tipo) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="id_trem" class="form-label">Trem associado</label>
                            <select class="form-select" id="id_trem" name="id_trem" required>
                                <option value="">Selecione o trem</option>
                                <?php while ($trem = $trens->fetch_assoc()): ?>
                                    <option value="<?= (int)$trem["id_trem"] ?>" <?= (int)$sensor["id_trem"] === (int)$trem["id_trem"] ? "selected" : "" ?>>
                                        <?= htmlspecialchars($trem["nome_trens"] . " (" . $trem["codigo"] . ")") ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-check-lg me-1"></i>Salvar alterações
                        </button>
                        <a href="../cadastro_de_sensores_e_trens.php" class="btn btn-outline-secondary ms-2">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
