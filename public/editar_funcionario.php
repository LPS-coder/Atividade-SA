```php
<?php
require_once "../infra/seguranca.php";
exigirAdministrador();
require_once "../infra/conexao.php";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    header("Location: cadastro_user.php");
    exit;
}

$consulta = $conn->prepare("SELECT id_funcionario, nome_funcionario, email_funcionario, cpf_funcionario, tipo_usuario FROM funcionario WHERE id_funcionario = ?");
$consulta->bind_param("i", $id);
$consulta->execute();
$usuario = $consulta->get_result()->fetch_assoc();
$consulta->close();

if (!$usuario) {
    header("Location: cadastro_user.php");
    exit;
}

$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST["nome"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $cpf = preg_replace('/\D/', '', $_POST["cpf"] ?? "");
    $tipo = $_POST["tipo_usuario"] ?? "";
    $senha = $_POST["senha"] ?? "";

    if ($nome === "" || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($cpf) !== 11 || !in_array($tipo, ["Administrador", "Operador"], true)) {
        $erro = "Preencha os campos corretamente.";
    } else {
        $consulta = $conn->prepare("SELECT id_funcionario FROM funcionario WHERE (email_funcionario = ? OR cpf_funcionario = ?) AND id_funcionario <> ?");
        $consulta->bind_param("ssi", $email, $cpf, $id);
        $consulta->execute();
        $duplicado = $consulta->get_result()->num_rows > 0;
        $consulta->close();

        if ($duplicado) {
            $erro = "Este e-mail ou CPF já está cadastrado para outro funcionário.";
        } else {
            if ($senha !== "") {
                $hash = password_hash($senha, PASSWORD_DEFAULT);
                $atualizacao = $conn->prepare("UPDATE funcionario SET nome_funcionario = ?, email_funcionario = ?, cpf_funcionario = ?, tipo_usuario = ?, senha_funcionario = ? WHERE id_funcionario = ?");
                $atualizacao->bind_param("sssssi", $nome, $email, $cpf, $tipo, $hash, $id);
            } else {
                $atualizacao = $conn->prepare("UPDATE funcionario SET nome_funcionario = ?, email_funcionario = ?, cpf_funcionario = ?, tipo_usuario = ? WHERE id_funcionario = ?");
                $atualizacao->bind_param("ssssi", $nome, $email, $cpf, $tipo, $id);
            }

            if ($atualizacao->execute()) {
                $atualizacao->close();
                header("Location: cadastro_user.php?sucesso=edicao");
                exit;
            }

            $erro = "Não foi possível atualizar o funcionário.";
            $atualizacao->close();
        }
    }

    $usuario["nome_funcionario"] = $nome;
    $usuario["email_funcionario"] = $email;
    $usuario["cpf_funcionario"] = $cpf;
    $usuario["tipo_usuario"] = $tipo;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar funcionário | OMNIRAIL</title>
    <link rel="stylesheet" href="../assets/styles/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
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
                <a href="monitoramento.php" class="nav-link text-white">
                    <i class="bi bi-activity me-2"></i>
                    Monitoramento
                </a>
            </li>

            <li>
                <a
                    href="relatorios.php"
                    class="nav-link text-white"
                    aria-current="page"
                >
                    <i class="bi bi-file-earmark-text me-2"></i>
                    Relatórios
                </a>
            </li>

            <li>
                <a href="cadastro_user.php" class="nav-link active">
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

    <main class="flex-grow-1 bg-light p-5" style="margin-left: 280px; min-height: 100vh; width: calc(100% - 280px);">
        <h1 class="fw-bold">Editar funcionário</h1>
        <p class="text-muted">Altere as informações do funcionário selecionado.</p>

        <div class="card p-4 mt-4 shadow-sm">
            <?php if ($erro !== ""): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nome" class="form-label">Nome completo</label>
                        <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($usuario["nome_funcionario"]) ?>" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($usuario["email_funcionario"]) ?>" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="cpf" class="form-label">CPF</label>
                        <input type="text" class="form-control" id="cpf" name="cpf" maxlength="11" pattern="[0-9]{11}" value="<?= htmlspecialchars($usuario["cpf_funcionario"]) ?>" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="tipo_usuario" class="form-label">Tipo de usuário</label>
                        <select class="form-select" id="tipo_usuario" name="tipo_usuario" required>
                            <option value="Operador" <?= $usuario["tipo_usuario"] === "Operador" ? "selected" : "" ?>>Operador</option>
                            <option value="Administrador" <?= $usuario["tipo_usuario"] === "Administrador" ? "selected" : "" ?>>Administrador</option>
                        </select>
                    </div>

                    <div class="col-12 mb-3">
                        <label for="senha" class="form-label">Nova senha (opcional)</label>
                        <input type="password" class="form-control" id="senha" name="senha" minlength="8" autocomplete="new-password">
                        <small class="text-muted">Deixe em branco para manter a senha atual.</small>
                    </div>
                </div>

                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-check-lg me-1"></i> Salvar alterações
                </button>
                <a href="cadastro_user.php" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </main>
</body>
</html>
```