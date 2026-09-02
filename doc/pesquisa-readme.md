# OMNIRAIL

## Sobre o projeto

O OMNIRAIL é um sistema ferroviário desenvolvido com foco em monitoramento inteligente, integração de sensores IoT e análise operacional em tempo real. O projeto segue boas práticas de programação, usabilidade, segurança da informação e organização de dados, centralizando o cadastro de trens e sensores, a coleta de dados operacionais e a geração de relatórios para acompanhamento administrativo da malha ferroviária.

## Funcionalidades do sistema

**Login de usuários**

O acesso ao sistema é feito por e-mail e senha, com validação das credenciais no banco de dados. Após a autenticação, é criada uma sessão do usuário e ele é redirecionado ao dashboard administrativo. Tentativas de login inválidas resultam em mensagem de erro.

**Dashboard administrativo**

Área restrita a usuários autenticados, exibindo o nome do administrador logado, a lista de sensores cadastrados, botões de acesso rápido às principais funções do sistema e informações operacionais gerais da ferrovia.

**Cadastro de sensores e trens**

Permite o cadastro de sensores e sua vinculação obrigatória a um trem, incluindo o registro da localização e do tipo de monitoramento realizado por cada sensor.

**Listagem de sensores**

Exibe os sensores já cadastrados, com possibilidade de visualizar detalhes individuais e de excluir sensores que não possuam dados registrados.

**Monitoramento em tempo real**

Apresenta a velocidade do trem, sua localização em mapa, o consumo de energia, o status operacional atual, o histórico de falhas e gráficos analíticos com os dados coletados.

**Relatórios**

Geração de relatórios operacionais com filtros por período e por tipo de falha, apresentados também em formato de gráficos interativos.

**Logout**

Encerra a sessão ativa do usuário e o redireciona à tela de login.

## Regras de negócio

Todo sensor cadastrado deve estar obrigatoriamente vinculado a um trem.

Sensores que possuem dados registrados não podem ser excluídos do sistema.

O sistema simula dados em tempo real a partir dos sensores IoT.

O status operacional do trem é definido automaticamente conforme os dados recebidos pelos sensores.

Os relatórios são gerados com base nos dados históricos armazenados no banco de dados.

O dashboard administrativo é acessível apenas a usuários autenticados.

O sistema armazena informações de velocidade, localização, consumo de energia e falhas operacionais.

As informações são apresentadas de forma organizada, intuitiva e responsiva.

## Tecnologias utilizadas

Frontend: HTML, CSS, JavaScript e Bootstrap.

Backend: PHP.

Banco de dados: MySQL.

## Estrutura do banco de dados

O banco de dados é composto pelas tabelas Usuários (id, email, senha, nome), Trens (id, nome), Sensores (id, nome, localização, tipo, trem_id), Dados (id, sensor_id, valor, tipo, timestamp) e Relatórios (id, data_inicio, data_fim, tipo). Cada tabela deve conter ao menos três registros para fins de teste e validação do sistema.

## CRUD no OMNIRAIL

CRUD é o conjunto de operações usadas para manipular dados em sistemas com banco de dados: Create (criar), Read (ler), Update (atualizar) e Delete (excluir). No OMNIRAIL, essas operações são implementadas em PHP integrado ao MySQL para cadastrar, visualizar, alterar e remover informações do sistema.

O Create é usado no cadastro de usuários, sensores, trens e relatórios, como no cadastro de sensores ferroviários. O Read é responsável pela visualização dos sensores cadastrados, dos dados coletados em tempo real e dos relatórios e gráficos gerados, como na tela de monitoramento da ferrovia. O Update permite a alteração do status dos sensores, dos dados operacionais e das informações dos trens, como na mudança de status de um sensor para "Alerta" ou "Falha". O Delete remove sensores, registros incorretos e dados antigos, respeitando a regra de que sensores com dados registrados não podem ser excluídos.

O CRUD é fundamental no OMNIRAIL, pois permite gerenciar todas as informações da ferrovia de forma organizada, segura e eficiente, utilizando PHP e MySQL para o controle dos dados em tempo real.

## Ambiente de desenvolvimento

O projeto é desenvolvido em ambiente local por meio do XAMPP, um pacote de software que reúne as ferramentas essenciais para criar um servidor local de testes, permitindo desenvolver e validar o sistema sem a necessidade de publicá-lo na internet.

O Apache atua como servidor web do XAMPP, responsável por receber as requisições do navegador e exibir as páginas construídas em PHP, servindo de base para a execução da aplicação no ambiente local. O PHP é a linguagem utilizada para dar dinamismo ao sistema, viabilizando o login, os cadastros, os formulários e as demais funções interativas do OMNIRAIL. O MariaDB/MySQL é o banco de dados responsável por armazenar as informações de usuários, sensores, trens, dados coletados e relatórios, permitindo que o sistema grave e consulte essas informações com facilidade. O phpMyAdmin complementa o ambiente como ferramenta visual de gerenciamento do banco de dados, possibilitando a criação de tabelas, a edição de registros e a execução de consultas sem a necessidade de escrever comandos diretamente. O painel de controle do XAMPP centraliza a inicialização e o encerramento desses serviços, simplificando a organização do ambiente de desenvolvimento.

O uso do XAMPP no desenvolvimento do OMNIRAIL agiliza os testes do sistema, evita a configuração separada de cada ferramenta, permite o trabalho offline e possibilita corrigir problemas antes de qualquer publicação, tornando o processo de desenvolvimento mais seguro e organizado.

## Metodologia de trabalho

O desenvolvimento do OMNIRAIL segue o framework Scrum, uma estrutura de trabalho voltada à divisão de tarefas complexas em etapas menores, favorecendo o gerenciamento do tempo da equipe e a entrega contínua de valor ao longo do projeto, com adaptação a mudanças pequenas e necessárias durante o desenvolvimento.

O Product Owner é o responsável por maximizar o valor do produto e do trabalho da equipe de desenvolvimento, atuando como elo entre o negócio, os clientes e o time, e priorizando o que deve ser desenvolvido de acordo com as necessidades reais de quem vai utilizar o sistema. O Scrum Master é o facilitador das práticas ágeis da equipe, garantindo que o grupo siga os valores e princípios do Scrum e atuando na remoção de obstáculos para que o time possa focar na execução do trabalho. O Development Team é o grupo multifuncional e autogerenciável responsável por transformar os requisitos definidos pelo Product Owner em incrementos do produto a cada Sprint, decidindo internamente como o trabalho será realizado.

O Sprint é o ciclo de trabalho de prazo fixo em que a equipe desenvolve e entrega um incremento pronto e utilizável do sistema, funcionando como um mini-projeto independente dentro do desenvolvimento do OMNIRAIL. O Sprint Planning é a reunião que inicia cada Sprint, alinhando a equipe sobre o que será feito no período e de que forma esse trabalho será entregue. O Daily Scrum é a reunião diária e rápida realizada pelos desenvolvedores para alinhar o andamento das tarefas, inspecionar o progresso em direção à meta da Sprint e ajustar o planejamento das próximas 24 horas, mantendo a equipe sincronizada e identificando impedimentos logo no início. O Sprint Review ocorre ao final de cada Sprint, com a demonstração prática do incremento concluído e a coleta de feedback das partes interessadas para orientar os próximos passos do projeto. O Sprint Retrospective encerra o ciclo com a avaliação da equipe sobre processos, ferramentas e comunicação, gerando um plano de melhorias concretas para a Sprint seguinte.

O acompanhamento das tarefas do OMNIRAIL é feito por meio de um quadro Kanban no GitHub, que organiza visualmente o backlog e o andamento de cada funcionalidade do sistema ao longo das Sprints.

O uso do Scrum no desenvolvimento do OMNIRAIL contribui para um projeto mais organizado e eficiente, melhorando a comunicação da equipe e dividindo o trabalho em etapas menores, o que facilita o acompanhamento do progresso e a correção rápida de problemas. A metodologia também permite adaptar mudanças com mais facilidade, tornando o desenvolvimento mais flexível, já que as entregas ocorrem de forma incremental e possibilitam acompanhamento constante ao longo do projeto.