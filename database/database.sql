CREATE DATABASE IF NOT EXISTS omnirail;

USE omnirail;

CREATE TABLE IF NOT EXISTS funcionario (
    id_funcionario INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome_funcionario VARCHAR(100) NOT NULL,
    cpf_funcionario VARCHAR(11) NOT NULL UNIQUE,
    email_funcionario VARCHAR(150) NOT NULL UNIQUE,
    senha_funcionario VARCHAR(255) NOT NULL,
    tipo_usuario ENUM('Administrador', 'Operador') NOT NULL DEFAULT 'Operador'
);

CREATE TABLE IF NOT EXISTS trens (
    id_trem INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome_trens VARCHAR(100) NOT NULL,
    codigo VARCHAR(20) NOT NULL UNIQUE,
    modelo VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS sensores (
    id_sensor INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome_sensores VARCHAR(100) NOT NULL,
    localizacao VARCHAR(150) NOT NULL,
    tipo_dado VARCHAR(50) NOT NULL,
    id_trem INT NOT NULL,
    FOREIGN KEY (id_trem) REFERENCES trens(id_trem)
);

CREATE TABLE IF NOT EXISTS relatorios (
    id_relatorio INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_funcionario INT NOT NULL,
    id_sensor INT NOT NULL,
    id_trem INT NOT NULL,
    data_geracao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    descricao TEXT,
    FOREIGN KEY (id_trem) REFERENCES trens(id_trem),
    FOREIGN KEY (id_sensor) REFERENCES sensores(id_sensor),
    FOREIGN KEY (id_funcionario) REFERENCES funcionario(id_funcionario)
);