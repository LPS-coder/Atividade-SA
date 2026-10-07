<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cadastro de Sensores e Trens</title>

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
                <a
                    href="cadastro_de_sensores_e_trens.php"
                    class="nav-link active"
                    aria-current="page"
                >
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
                <a href="relatorios.php" class="nav-link text-white">
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

                <strong>
                    Administrador
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
                    Gerencie a frota e os sensores IoT instalados.
                </p>

                <h1 class="fw-bold mb-0">
                    Cadastro de Sensores e Trens
                </h1>

            </div>

        </div>


        <div class="row g-4">


            <div class="col-lg-6">

                <div class="card shadow-sm border-0 rounded-4 p-3 h-100">

                    <div class="card-body">

                        <h4 class="fw-bold mb-4">

                            <i class="bi bi-broadcast text-danger me-2"></i>

                            Novo Sensor

                        </h4>


                        <div class="mb-3">

                            <label
                                for="sensorNome"
                                class="form-label"
                            >
                                Nome do sensor
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="sensorNome"
                            >

                        </div>


                        <div class="mb-3">

                            <label
                                for="sensorLocalizacao"
                                class="form-label"
                            >
                                Localização
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="sensorLocalizacao"
                            >

                        </div>


                        <div class="mb-3">

                            <label
                                for="sensorTipo"
                                class="form-label"
                            >
                                Tipo de dado monitorado
                            </label>

                            <select
                                class="form-select"
                                id="sensorTipo"
                            >

                                <option value="Velocidade" selected>
                                    Velocidade
                                </option>

                                <option value="Temperatura">
                                    Temperatura
                                </option>

                                <option value="Energia">
                                    Energia
                                </option>

                                <option value="Falha">
                                    Falha
                                </option>

                                <option value="Localização">
                                    Localização
                                </option>

                            </select>

                        </div>


                        <div class="mb-4">

                            <label
                                for="sensorTrem"
                                class="form-label"
                            >
                                Trem vinculado
                            </label>

                            <select
                                class="form-select"
                                id="sensorTrem"
                            >

                                <option
                                    value=""
                                    selected
                                    disabled
                                >
                                    Selecione
                                </option>

                                <option value="Locomotiva Alpha">
                                    Locomotiva Alpha (LOC-001)
                                </option>

                                <option value="Locomotiva Beta">
                                    Locomotiva Beta (LOC-002)
                                </option>

                                <option value="Locomotiva Gamma">
                                    Locomotiva Gamma (LOC-003)
                                </option>

                            </select>

                        </div>


                        <button
                            class="btn btn-danger w-100 py-2 d-flex align-items-center justify-content-center gap-2 fw-bold"
                            onclick="cadastrarSensor()"
                        >

                            <i class="bi bi-plus-lg"></i>

                            Cadastrar Sensor

                        </button>

                    </div>

                </div>

            </div>


            <div class="col-lg-6">

                <div class="card shadow-sm border-0 rounded-4 p-3 h-100">

                    <div class="card-body">

                        <h4 class="fw-bold mb-4">

                            <i class="bi bi-train-front-fill text-danger me-2"></i>

                            Novo Trem

                        </h4>


                        <div class="mb-3">

                            <label
                                for="tremNome"
                                class="form-label"
                            >
                                Nome
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="tremNome"
                            >

                        </div>


                        <div class="mb-3">

                            <label
                                for="tremCodigo"
                                class="form-label"
                            >
                                Código
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="tremCodigo"
                            >

                        </div>


                        <div class="mb-4">

                            <label
                                for="tremModelo"
                                class="form-label"
                            >
                                Modelo
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="tremModelo"
                            >

                        </div>


                        <button
                            class="btn btn-outline-dark w-100 py-2 d-flex align-items-center justify-content-center gap-2 fw-bold"
                            onclick="cadastrarTrem()"
                        >

                            <i class="bi bi-plus-lg"></i>

                            Cadastrar Trem

                        </button>

                    </div>

                </div>

            </div>

        </div>


        <div class="card mt-4 border-0 shadow-sm rounded-4 p-4">

            <h4 class="fw-bold mb-4">
                Sensores da Ferrovia
            </h4>


            <div class="table-responsive">

                <table class="table align-middle mb-0">

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


                    <tbody id="sensorsTable">

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8NlM6XUeP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"
    ></script>

    <script src="../scripts/script.js"></script>

</body>

</html>