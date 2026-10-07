<?php

$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "omnirail";
$porta = 3309;

$conn = new mysqli($host, $usuario, $senha, $banco, $porta);

if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados.");
}

$conn->set_charset("utf8mb4");
