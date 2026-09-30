CREATE DATABASE omnirail;

USE omnirail;

CREATE TABLE funcionario (
    id_funcionario INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome_funcionario VARCHAR(100) NOT NULL,
    cpf_funcionario VARCHAR(11) NOT NULL UNIQUE,
    email_funcionario VARCHAR(150) NOT NULL UNIQUE,
    senha_funcionario VARCHAR(255) NOT NULL,
);

CREATE TABLE trens (
    id_trem INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome_trens VARCHAR(100) NOT NULL,
    codigo VARCHAR(20) NOT NULL UNIQUE,
    modelo VARCHAR(100) NOT NULL
);

CREATE TABLE sensores (
    id_sensor INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome_sensores VARCHAR(100) NOT NULL,
    localizacao VARCHAR(150) NOT NULL,
    tipo_dado VARCHAR(50) NOT NULL,
    id_trem INT NOT NULL,
    FOREIGN KEY (id_trem) REFERENCES trens(id_trem)
);

CREATE TABLE usuario (
    id_usuario INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome_usuario VARCHAR(100) NOT NULL,
    email_usuario VARCHAR(150) NOT NULL UNIQUE,
    cpf_usuario VARCHAR(11) NOT NULL UNIQUE,
    senha_usuario VARCHAR(255) NOT NULL,
);
