# OMNIRAIL — Sistema de Monitoramento Ferroviário Inteligente

> Projeto desenvolvido para a Situação de Aprendizagem (Ferrorama) — Curso Técnico em Desenvolvimento de Sistemas.

O **OMNIRAIL** é uma solução focada no monitoramento em tempo real de frotas ferroviárias. O sistema realiza a captura simulada de dados de sensores IoT (velocidade, localização, consumo de energia e falhas operacionais), permitindo a gestão centralizada da operação, manutenção preditiva e análise de desempenho.

---

##  Tecnologias Utilizadas

* **Front-end:** HTML5, CSS3, JavaScript, Bootstrap 5, Chart.js
* **Back-end:** PHP 8.x
* **Banco de Dados:** MySQL / MariaDB (Servidor local XAMPP)
* **Gestão e Versionamento:** Git, GitHub, GitHub Projects (Kanban)

---

##  Requisitos e Regras de Negócio

* **Autenticação Segura:** Acesso restrito ao dashboard administrativo mediante sessão ativa (**RN6**).
* **Vínculo Obrigatório:** Todo sensor cadastrado é associado a um trem específico (**RN1**).
* **Integridade de Dados:** Bloqueio de exclusão para sensores que possuem histórico armazenado no banco (**RN2**).
* **Automação IoT:** Cálculo automático do status operacional (Normal, Alerta, Falha) com base nos dados recebidos (**RN4**).
* **Relatórios Analíticos:** Consultas filtradas por período e tipo de falha com gráficos interativos (**RN5**, **RN7**).

---

##  Estrutura de Pastas do Projeto

```text
Atividade-SA/
├── assets/          # Arquivos CSS, JS e imagens do sistema
├── config/          # Arquivo de conexão com o Banco de Dados (database.php)
├── includes/        # Cabeçalho, rodapé e validação de sessão (auth.php)
├── views/           # Interface de usuário (Login, Dashboard, Sensores, Relatórios)
├── docs/            # Pesquisas teóricas (SCRUM, XAMPP, CRUD PHP) e padrões do projeto
├── database.sql     # Script DDL e DML de criação e população do banco de dados
└── README.md        # Documentação principal
