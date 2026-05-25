**Aplicação dos Requisitos Funcionais e Regras de Negócio – OMNIRAIL**

O sistema ferroviário OMNIRAIL foi desenvolvido com foco em monitoramento inteligente, integração de sensores IoT e análise operacional em tempo real.

O projeto segue boas práticas de programação, usabilidade, segurança da informação e organização de dados.

Requisitos Funcionais
Login de Usuários

**O sistema deve permitir:**

Inserção de e-mail e senha;
Validação das credenciais no banco de dados;
Criação de sessão do usuário;
Redirecionamento ao dashboard administrativo;
Exibição de mensagem de erro caso o login seja inválido.
Dashboard Administrativo

**O sistema deve exibir:**

Nome do administrador autenticado;
Lista de sensores cadastrados;
Botões de acesso rápido;
Informações operacionais da ferrovia.
Cadastro de Sensores e Trens

**O sistema deve permitir:**

Cadastro de sensores;
Vinculação de sensores a trens;
Registro da localização e tipo de monitoramento.
Listagem de Sensores

**O sistema deve:**

Exibir sensores cadastrados;
Permitir visualização de detalhes;
Permitir exclusão de sensores.
Monitoramento em Tempo Real

**O sistema deve apresentar:**

Velocidade do trem;
Localização em mapa;
Consumo de energia;
Status operacional;
Histórico de falhas;
Gráficos analíticos.
Relatórios

**O sistema deve:**

Gerar relatórios operacionais;
Filtrar relatórios por período;
Filtrar relatórios por tipo de falha;
Exibir gráficos interativos.
Logout

**O sistema deve:**

Encerrar a sessão do usuário;
Redirecionar para a tela de login.
Regras de Negócio – OMNIRAIL

**Regra 1**
Todo sensor cadastrado deve estar obrigatoriamente vinculado a um trem.

**Regra 2**
Sensores que possuem dados registrados não podem ser excluídos do sistema.

**Regra 3**
O sistema deve simular dados em tempo real utilizando sensores IoT.

**Regra 4**
O status operacional do trem deve ser definido automaticamente conforme os dados recebidos.

**Regra 5**
Os relatórios devem ser gerados com base nos dados históricos armazenados no banco de dados.

**Regra 6**
Somente usuários autenticados podem acessar o dashboard administrativo.

**Regra 7**
O sistema deve armazenar informações de velocidade, localização, consumo e falhas operacionais.

**Regra 8**
As informações devem ser apresentadas de forma organizada, intuitiva e responsiva.

**Tecnologias Utilizadas – OMNIRAIL**
Frontend
HTML;
CSS;
JavaScript;
Bootstrap.

Backend
Banco de Dados
MySQL.
Bibliotecas e APIs
Banco de Dados – OMNIRAIL

**O banco de dados do sistema possui as seguintes tabelas:**

Usuários
id
email
senha
nome
Trens
id
nome
Sensores
id
nome
localização
tipo
trem_id
Dados
id
sensor_id
valor
tipo
timestamp
Relatórios
id
data_inicio
data_fim
tipo

**Cada tabela deve possuir pelo menos três registros para testes e validações do sistema.**

**Pesquisa sobre CRUD em PHP – Sistema OMNIRAIL**

CRUD é um conjunto de operações usadas para manipular dados em sistemas com banco de dados. A sigla significa:

* **Create** → Criar
* **Read** → Ler
* **Update** → Atualizar
* **Delete** → Excluir

No PHP, o CRUD é utilizado junto ao MySQL para cadastrar, visualizar, alterar e remover informações do sistema.

**Aplicação no OMNIRAIL**

**Create (Criar)**

Usado para cadastrar:

* Usuários;
* Sensores;
* Trens;
* Relatórios.

Exemplo: cadastro de sensores ferroviários.

**Read (Ler)**

Usado para visualizar:

* Sensores cadastrados;
* Dados em tempo real;
* Relatórios e gráficos.

Exemplo: tela de monitoramento da ferrovia.

**Update (Atualizar)**

Usado para alterar:

* Status dos sensores;
* Dados operacionais;
* Informações dos trens.

Exemplo: mudança do status para “Alerta” ou “Falha”.

**Delete (Excluir)**

Usado para remover:

* Sensores;
* Registros incorretos;
* Dados antigos.

Regra do sistema: sensores com dados registrados não podem ser excluídos.

**Conclusão**

O CRUD é fundamental no sistema OMNIRAIL, pois permite gerenciar todas as informações da ferrovia de forma organizada, segura e eficiente, utilizando PHP e MySQL para o controle dos dados em tempo real.


---------------//-------------------------------//--------------------------------//---------------------------//-------------------------

**PESQUISA SOBRE O SCRUM E SUAS FUNCIONALIDADES**

**O que é o SCRUM**
 
 É uma estrutura de trabalho para gerenciar tarefas complexas em tarefas menores, ajudando os devs envolvidos a conseguir fazer um melhor gerenciamento de tempo para melhor desenvolvimento do site, eles geram valor ao tempo de trabalho do funcionário, adaptando mudanças pequenas, mas necessárias para o desenvolvimento. 

 **FUNCIONABILIDADES**

 **Product Owner**
 
 É o responsável por maximizar o valor do produto e o trabalho da equipe de desenvolvimento. Ele atua como o principal elo entre o negócio, os clientes e o time, garantindo que o produto atenda às necessidades reais de quem vai utilizá-lo, sendo mais didático, ele funciona como uma secretária, que prioriza o que primeiro deve ser feito do código, juntamente do que o usuário precisa receber.

 **Scrum Master**

 O Scrum Master é o facilitador de metodologias ágeis em uma equipe. Seu papel principal é garantir que o grupo siga os valores, princípios e práticas do framework Scrum. Ele atua como um líder servidor e coach, ajudando a remover obstáculos para que os desenvolvedores foquem na execução.

**Development Team**

 É o grupo de profissionais responsáveis na faixa de 6 a 10 profissionais por criar o incremento do produto a cada Sprint. Eles são multifuncionais e autogerenciáveis, possuindo todas as habilidades técnicas necessárias para transformar os requisitos do Product Owner em um produto pronto e utilizável,  eles decidem como o trabalho será realizado, sem ordens externas de gerentes ou líderes de projeto.

 **Sprint**

 O Sprint é o coração da metodologia Scrum. Trata-se de um ciclo de trabalho com prazo fixo (de 1 semana a no máximo 1 mês) no qual a equipe desenvolve e entrega um incremento do produto pronto e utilizável.
 Cada Sprint funciona como um "mini-projeto" independente. Durante esse período, o time transforma as prioridades do backlog em algo com valor real para o negócio. Uma nova Sprint começa imediatamente após o término da anterior, sem interrupções.

 **Sprint Planning**

 O Sprint Planning é a reunião que inicia cada ciclo de trabalho (Sprint) no framework Scrum. O objetivo é alinhar a equipe e definir o que será feito durante aquele período e como esse trabalho será entregue.

**Daily Scrum**

 O Daily Scrum (ou reunião diária) é um evento rápido de 15 minutos realizado todos os dias úteis por desenvolvedores para alinhar o trabalho, inspecionar o progresso em direção à Meta da Sprint e adaptar o planejamento das próximas 24 horas.
 O principal objetivo dessa cerimônia é manter a equipe inteira sincronizada e identificar quaisquer impedimentos logo cedo para manter o foco e a agilidade, a dinâmica do Daily Scrum costuma girar em torno de três perguntas fundamentais.

**O Sprint Review**

 O Sprint Review é uma reunião colaborativa que ocorre no final de cada Sprint. Seu objetivo principal é inspecionar o incremento de trabalho concluído e coletar feedback das partes interessadas para orientar os próximos passos do projeto.
 O evento foca na demonstração prática, a equipe apresenta o trabalho realizado rodando o produto ou sistema, e não apenas através de apresentações de slides.

**Sprint Retrospective**

 O Sprint Retrospective é uma cerimônia do Scrum em que a equipe avalia o último ciclo de trabalho para identificar pontos fortes e oportunidades de melhoria. O foco não é o produto final, mas sim os processos, as ferramentas, a comunicação e as interações entre os membros.
 Inspecionar o trabalho recente e criar um plano de melhorias concretas para implementar na próxima Sprint, promovendo a melhoria contínua.

 **Benefícios do uso do SCRUM em projetos de software.**

 O scrum ajuda os projetos de software a serem mais organizados e eficientes, melhorando a comunicação entre a equipe e dividindo o trabalho em pequenas etapas.Isso facilita acompanhar o progresso, corrigir problemas mais rápido e manter todos focados nos objetivos do projeto.
 Além disso, o método permite adaptar mudanças sem grandes dificuldades, tornando o desenvolvimento mais flexível. Como partes do sistema são entregues aos poucos, o cliente pode acompanhar o resultado e dar opiniões durante o processo, ajudando a criar um software mais útil e de melhor qualidade.
