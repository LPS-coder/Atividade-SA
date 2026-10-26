<?php
require_once "../../infra/seguranca.php";
exigirLogin();
require_once "../../infra/conexao.php";

$idRelatorio = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$idRelatorio) {
    header("Location: listar.php?erro=id_invalido");
    exit;
}

$stmt = $conn->prepare("
    SELECT
        r.id_relatorio,
        r.id_funcionario,
        r.id_sensor,
        r.id_trem,
        r.data_geracao,
        r.descricao,
        s.nome_sensores,
        s.localizacao,
        s.tipo_dado,
        t.nome_trens,
        t.codigo,
        t.modelo
    FROM relatorios r
    INNER JOIN sensores s ON r.id_sensor = s.id_sensor
    INNER JOIN trens t ON r.id_trem = t.id_trem
    WHERE r.id_relatorio = ?
");

$stmt->bind_param("i", $idRelatorio);
$stmt->execute();
$resultado = $stmt->get_result();
$relatorio = $resultado->fetch_assoc();
$stmt->close();

if (!$relatorio) {
    header("Location: listar.php?erro=relatorio_nao_encontrado");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar Relatório | OMNIRAIL</title>
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

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
            <div>
                <p class="text-secondary mb-0">Consulte os detalhes do registro selecionado.</p>
                <h1 class="fw-bold mb-0">Relatório #<?= (int) $relatorio["id_relatorio"] ?></h1>
            </div>

            <a href="listar.php" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                Voltar à lista
            </a>
        </div>

        <div class="row g-4">

            <div class="col-lg-6">
                <div class="card shadow-sm border-0 rounded-4 h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div class="bg-danger rounded-3 p-3">
                                <i class="bi bi-file-earmark-text text-white fs-3"></i>
                            </div>
                            <div>
                                <h4 class="fw-bold mb-1">Informações do Relatório</h4>
                                <span class="text-secondary">Dados do registro</span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <span class="text-secondary d-block">Identificador</span>
                            <strong>#<?= (int) $relatorio["id_relatorio"] ?></strong>
                        </div>

                        <div class="mb-3">
                            <span class="text-secondary d-block">Data de geração</span>
                            <strong><?= date("d/m/Y H:i:s", strtotime($relatorio["data_geracao"])) ?></strong>
                        </div>

                        <div class="mb-3">
                            <span class="text-secondary d-block">Funcionário responsável (ID)</span>
                            <strong><?= (int) $relatorio["id_funcionario"] ?></strong>
                        </div>

                        <div>
                            <span class="text-secondary d-block mb-2">Descrição</span>
                            <div class="bg-light rounded-3 p-3">
                                <?= nl2br(htmlspecialchars($relatorio["descricao"] ?? "")) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card shadow-sm border-0 rounded-4 h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div class="bg-success rounded-3 p-3">
                                <i class="bi bi-broadcast text-white fs-3"></i>
                            </div>
                            <div>
                                <h4 class="fw-bold mb-1">Sensor Vinculado</h4>
                                <span class="text-secondary">Informações do sensor</span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <span class="text-secondary d-block">Nome</span>
                            <strong><?= htmlspecialchars($relatorio["nome_sensores"]) ?></strong>
                        </div>

                        <div class="mb-3">
                            <span class="text-secondary d-block">Tipo de dado</span>
                            <span class="badge bg-secondary"><?= htmlspecialchars($relatorio["tipo_dado"]) ?></span>
                        </div>

                        <div>
                            <span class="text-secondary d-block">Localização</span>
                            <strong><?= htmlspecialchars($relatorio["localizacao"]) ?></strong>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div class="bg-secondary rounded-3 p-3">
                                <i class="bi bi-train-front text-white fs-3"></i>
                            </div>
                            <div>
                                <h4 class="fw-bold mb-1">Trem Vinculado</h4>
                                <span class="text-secondary">Informações do trem</span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <span class="text-secondary d-block">Nome</span>
                            <strong><?= htmlspecialchars($relatorio["nome_trens"]) ?></strong>
                        </div>

                        <div class="mb-3">
                            <span class="text-secondary d-block">Código</span>
                            <strong><?= htmlspecialchars($relatorio["codigo"]) ?></strong>
                        </div>

                        <div>
                            <span class="text-secondary d-block">Modelo</span>
                            <strong><?= htmlspecialchars($relatorio["modelo"]) ?></strong>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">
            <a href="editar.php?id=<?= (int) $relatorio["id_relatorio"] ?>" class="btn btn-danger fw-bold">
                <i class="bi bi-pencil-square me-1"></i>
                Editar Relatório
            </a>
        </div>

    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8NlM6XUeP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>
</html>
