<?php
require_once "../infra/seguranca.php";

exigirAdministrador();

require_once "../infra/conexao.php";

if (
    !isset($_SESSION["id_funcionario"]) ||
    !isset($_SESSION["tipo_usuario"]) ||
    $_SESSION["tipo_usuario"] !== "Administrador"
) {
    header("Location: login.php");
    exit;
}

$mensagem = "";
$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nome = trim($_POST["nome"] ?? "");
    $cpf = preg_replace("/\D/", "", $_POST["cpf"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $senha = $_POST["senha"] ?? "";
    $tipo_usuario = $_POST["tipo_usuario"] ?? "";

if (
    $nome === "" ||
    $cpf === "" ||
    $email === "" ||
    $senha === "" ||
    $tipo_usuario === ""
) {
    $erro = "Preencha todos os campos.";
} elseif (strlen($cpf) !== 11) {
    $erro = "O CPF deve conter 11 números.";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $erro = "Informe um e-mail válido.";
} elseif (!in_array($tipo_usuario, ["Administrador", "Operador"])) {
    $erro = "Tipo de usuário inválido.";
} else {

    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    $sql = "INSERT INTO funcionario
        (nome_funcionario, cpf_funcionario, email_funcionario, senha_funcionario, tipo_usuario)
        VALUES (?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "sssss",
            $nome,
            $cpf,
            $email,
            $senha_hash,
            $tipo_usuario
        );

        if ($stmt->execute()) {
            $mensagem = "Funcionário cadastrado com sucesso.";
        } elseif ($stmt->errno === 1062) {
            $erro = "O CPF ou e-mail informado já está cadastrado.";
        } else {
            $erro = "Erro ao cadastrar funcionário.";
        }

        $stmt->close();
    }
}

$usuarios = [];

$sqlUsuarios = "SELECT
                    id_funcionario,
                    nome_funcionario,
                    email_funcionario,
                    cpf_funcionario,
                    tipo_usuario
                FROM funcionario
                ORDER BY id_funcionario DESC";

$resultadoUsuarios = $conn->query($sqlUsuarios);

if ($resultadoUsuarios) {
    while ($usuario = $resultadoUsuarios->fetch_assoc()) {
        $usuarios[] = $usuario;
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["excluir_usuario"])) {

    $id_usuario = (int) $_POST["excluir_usuario"];

    if ($id_usuario === (int) $_SESSION["id_funcionario"]) {
        $erro = "Você não pode excluir o próprio usuário.";
    } else {
        $sqlExcluir = "DELETE FROM funcionario WHERE id_funcionario = ?";

        $stmtExcluir = $conn->prepare($sqlExcluir);
        $stmtExcluir->bind_param("i", $id_usuario);

        if ($stmtExcluir->execute()) {
            $mensagem = "Usuário excluído com sucesso.";
        } else {
            $erro = "Erro ao excluir usuário.";
        }

        $stmtExcluir->close();
    }
}

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="../assets/styles/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    
<div id="sidebar" class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark">
    <a href="/ceb/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none"> 
     <img src="../assets/images/logo.png" alt="Logo" width="40" height="40" class="me-2"> <span class="fs-4">OMNIRAIL</span> </a> 
    <hr> 
    <ul class="nav nav-pills flex-column mb-auto"> 
     <li class="nav-item"> <a href="dashboard.php" class="nav-link text-white" aria-current="page"> 
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-speedometer2 me-2" viewBox="0 0 16 16">
            <path d="M8 4a.5.5 0 0 1 .5.5V6a.5.5 0 0 1-1 0V4.5A.5.5 0 0 1 8 4M3.732 5.732a.5.5 0 0 1 .707 0l.915.914a.5.5 0 1 1-.708.708l-.914-.915a.5.5 0 0 1 0-.707M2 10a.5.5 0 0 1 .5-.5h1.586a.5.5 0 0 1 0 1H2.5A.5.5 0 0 1 2 10m9.5 0a.5.5 0 0 1 .5-.5h1.5a.5.5 0 0 1 0 1H12a.5.5 0 0 1-.5-.5m.754-4.246a.39.39 0 0 0-.527-.02L7.547 9.31a.91.91 0 1 0 1.302 1.258l3.434-4.297a.39.39 0 0 0-.029-.518z"/>
            <path fill-rule="evenodd" d="M0 10a8 8 0 1 1 15.547 2.661c-.442 1.253-1.845 1.602-2.932 1.25C11.309 13.488 9.475 13 8 13c-1.474 0-3.31.488-4.615.911-1.087.352-2.49.003-2.932-1.25A8 8 0 0 1 0 10m8-7a7 7 0 0 0-6.603 9.329c.203.575.923.876 1.68.63C4.397 12.533 6.358 12 8 12s3.604.532 4.923.96c.757.245 1.477-.056 1.68-.631A7 7 0 0 0 8 3"/>
        </svg>Dashboard</a> </li>

     <li> <a href="cadastro_de_sensores_e_trens.php" class="nav-link text-white"> 
       <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-broadcast me-2" viewBox="0 0 16 16">
            <path d="M3.05 3.05a7 7 0 0 0 0 9.9.5.5 0 0 1-.707.707 8 8 0 0 1 0-11.314.5.5 0 0 1 .707.707m2.122 2.122a4 4 0 0 0 0 5.656.5.5 0 1 1-.708.708 5 5 0 0 1 0-7.072.5.5 0 0 1 .708.708m5.656-.708a.5.5 0 0 1 .708 0 5 5 0 0 1 0 7.072.5.5 0 1 1-.708-.708 4 4 0 0 0 0-5.656.5.5 0 0 1 0-.708m2.122-2.12a.5.5 0 0 1 .707 0 8 8 0 0 1 0 11.313.5.5 0 0 1-.707-.707 7 7 0 0 0 0-9.9.5.5 0 0 1 0-.707zM10 8a2 2 0 1 1-4 0 2 2 0 0 1 4 0"/>
        </svg>Sensores & Trens</a> </li> 

     <li> <a href="monitoramento.php" class="nav-link text-white"> 
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-activity me-2" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M6 2a.5.5 0 0 1 .47.33L10 12.036l1.53-4.208A.5.5 0 0 1 12 7.5h3.5a.5.5 0 0 1 0 1h-3.15l-1.88 5.17a.5.5 0 0 1-.94 0L6 3.964 4.47 8.171A.5.5 0 0 1 4 8.5H.5a.5.5 0 0 1 0-1h3.15l1.88-5.17A.5.5 0 0 1 6 2"/>
        </svg>Monitoramento</a> </li> 

     <li> <a href="relatorios.php" class="nav-link text-white"> 
       <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-text me-2" viewBox="0 0 16 16">
            <path d="M5.5 7a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1zM5 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5"/>
            <path d="M9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.5zm0 1v2A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z"/>
        </svg>Relatórios</a> </li> 

     <li> <a href="cadastro_user.php" class="nav-link active"> 
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-people me-2" viewBox="0 0 16 16">
            <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1zm-7.978-1L7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002-.014.002zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0M6.936 9.28a6 6 0 0 0-1.23-.247A7 7 0 0 0 5 9c-4 0-5 3-5 4q0 1 1 1h4.216A2.24 2.24 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816M4.92 10A5.5 5.5 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0m3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4"/>
        </svg>Cadastrados</a> </li> 
    </ul> 
    <hr> 
    <div class="dropdown"> 
     <a href="/ceb/docs/5.1/examples/sidebars/#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false"> <img src="../assets/images/User.png" alt="" class="rounded-circle me-2" width="32" height="32"> <strong>Administrador</strong> </a> 
     <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1"> 
      <li>
          <a class="dropdown-item" href="logout.php">Sair</a>
      </li>  
     </ul> 
    </div> 
   </div>

        <div class="flex-grow-1 bg-light p-5"
                style="margin-left: 280px; min-height: 100vh; width: calc(100% - 280px);">

                <h1 class="fw-bold">Funcionários Cadastrados</h1>

                <p class="text-muted">
                    Preencha as informações abaixo para cadastrar um novo usuário.
                </p>

                <div class="card p-4 mt-4 shadow-sm">

            <?php if ($mensagem !== ""): ?>

                <div class="alert alert-success">
                    <?= htmlspecialchars($mensagem) ?>
                </div>

            <?php endif; ?>

            <?php if ($erro !== ""): ?>

                <div class="alert alert-danger">
                    <?= htmlspecialchars($erro) ?>
                </div>

            <?php endif; ?>

            <form method="POST" action="cadastro_user.php">

                <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nome" class="form-label">
                        Nome Completo
                    </label>
                    <input
                        type="text"
                        class="form-control"
                        id="nome"
                        name="nome"
                        required
                        >
                </div>

                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">
                            E-mail
                        </label>

                        <input
                            type="email"
                            class="form-control"
                            id="email"
                            name="email"
                            required
                        >
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="cpf" class="form-label">
                            CPF
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="cpf"
                            name="cpf"
                            maxlength="11"
                            required
                        >
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="senha" class="form-label">
                            Senha
                        </label>

                        <input
                            type="password"
                            class="form-control"
                            id="senha"
                            name="senha"
                            required
                        >
                    </div>

                    <div class="col-md-6 mb-3">

                        <label for="tipo_usuario" class="form-label">
                            Tipo de usuário
                        </label>

                        <select
                            class="form-select"
                            id="tipo_usuario"
                            name="tipo_usuario"
                            required
                        >
                            <option value="Operador" selected>
                                Operador
                            </option>

                            <option value="Administrador">
                                Administrador
                            </option>
                        </select>

                    </div>

                </div>

                <button type="submit" class="btn btn-danger">
                    Cadastrar funcionário
                </button>

            </form>

        </div>

        <div class="card p-4 mt-4 shadow-sm">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h4 class="fw-bold mb-1">Usuários registrados</h4>
                    <p class="text-muted mb-0">
                        Consulte e filtre os funcionários cadastrados no sistema.
                    </p>
                </div>

                <span class="badge bg-dark">
                    <?= count($usuarios) ?> usuários
                </span>
            </div>

            <div class="row g-3 mb-4">

                <div class="col-md-4">
                    <label for="filtroId" class="form-label">
                        Filtrar por ID
                    </label>

                    <input
                        type="number"
                        id="filtroId"
                        class="form-control"
                        placeholder="Digite o ID">
                </div>

                <div class="col-md-4">
                    <label for="filtroNome" class="form-label">
                        Filtrar por nome
                    </label>

                    <input
                        type="text"
                        id="filtroNome"
                        class="form-control"
                        placeholder="Digite o nome">
                </div>

                <div class="col-md-4">
                    <label for="filtroTipo" class="form-label">
                        Tipo de usuário
                    </label>

                    <select id="filtroTipo" class="form-select">
                        <option value="">Todos</option>
                        <option value="Administrador">Administrador</option>
                        <option value="Operador">Operador</option>
                    </select>
                </div>

            </div>

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-dark">

                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>E-mail</th>
                            <th>CPF</th>
                            <th>Tipo de usuário</th>
                            <th>Ações</th>
                        </tr>

                    </thead>

                    <tbody id="tabelaUsuarios">

                        <?php foreach ($usuarios as $usuario): ?>

                            <tr
                                data-id="<?= $usuario["id_funcionario"] ?>"
                                data-nome="<?= htmlspecialchars(strtolower($usuario["nome_funcionario"])) ?>"
                                data-tipo="<?= htmlspecialchars($usuario["tipo_usuario"]) ?>"
                            >

                                <td>
                                    <?= $usuario["id_funcionario"] ?>
                                </td>

                                <td class="fw-semibold">
                                    <?= htmlspecialchars($usuario["nome_funcionario"]) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($usuario["email_funcionario"]) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($usuario["cpf_funcionario"]) ?>
                                </td>

                                <td>

                                    <?php if ($usuario["tipo_usuario"] === "Administrador"): ?>

                                        <span class="badge bg-danger">
                                            Administrador
                                        </span>

                                    <?php else: ?>

                                        <span class="badge bg-secondary">
                                            Operador
                                        </span>

                                    <?php endif; ?>

                                </td>

                                <td>
                                    <div class="d-flex gap-3">

                                        <button
                                            type="button"
                                            class="btn btn-link p-0 text-secondary"
                                            title="Visualizar"
                                            onclick="visualizarUsuario(
                                                '<?= htmlspecialchars($usuario["nome_funcionario"], ENT_QUOTES) ?>',
                                                '<?= htmlspecialchars($usuario["email_funcionario"], ENT_QUOTES) ?>',
                                                '<?= htmlspecialchars($usuario["cpf_funcionario"], ENT_QUOTES) ?>',
                                                '<?= htmlspecialchars($usuario["tipo_usuario"], ENT_QUOTES) ?>'
                                            )"
                                        >
                                            <i class="bi bi-eye fs-5"></i>
                                        </button>

                                        <form method="POST" action="cadastro_user.php" onsubmit="return confirmarExclusao()">
                                            <input
                                                type="hidden"
                                                name="excluir_usuario"
                                                value="<?= $usuario["id_funcionario"] ?>"
                                            >

                                            <button
                                                type="submit"
                                                class="btn btn-link p-0 text-danger"
                                                title="Excluir"
                                            >
                                                <i class="bi bi-trash fs-5"></i>
                                            </button>
                                        </form>

                                    </div>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

            <div id="semResultados" class="text-center text-muted py-4" style="display: none;">
                Nenhum usuário encontrado.
            </div>

        </div>
        
    </div>

</div>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<script>

const filtroId = document.getElementById("filtroId");
const filtroNome = document.getElementById("filtroNome");
const filtroTipo = document.getElementById("filtroTipo");
const linhas = document.querySelectorAll("#tabelaUsuarios tr");
const semResultados = document.getElementById("semResultados");

function filtrarUsuarios() {

    const id = filtroId.value.trim();
    const nome = filtroNome.value.trim().toLowerCase();
    const tipo = filtroTipo.value;

    let encontrados = 0;

    linhas.forEach(linha => {

        const linhaId = linha.dataset.id;
        const linhaNome = linha.dataset.nome;
        const linhaTipo = linha.dataset.tipo;

        const correspondeId =
            id === "" || linhaId === id;

        const correspondeNome =
            nome === "" || linhaNome.includes(nome);

        const correspondeTipo =
            tipo === "" || linhaTipo === tipo;

        if (
            correspondeId &&
            correspondeNome &&
            correspondeTipo
        ) {

            linha.style.display = "";
            encontrados++;

        } else {

            linha.style.display = "none";

        }

    });

    semResultados.style.display =
        encontrados === 0 ? "block" : "none";
}

filtroId.addEventListener("input", filtrarUsuarios);
filtroNome.addEventListener("input", filtrarUsuarios);
filtroTipo.addEventListener("change", filtrarUsuarios);

function confirmarExclusao() {
    return confirm("Tem certeza que deseja excluir este usuário?");
}

function visualizarUsuario(nome, email, cpf, tipo) {
    alert(
        "Nome: " + nome +
        "\nE-mail: " + email +
        "\nCPF: " + cpf +
        "\nTipo de usuário: " + tipo
    );
}

</script>

</html>
