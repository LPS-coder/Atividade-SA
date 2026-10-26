<?php
require_once "../infra/seguranca.php";

exigirLogin();
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../assets/styles/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body id="body-dashboard">
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
                <a href="dashboard.php" class="nav-link active">
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
                <a href="cadastro_user.php" class="nav-link text-white">
                    <i class="bi bi-people me-2"></i>
                    Cadastrados
                </a>
            </li>

            <li>
                <a href="rotas.php" class="nav-link text-white">
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

   <div class="main">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <p class="text-secondary mb-0">
                Bem-vindo de volta,
            </p>
            <h1 class="fw-bold">
                <strong>
                <?= htmlspecialchars($_SESSION["nome_funcionario"] ?? "Usuário") ?>
</strong>
            </h1>
        </div>

        <a href="../public/cadastro_de_sensores_e_trens.php" class="btn btn-novo-sensor px-4 py-2 rounded-3 fw-bold">
            <i class="bi bi-plus-lg me-2"></i>
            Novo Sensor
        </a>

    </div>
    <div class="row g-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-secondary mb-1">
                            Trens
                        </p>
                        
                    </div>
                    <i class="bi bi-train-front-fill text-danger fs-1"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-secondary mb-1">
                            Sensores
                        </p>
                       
                    </div>
                    <i class="bi bi-broadcast text-success fs-1"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-secondary mb-1">
                            Leituras
                        </p>
                        
                    </div>
                    <i class="bi bi-activity text-warning fs-1"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-secondary mb-1">
                            Alertas
                        </p>
                        
                    </div>
                    <i class="bi bi-exclamation-triangle text-danger fs-1"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4 border-0 shadow-sm rounded-4 p-4">
        <h4 class="fw-bold mb-4">
            Sensores Cadastrados
        </h4>
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Localização</th>
                    <th>Tipo</th>
                    <th>Trem</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>
    </div>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</html>
