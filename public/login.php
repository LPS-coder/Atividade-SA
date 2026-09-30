<?php

session_start();

require_once "../database/conexao.php";

$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = $_POST["email"];
    $senha = $_POST["senha"];

    $sql = "SELECT * FROM usuario WHERE email_usuario = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {

        $usuario = $resultado->fetch_assoc();

        if (password_verify($senha, $usuario["senha_usuario"])) {

            $_SESSION["id_usuario"] = $usuario["id_usuario"];
            $_SESSION["nome_usuario"] = $usuario["nome_usuario"];
            $_SESSION["email_usuario"] = $usuario["email_usuario"];

            header("Location: dashboard.html");
            exit;

        } else {

            $erro = "E-mail ou senha incorretos!";

        }

    } else {

        $erro = "E-mail ou senha incorretos!";

    }

    $stmt->close();
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

            <?php if ($erro != ""): ?>

                <p id="erro" style="color: red;">
                    <?php echo $erro; ?>
                </p>

            <?php endif; ?>

            <button type="submit" class="btn btn-danger w-100">
                Entrar
            </button>

        </form>

    </div>

</body>

</html>