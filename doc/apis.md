**O que é uma API e qual é sua finalidade**

API é um mecanismo que permite que dois componentes de software se comuniquem utilizando um conjunto de definições e protocolos. API significa "Application Programming Interface", traduzindo, "Interface de Programação de Aplicação", a palavra Aplicação refere-se a qualquer software com uma função distinta. A interface pode ser pensada como um contrato de serviço entre duas aplicações. Esse contrato define como as duas se comunicam usando solicitações e respostas.

**Como funciona a comunicação entre sistemas por meio de APIs**

Funciona como um garçom que conecta dois sistemas de software diferentes, permitindo que eles troquem dados e funcionalidades sem que um precise conhecer os detalhes internos do código do outro. O cliente é o sistema A, ele quer fazer um pedido, o garçom é a API, que pega o o pedido e leva para a cozinha, já a cozinha é o sistema B, que prepara o pedido do sistema A.

**O que são APIs REST**

REST significa Transferência Representacional de Estado. REST define um conjunto de funções como GET, PUT, DELETE e assim por diante, que os clientes podem usar para acessar os dados do servidor. Clientes e servidores trocam dados usando HTTP. A principal característica da API REST é a ausência de estado. A ausência de estado significa que os servidores não salvam dados do cliente entre as solicitações. As solicitações do cliente ao servidor são semelhantes aos URLs que você digita no navegador para visitar um site. A resposta do servidor corresponde a dados simples, sem a renderização gráfica típica de uma página da Web.

**O papel dos métodos HTTP, como GET, POST, PUT, PATCH e DELETE**

GET: É o método mais utilizado na internet. Ele realiza uma operação de leitura, o que significa que o cliente está apenas solicitando informações ao servidor. Por ser um método seguro, ele não provoca nenhuma alteração no banco de dados, não importa quantas vezes seja executado.

POST: É utilizado quando o cliente deseja enviar novas informações para o servidor processar e armazenar. Ao contrário do GET, as informações não vão expostas na URL, mas sim no corpo da mensagem. Cada envio gera um novo registro, por isso ele não é considerado um método idempotente.

PUT: Serve para atualizar um registro que já existe no servidor, mas com uma regra específica: ele substitui o objeto inteiro. Caso você envie apenas um campo atualizado esquecendo os demais, os campos omitidos correm o risco de ser apagados ou substituídos por valores em branco no banco de dados.

PATCH: Funciona como uma alternativa mais leve e precisa ao PUT. Ele foi criado especificamente para modificações parciais. Em vez de enviar todo o conjunto de dados para atualizar uma informação simples, você envia apenas o fragmento exato que deseja modificar, economizando banda e processamento.

DELETE: Indica ao servidor que um determinado recurso deve ser removido permanentemente. Assim como o PUT e o GET, ele é idempotente; se você tentar deletar o mesmo arquivo várias vezes seguidas, a primeira tentativa vai excluí-lo e as seguintes apenas confirmarão que o arquivo não existe mais.

**O que são requisições e respostas HTTP**

REQUISIÇÃO: É o pedido inicial que o cliente, como o seu navegador ou aplicativo, envia ao servidor para solicitar um recurso ou realizar uma ação na internet. Ela carrega informações essenciais como o método HTTP (que define a intenção, como GET ou POST), a URL com o caminho exato do recurso desejado, cabeçalhos com metadados do sistema e, quando necessário, um corpo de mensagem com os dados preenchidos pelo usuário.

RESPOSTA: É o retorno enviado pelo servidor de volta ao cliente após receber e processar com sucesso ou falha a requisição correspondente. Ela é composta obrigatoriamente por um código de status de três dígitos (que resume o resultado da operação, como o 200 OK ou o 404 Not Found), cabeçalhos com metadados sobre o conteúdo enviado e o corpo da resposta, que traz o arquivo final que você visualiza na tela, como o código HTML de um site, uma imagem ou dados de texto.

**O que são endpoints**

Um local digial onde uma API recebe chamadas da própria, também conhecidas como solicitações de API, para recursos em seu servidor. Os endpoints de APIs são componentes de APIs e estão na forma de URLs ou localizadores de recursos uniformes. A URL de endpoint fornece o local de um recurso em um servidor de API e ajuda a conectar o cliente de API ao recurso que está sendo solicitado.

**Como funciona a troca de dados no formato JSON**

JSON (JavaScript Object Notation) é o padrão universal para trocar dados entre sistemas. Seu objetivo é transformar informações complexas em texto puro, permitindo que tecnologias diferentes conversem entre si de forma leve e rápida.
Essa comunicação funciona através de quatro pilares básicos:

Estrutura de Chave e Valor

O JSON organiza os dados de forma visual e legível. Ele utiliza chaves {} para definir objetos e colchetes [] para listas. As informações são gravadas sempre em pares de chave e valor (como "usuario": "Ana"), tornando o texto fácil de ser compreendido por humanos e máquinas.

Serialização e Desserialização

Para o dado viajar pela internet, o sistema de origem faz a serialização (transforma um objeto do código em texto JSON). Quando o texto chega ao destino, o sistema receptor faz a desserialização (transforma o texto JSON de volta em um objeto de código nativo para ser usado pelo software).

Transmissão via HTTP

Por ser apenas texto simples, o arquivo JSON é extremamente compacto. Ele trafega pela web através do protocolo HTTP consumindo o mínimo de banda e internet, o que garante respostas quase instantâneas entre os servidores e os aplicativos.

Independência de Linguagem

O JSON é universal e não pertence a nenhuma linguagem de programação. Isso permite que um servidor escrito em Python envie dados para um aplicativo mobile feito em Flutter, ou que um sistema em PHP converse com um banco de dados, sem a necessidade de nenhum conversor complexo.

**Diferenças entre uma API e um sistema web tradicional**

**Como uma aplicação PHP pode consumir uma API**

**Como uma API pode disponibilizar informações para outros sistemas**

**Importância da autenticação, autorização e segurança no consumo de APIs**

**Exemplos de utilização de APIs em sistemas empresariais**