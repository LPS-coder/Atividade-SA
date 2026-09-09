CREATE DATABASE omnirail;

USE omnirail;

CREATE TABLE funcionarios (
    id_funcionario INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    cpf VARCHAR(11) NOT NULL UNIQUE,
    email VARCHAR(150) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,

);

CREATE TABLE trens (
    id_trem INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
    nome VARCHAR(100) NOT NULL,
    codigo VARCHAR(20) NOT NULL UNIQUE,
    modelo VARCHAR(100) NOT NULL,
    
);

CREATE TABLE sensores (
    id_sensor INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    localizacao VARCHAR(150) NOT NULL,
    tipo_dado VARCHAR(50) NOT NULL,
    id_trem INT NOT NULL,

    FOREIGN KEY (id_trem)
        REFERENCES trens(id_trem)
);