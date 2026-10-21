CREATE DATABASE IF NOT EXISTS omnirail;
USE omnirail;

CREATE TABLE IF NOT EXISTS funcionario (
    id_funcionario INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome_funcionario VARCHAR(100) NOT NULL,
    cpf_funcionario VARCHAR(11) NOT NULL UNIQUE,
    email_funcionario VARCHAR(150) NOT NULL UNIQUE,
    senha_funcionario VARCHAR(255) NOT NULL,
    tipo_usuario ENUM('ADMINISTRADOR','USUARIO','OPERARIO')
        NOT NULL DEFAULT 'USUARIO'
);

-- Administrador padrão
INSERT INTO funcionario (
    nome_funcionario,
    cpf_funcionario,
    email_funcionario,
    senha_funcionario,
    tipo_usuario
)
SELECT
    'Administrador OMNIRAIL',
    '12345678967',
    'omnirail@gmail.com',
    'COLE_AQUI_O_HASH_GERADO',
    'ADMINISTRADOR'
WHERE NOT EXISTS (
    SELECT 1
    FROM funcionario
    WHERE email_funcionario = 'omnirail@gmail.com'
);

CREATE TABLE IF NOT EXISTS trens (
    id_trem INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome_trens VARCHAR(100) NOT NULL,
    codigo VARCHAR(20) NOT NULL UNIQUE,
    modelo VARCHAR(100) NOT NULL,
    id_funcionario INT NOT NULL,
    FOREIGN KEY (id_funcionario)
        REFERENCES funcionario(id_funcionario)
);

CREATE TABLE IF NOT EXISTS sensores (
    id_sensor INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome_sensores VARCHAR(100) NOT NULL,
    localizacao VARCHAR(150) NOT NULL,
    tipo_dado VARCHAR(50) NOT NULL,
    id_trem INT NOT NULL,
    FOREIGN KEY (id_trem)
        REFERENCES trens(id_trem)
);

CREATE TABLE IF NOT EXISTS relatorios (
    id_relatorio INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_funcionario INT NOT NULL,
    id_sensor INT NOT NULL,
    id_trem INT NOT NULL,
    data_geracao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    descricao TEXT,
    FOREIGN KEY (id_trem)
        REFERENCES trens(id_trem),
    FOREIGN KEY (id_sensor)
        REFERENCES sensores(id_sensor),
    FOREIGN KEY (id_funcionario)
        REFERENCES funcionario(id_funcionario)
);

CREATE TABLE IF NOT EXISTS rotas (
    id_rota INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome_rota VARCHAR(100) NOT NULL,
    descricao_rota VARCHAR(500) NOT NULL,
    distancia_km DECIMAL(8,2) NOT NULL,
    tempo_previsto_min INT NOT NULL,
    data_criacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    id_trem INT NOT NULL,
    UNIQUE KEY uq_rotas_nome_rota (nome_rota),
    CONSTRAINT fk_rotas_trens
        FOREIGN KEY (id_trem)
        REFERENCES trens(id_trem)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
);