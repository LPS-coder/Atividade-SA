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
