<?php

ini_set("session.use_strict_mode", "1");
ini_set("session.use_only_cookies", "1");

session_set_cookie_params([
    "httponly" => true,
    "secure" => !empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off",
    "samesite" => "Lax"
]);

session_start();

if (isset($_SESSION["id_funcionario"])) {
    header("Location: dashboard.php");
    exit;
}

require_once "../infra/conexao.php";

$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? "");
    $senha = $_POST["senha"] ?? "";

    if ($email === "" || $senha === "") {

        $erro = "Preencha todos os campos.";

    } else {

        $sql = "SELECT
                    id_funcionario,
                    nome_funcionario,
                    email_funcionario,
                    senha_funcionario,
                    tipo_usuario
                FROM funcionario
                WHERE email_funcionario = ?";

        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("Erro na consulta: " . $conn->error);
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();

        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 1) {

            $usuario = $resultado->fetch_assoc();

            if (password_verify($senha, $usuario["senha_funcionario"])) {

                session_regenerate_id(true);

                $_SESSION["id_funcionario"] = $usuario["id_funcionario"];
                $_SESSION["nome_funcionario"] = $usuario["nome_funcionario"];
                $_SESSION["email_funcionario"] = $usuario["email_funcionario"];
                $_SESSION["tipo_usuario"] = $usuario["tipo_usuario"];

                header("Location: dashboard.php");
                exit;

            } else {

                $erro = "E-mail ou senha incorretos!";

            }

        } else {

            $erro = "E-mail ou senha incorretos!";

        }

        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>OmniRail - Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../assets/styles/style.css">

</head>

<body id="body-login" class="d-flex justify-content-center align-items-center vh-100" style="background-color: #0d0d16;">

    <div class="login-box p-4 shadow" style="background-color: #e9ecef; border-radius: 10px; width: 350px;">

        <img src="../assets/images/omnirail.png" alt="Logo" class="img-fluid mb-3">

        <p class="subtitle text-muted small">
            Plataforma de Monitoramento Ferroviário
        </p>

        <form id="loginForm" method="POST" action="login.php">

            <div class="mb-3 text-start">

                <label class="form-label">
                    E-mail
                </label>

                <input
                    type="email"
                    class="form-control"
                    id="email"
                    name="email"
                    required>

            </div>

            <div class="mb-4 text-start">

                <label class="form-label">
                    Senha
                </label>

                <input
                    type="password"
                    class="form-control"
                    id="senha"
                    name="senha"
                    required>

            </div>

            <?php if ($erro !== ""): ?>

                <p id="erro" style="color: red;">
                    <?php echo htmlspecialchars($erro); ?>
                </p>

            <?php endif; ?>

            <button type="submit" class="btn btn-danger w-100">
                Entrar
            </button>

        </form>

    </div>

</body>

</html>