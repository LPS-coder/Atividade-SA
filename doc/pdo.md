**O que é o PDO**

O PDO (PHP Data Object) é uma extensão da linguagem PHP para acesso a banco de dados. É orientado a objetos, possuiu diferemtes recursos considerado importantes e suporta diversos mecanismos de banco de dados.

**Para que ele é utilizado no PHP**

Ele é utilizada para conectar, consultar e gerenciar múltiplos bancos de dados de forma robusta e padronizada, oferecendo proteção nativa contra ataques de SQL Injection por meio de consultas parametrizadas.

**Como funciona uma conexão utilizando PDO;**



**Quais são suas principais características;** 



**Diferenças entre PDO e MySQLi;** 

Suporte a Bancos de Dados:
PDO: Funciona com mais de 10 sistemas de bancos de dados diferentes, como MySQL, PostgreSQL, Oracle e SQLite.

MySQLi: Funciona apenas com bancos de dados MySQL e MariaDB.

Estilo de Programação (Sintaxe):
PDO: Usa exclusivamente o modelo Orientado a Objetos.

MySQLi: Oferece suporte tanto ao estilo Orientado a Objetos quanto ao estilo Procedural (com funções tradicionais).

Parâmetros nas Consultas:
PDO: Suporta parâmetros nomeados (ex: NOME) além dos posicionais (?), o que facilita a leitura do código.

MySQLi: Suporta apenas parâmetros posicionais (?) numerados. 

Desempenho:
MySQLi: Pode ter uma leve vantagem de desempenho focada e otimizada exclusivamente para o ecossistema MySQL.

PDO: Possui desempenho similar, mas com uma pequena sobrecarga por causa da camada de abstração.

**Vantagens e desvantagens de utilizar PDO;**



**O que são Prepared Statements e por que são importantes;**



**Em quais situações o PDO pode ser uma boa escolha.** 