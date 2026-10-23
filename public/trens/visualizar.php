<?php

require_once __DIR__ . "/../../infra/seguranca.php";
exigirLogin();

require_once __DIR__ . "/../../infra/conexao.php";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    header("Location: ../cadastro_de_sensores_e_trens.php");
    exit;
}

$sql = "SELECT id_trem, nome_trens, codigo, modelo FROM trens WHERE id_trem = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$resultado = $stmt->get_result();
$trem = $resultado->fetch_assoc();

$stmt->close();
$conn->close();

if (!$trem) {
    header("Location: ../cadastro_de_sensores_e_trens.php?erro=trem_nao_encontrado");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar Trem | OMNIRAIL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <p class="text-secondary mb-1">Gerenciamento da frota</p>
                <h1 class="fw-bold mb-0">Detalhes do Trem</h1>
            </div>

            <a href="../cadastro_de_sensores_e_trens.php" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Voltar
            </a>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="bg-danger bg-opacity-10 text-danger rounded-3 p-3">
                        <i class="bi bi-train-front-fill fs-2"></i>
                    </div>

                    <div>
                        <h4 class="fw-bold mb-1">
                            <?= htmlspecialchars($trem["nome_trens"], ENT_QUOTES, "UTF-8") ?>
                        </h4>
                        <span class="text-secondary">Código: <?= htmlspecialchars($trem["codigo"], ENT_QUOTES, "UTF-8") ?></span>
                    </div>
                </div>

                <hr>

                <div class="row g-4">
                    <div class="col-md-4">
                        <span class="text-secondary d-block mb-1">ID do trem</span>
                        <strong><?= (int) $trem["id_trem"] ?></strong>
                    </div>

                    <div class="col-md-4">
                        <span class="text-secondary d-block mb-1">Nome</span>
                        <strong><?= htmlspecialchars($trem["nome_trens"], ENT_QUOTES, "UTF-8") ?></strong>
                    </div>

                    <div class="col-md-4">
                        <span class="text-secondary d-block mb-1">Código</span>
                        <strong><?= htmlspecialchars($trem["codigo"], ENT_QUOTES, "UTF-8") ?></strong>
                    </div>

                    <div class="col-md-4">
                        <span class="text-secondary d-block mb-1">Modelo</span>
                        <strong><?= htmlspecialchars($trem["modelo"], ENT_QUOTES, "UTF-8") ?></strong>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="editar.php?id=<?= (int) $trem["id_trem"] ?>" class="btn btn-danger">
                        <i class="bi bi-pencil-square me-2"></i>Editar Trem
                    </a>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
