<?php
require_once "../infra/seguranca.php";

exigirLogin();
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoramento</title>
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
                <a href="monitoramento.php" class="nav-link active">
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
                Dados ao vivo da locomotiva LOC-001
            </p>
            <h1 class="fw-bold">
                Monitoramento 
            </h1>
        </div>

        <a class="btn btn-novo-sensor px-4 py-2 rounded-3 fw-bold">
            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="currentColor" class="bi bi-circle-fill" viewBox="0 0 16 16">
  <circle cx="8" cy="8" r="7"/>
</svg>
            Normal
        </a>

    </div>
    <div class="row g-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-secondary mb-1">
                            Velocidade
                        </p>
                        <h1 class="fw-bold">
                           
                        </h1>
                    </div>
                    <div style="color: red;">
    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-clock-fill" viewBox="0 0 16 16">
      <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71z"/>
    </svg>
</div>
</svg>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-secondary mb-1">
                            Consumo 
                        </p>
                        <h1 class="fw-bold">
                       
                        </h1>
                    </div>
                     <div style="color: rgb(255, 208, 0);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-lightning-fill" viewBox="0 0 16 16">
  <path d="M5.52.359A.5.5 0 0 1 6 0h4a.5.5 0 0 1 .474.658L8.694 6H12.5a.5.5 0 0 1 .395.807l-7 9a.5.5 0 0 1-.873-.454L6.823 9.5H3.5a.5.5 0 0 1-.48-.641z"/>
</svg>
</div>
                </div>
            </div>
        </div>
                <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>


                        <p class="text-secondary mb-1">
                            Temperatura 
                        </p>
                        <h1 class="fw-bold">

                        </h1>
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
                            Falhas(total)
                        </p>
                        <h1 class="fw-bold">
                       
                        </h1>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16">
  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
  <path d="m10.97 4.97-.02.022-3.473 4.425-2.093-2.094a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05"/>
</svg>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4 border-0 shadow-sm rounded-4 p-4">
        
            <thead>
               
            </tbody>
        </table>
    </div>

 <div class="card mt-4 border-0 shadow-sm rounded-4 p-4">

    <div class="row g-4">

        
        <div class="col-lg-8">
            <div class="border rounded-4 p-3 bg-light h-100">

                <h4 class="fw-bold mb-3">
                    Velocidade e Temperatura
                </h4>

            

            </div>
        </div>

        
        <div class="col-lg-4">
            <div class="border rounded-4 p-3 bg-light h-100">

                <h4 class="fw-bold mb-3">
                    
                    Localização
                </h4>

                

            </div>
        </div>

        
        <div class="col-12">
            <div class="border rounded-4 p-3 bg-light">

                <h4 class="fw-bold mb-3">
                    Consumo de Energia
                </h4>

              
            </div>
        </div>

    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

    new Chart(document.getElementById('graficoLinha'), {
        type: 'line',

        data: {
            labels: ['57:35','57:41','57:47','57:53','57:59','58:05','58:11','58:17','58:23','58:29'],

            datasets: [
                {
                    label: 'Velocidade',
                    data: [60,95,70,65,120,80,60,100,85,90],
                    borderColor: 'red',
                    tension: 0.4
                },

                {
                    label: 'Temperatura',
                    data: [85,70,65,80,75,105,62,88,64,63],
                    borderColor: 'orange',
                    tension: 0.4
                }
            ]
        },

        options: {
            responsive: true
        }
    });

    new Chart(document.getElementById('graficoEnergia'), {
        type: 'line',

        data: {
            labels: ['11:38','11:41','11:44','11:47','11:50','11:53','11:56','11:59','12:02','12:05','12:08','12:11'],

            datasets: [{
                label: 'Energia',

                data: [450,560,420,500,540,530,560,470,550,440,560,540],

                borderColor: 'red',

                backgroundColor: 'rgba(255,0,0,0.1)',

                fill: true,

                tension: 0.4
            }]
        },

        options: {
            responsive: true
        }
    });
</script>

<link rel="stylesheet"
href="https://unpkg.com/leaflet/dist/leaflet.css"/>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    
    var map = L.map('map').setView([-23.5505, -46.6333], 11);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    L.marker([-23.5505, -46.6333])
        .addTo(map)
        .bindPopup('Locomotiva LOC-001')
        .openPopup();
</script>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</html>
