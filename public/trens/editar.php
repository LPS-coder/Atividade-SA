
<?php

require_once __DIR__ . "/../../infra/seguranca.php";
exigirLogin();

require_once __DIR__ . "/../../infra/conexao.php";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    header("Location: ../cadastro_de_sensores_e_trens.php");
    exit;
}

$erro = "";
$nome = "";
$codigo = "";
$modelo = "";

$sql = "SELECT nome_trens, codigo, modelo FROM trens WHERE id_trem = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$trem = $resultado->fetch_assoc();
$stmt->close();

if (!$trem) {
    header("Location: ../cadastro_de_sensores_e_trens.php?erro=trem_nao_encontrado");
    exit;
}

$nome = $trem["nome_trens"];
$codigo = $trem["codigo"];
$modelo = $trem["modelo"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST["nome_trens"] ?? "");
    $codigo = trim($_POST["codigo"] ?? "");
    $modelo = trim($_POST["modelo"] ?? "");

    if ($nome === "" || $codigo === "" || $modelo === "") {
        $erro = "Preencha todos os campos.";
    } else {
        try {
            $sql = "UPDATE trens
                    SET nome_trens = ?, codigo = ?, modelo = ?
                    WHERE id_trem = ?";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $nome, $codigo, $modelo, $id);
            $stmt->execute();
            $stmt->close();

            header("Location: ../cadastro_de_sensores_e_trens.php?sucesso=trem_editado");
            exit;
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() === 1062) {
                $erro = "Já existe outro trem cadastrado com esse código.";
            } else {
                $erro = "Não foi possível atualizar o trem. Tente novamente.";
            }
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Trem | OMNIRAIL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <p class="text-secondary mb-1">Gerenciamento da frota</p>
                <h1 class="fw-bold mb-0">Editar Trem</h1>
            </div>

            <a href="../cadastro_de_sensores_e_trens.php" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Voltar
            </a>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <p class="text-secondary">Editando o trem ID <?= (int) $id ?></p>

                <?php if ($erro !== ""): ?>
                    <div class="alert alert-warning" role="alert">
                        <?= htmlspecialchars($erro, ENT_QUOTES, "UTF-8") ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="editar.php?id=<?= (int) $id ?>">
                    <div class="mb-3">
                        <label for="nome_trens" class="form-label">Nome do trem</label>
                        <input
                            type="text"
                            class="form-control"
                            id="nome_trens"
                            name="nome_trens"
                            maxlength="100"
                            value="<?= htmlspecialchars($nome, ENT_QUOTES, "UTF-8") ?>"
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label for="codigo" class="form-label">Código</label>
                        <input
                            type="text"
                            class="form-control"
                            id="codigo"
                            name="codigo"
                            maxlength="20"
                            value="<?= htmlspecialchars($codigo, ENT_QUOTES, "UTF-8") ?>"
                            required
                        >
                    </div>

                    <div class="mb-4">
                        <label for="modelo" class="form-label">Modelo</label>
                        <input
                            type="text"
                            class="form-control"
                            id="modelo"
                            name="modelo"
                            maxlength="100"
                            value="<?= htmlspecialchars($modelo, ENT_QUOTES, "UTF-8") ?>"
                            required
                        >
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-check-lg me-2"></i>Salvar alterações
                        </button>

                        <a href="../cadastro_de_sensores_e_trens.php" class="btn btn-outline-secondary">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
