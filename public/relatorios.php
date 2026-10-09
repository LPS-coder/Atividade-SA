<?php
require_once "../infra/seguranca.php";

exigirLogin();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios</title>

    <link rel="stylesheet" href="../assets/styles/style.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
        crossorigin="anonymous"
    >
</head>

<body id="body-dashboard">

    <div id="sidebar" class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark">

        <a href="dashboard.php" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
            <img
                src="../assets/images/logo.png"
                alt="Logo"
                width="40"
                height="40"
                class="me-2"
            >
            <span class="fs-4">OMNIRAIL</span>
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
                <a href="relatorios.php" class="nav-link active" aria-current="page">
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

                <strong>Administrador</strong>
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
                    Gere e consulte análises da operação ferroviária.
                </p>

                <h1 class="fw-bold">
                    Relatórios Operacionais
                </h1>
            </div>

        </div>


        <div class="row g-4">

            <div class="col-lg-4">

                <div class="card shadow-sm border-0 rounded-4 p-3 h-100">

                    <div class="card-body">

                        <h4 class="fw-bold mb-4">
                            Novo Relatório
                        </h4>


                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Título
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                placeholder="Digite o título do relatório"
                            >

                        </div>


                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Data início
                            </label>

                            <input
                                type="date"
                                class="form-control"
                            >

                        </div>


                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Data fim
                            </label>

                            <input
                                type="date"
                                class="form-control"
                            >

                        </div>


                        <div class="mb-4">

                            <label class="form-label fw-semibold">
                                Tipo
                            </label>

                            <select class="form-select">

                                <option selected disabled>
                                    Selecione
                                </option>

                                <option>
                                    Trens cadastrados
                                </option>

                                <option>
                                    Sensores
                                </option>

                                <option>
                                    Operacional
                                </option>

                                <option>
                                    Falhas
                                </option>

                                <option>
                                    Energia
                                </option>

                            </select>

                        </div>


                        <button
                            type="button"
                            class="btn btn-danger w-100 py-2 fw-bold"
                        >
                            <i class="bi bi-file-earmark-bar-graph me-2"></i>
                            Gerar Relatório
                        </button>

                    </div>

                </div>

            </div>


            <div class="col-lg-8">

                <div class="card shadow-sm border-0 rounded-4 p-3 h-100">

                    <div class="card-body">

                        <h4 class="fw-bold mb-4">
                            Tendências por Tipo de Leitura
                        </h4>


                        <div class="d-flex align-items-center justify-content-center h-75">

                            <div class="text-center">

                                <i class="bi bi-bar-chart-fill text-danger display-3"></i>

                                <h5 class="fw-bold mt-3 mb-2">
                                    Velocidade
                                </h5>

                                <span class="badge bg-danger">
                                    Total: 1000
                                </span>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="card mt-4 border-0 shadow-sm rounded-4 p-4">

            <h4 class="fw-bold mb-4">
                Relatórios Gerados
            </h4>


            <div class="row g-4">


                <div class="col-md-6">

                    <div class="border rounded-4 p-3">

                        <div class="d-flex align-items-center">

                            <i class="bi bi-file-earmark-text fs-3 text-danger me-3"></i>

                            <div class="flex-grow-1">

                                <div class="fw-bold">
                                    Relatório Mensal Operacional
                                </div>

                                <small class="text-muted">
                                    2026-04-01 - 2026-05-04
                                </small>

                            </div>


                            <button
                                type="button"
                                class="btn btn-sm text-danger"
                                data-bs-toggle="modal"
                                data-bs-target="#modalExcluir"
                            >
                                <i class="bi bi-trash"></i>
                            </button>

                        </div>


                        <span class="badge bg-light text-dark mt-2">
                            Operacional
                        </span>

                    </div>

                </div>


                <div class="col-md-6">

                    <div class="border rounded-4 p-3">

                        <div class="d-flex align-items-center">

                            <i class="bi bi-file-earmark-text fs-3 text-danger me-3"></i>

                            <div class="flex-grow-1">

                                <div class="fw-bold">
                                    Análise de Falhas
                                </div>

                                <small class="text-muted">
                                    2026-04-19 - 2026-05-04
                                </small>

                            </div>


                            <button
                                type="button"
                                class="btn btn-sm text-danger"
                                data-bs-toggle="modal"
                                data-bs-target="#modalExcluir"
                            >
                                <i class="bi bi-trash"></i>
                            </button>

                        </div>


                        <span class="badge bg-light text-dark mt-2">
                            Operacional
                        </span>

                    </div>

                </div>


            </div>

        </div>

    </div>


    <div
        class="modal fade"
        id="modalExcluir"
        tabindex="-1"
        aria-hidden="true"
    >

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">

                <div class="modal-body text-center p-4">

                    <h5 class="fw-bold mb-3">
                        Deseja mesmo excluir?
                    </h5>

                    <p class="text-muted mb-4">
                        Essa ação não poderá ser desfeita.
                    </p>


                    <div class="d-flex gap-2">

                        <button
                            type="button"
                            class="btn btn-light w-100"
                            data-bs-dismiss="modal"
                        >
                            Cancelar
                        </button>


                        <button
                            type="button"
                            class="btn btn-danger w-100"
                        >
                            Excluir
                        </button>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8NlM6XUeP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"
    ></script>

</body>
</html>