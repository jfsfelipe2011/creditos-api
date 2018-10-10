# API de Créditos

#### Slim Framework 3 Skeleton Application

O Microframework Slim é uma das opções mais usadas para o desenvolvimento de APIs. Ele tem como caracteristica o rápido desenvolvimento e sua liberdade para o uso e escolhas de bibliotecas.

Nesse projeto usei as bibliotecas padrão de Router, Request e Response. Também foram usadas as bibliotecas php-jwt, slim-jwt-auth e slim-basic-auth, para a autorização de acessos ao recursos. Foi usado a biblioteca slim-validation para a validação dos Requests. Também foi usado o ORM Eloquent para os modelos do sistema.

#### Como rodar o projeto

Esse projeto usa um container docker para os testes em desenvolvimento. Então para subir o container, é necessário dar permissão de execução no arquivo de instalação. Para isso rode o seguinte comando:

```shell
chmod +x startapp.sh
```

Depois das devidas permissões, basta executar o arquivo de instalação, com o comando:

```shell
./startapp.sh
```

** Obs.: Caso você não tenha a network ```jose```, o docker irá gerar um erro e lhe mostrar o comando necessário para criar a rede. Depois do comando, basta executar novamente a instalação. Caso queira vocêr pode alterar o nome da rede ou colocar em uma rede existente editando ```networks``` no arquivo ```Dockerfile```.

Deixo como dica rodar o container chamado [docker-hosts-updater](https://github.com/grachevko/docker-hosts-updater) que faz a atualização do arquivo de hosts do seu computador, para apontar o IP do container para o seu nome do host. Caso você não tenha esse container, você terá que mapear ele de forma manual em seu arquivo de hosts. Para isso execute os comando: 

```shell 
docker inspect --format={{.NetworkSettings.Networks.creditos.IPAddress}} creditos
```
E anote o ip que foi informado. Depois insira a linha a abaixo em seus hosts:

```shell
seu_ip      creditos
```

Fique atento ao container de nome ```creditos-composer```, pois ele é responsável por instalar as dependencias do projeto. Para isso você pode analisa-lo com o seguinte comando:

```shell
docker logs -f creditos-composer
```

#### Modelagem e DB

O banco escolhido para ser usado no projeto, foi o Mysql. Escolhi o Mysql por ele ser um banco que já está no mercado a bastente tempo.

Abaixo segue o mapeamento das tabelas usadas no projeto.

#####users

Está é a tabela que armazena os usuários que acessam o sistema web. 

* id - INT
* name - VARCHAR
* email - VARCHAR
* password - VARCHAR
* remember_token - VARCHAR
* created_at - TIMESTAMP
* update_at - TIMESTAMP

#####clients

Essa é tabela usada para armazenar os clientes do sistema. Essa tabela é auto-explicativa, usei para armazenar os clientes e testar as funcionalidades. Acredito que em um outro cenário os clientes estariam em outro local.

* id - INT
* name - VARCHAR
* document - VARCHAR
* email - VARCHAR
* fone - VARCHAR
* created_at - TIMESTAMP
* update_at - TIMESTAMP

#####credits

Essa tabela é usada para armazenar os créditos de um cliente. Ela é usada para saber quantos e-mails o cliente pode enviar. Nela usei o campo value que é o valor original de quando uma recarga é feita. Também criei um campo chamado balance que é o saldo dessa recarga, ou seja, quantos créditos ainda tem nessa recarga. Essa tabela é vinculada com cliente.

* id - INT
* value - INT
* validate - DATE
* description - TEXT
* balance - INT
* client_id - INT
* created_at - TIMESTAMP
* update_at - TIMESTAMP

#####extracts

Essa tabela é o extrato do cliente. Ela é usada como se fosse um log dos créditos do cliente. Qualquer uma das operações do sistema geram uma linha nessa tabela. Ela tem um relacionamento com cliente, que permite gerar um conjunto de registros de histórico.

* id - INT
* type - ENUM
* description - TEXT
* date - TIMESTAMP
* credits - INT
* operation - ENUM
* balance - INT
* client_id - INT
* created_at - TIMESTAMP
* updated_at - TIMESTAMP

No diretório database/ se encontra o modelo fisico do banco. Ele é usado para gerar a base usada no projeto.

Para acessar o banco de dados, use um gerenciador de banco mysql a seu critério, com as credenciais abaixo:

```
Host:            creditos-mysql
User:            creditos
Password:        creditos
Base:            creditos
```

#### Estrutura do Projeto

1. **config** - Diretório que guarda as configs do projeto.

2. **database** - Diretório responsável por guardar os arquivos para a montagem do banco de dados.

3. **logs** - Diretório que armazena os logs do sistema. Está divido em app.log e error.log, que guardam as operações do sistema e os erros respectivamente.

4. **public** - Pasta root do sitema que chama a aplicação.

5. **src** - Diretório que armazena o core do projeto.

* **Controllers** - Diretório que armazena os controladores do sistema.

* **Helper** - Diretório com funções auxiliares do sistema.

* **Models** - Diretório de modelos do sistema.

* **Providers** - Diretório que contem os provedores de serviços do sistema.

* **Repository** - Diretório que contém os repositorios de ações do sistema com a base de dados.

* **ResourceRouter** - Diretório que armazena a geração de rotas genericas do sistema.

* **Validation** - Diretório que armazena as validações das requests.

* **dependecies.php** - Arquivo que são geradas as dependencias do sistema.

* **middleware.php** - Arquivo onde é armazenada a lógica de acesso a aplicação.

* **routes.php** - Arquivo de rotas do sistema.

* **settings.php** - Arquivo de configurações do sistema.

6. **Dockerfile** - Arquivo de geração de imagem docker.

7. **docker-compose.yml** - Arquivo de criação de container.

8. **startapp.sh** - Arquivo de iniciação da aplicação.


#### Funcionalidades

#####Usuários

Esses são os recursos usados para o CRUD completo de usuários, que vão ter acesso ao sistema. Esse acesso é restrito para o usuário admin do sistema. Para isso todas as rotas tem proteção básica de usuário e senha. Para isso passe a opção Authorization, com type Basic Auth e os seguintes dados:

```
Usename:	admin@email.com
Password:   senac2018
```

Recursos:

/users			- GET        - Lista todos os usuários

/users		    - POST       - Criação de usuários

/users/{id}     - GET        - Mostra um usuário

/users/{id}     - PUT        - Atualiza um usuário

/users/{id}     - DELETE     - Deleta um usuário

Campos para criação/atualização:

* name - STRING
* email - STRING
* password - STRING

#####Auth

Essa rota que é usada para gerar um token de autorização de acesso aos demais recursos do sistema. Para acesso a todos os demais recursos do sistema, é necessário passar na requisição o Header: Authorization: Bearer {token}.

Recurso:

/auth           - POST        - Gera um token de acesso.

Campos para a geração:

* email - STRING
* password - STRING

#####Clientes

Esses são recursos para o CRUD de clientes do sistema.

Recursos:

/clients			- GET        - Lista todos os clientes

/clients		    - POST       - Criação de clientes

/clients/{id}       - GET        - Mostra um cliente

/clients/{id}       - PUT        - Atualiza um cliente

/clients/{id}       - DELETE     - Deleta um cliente

Campos para criação/atualização:

* name - STRING
* document - STRING
* email - STRING
* fone - STRING

#####Saldo

Recurso que retorna o saldo em créditos do cliente.

Recursos:

/clients/{id}/saldo     - GET    - Saldo do cliente

#####Extrato

Recurso que retorna o extrato de operações do cliente.

Recursos:

/clients/{id}/extrato   - GET   - Extrato do cliente

#####Créditos

Recurso que retorna os créditos ativos do cliente.

Recursos:

/clients/{id}/creditos    - GET    - Créditos do cliente

#####Recarregar

Recurso que cria um novo crédito para o cliente.

/recarregar/{id}       - POST      - Recarrega créditos para o cliente.

Campos para criação:

* value - INT
* validate - DATE
* description - STRING

#####Remover

Recurso que remove os créditos do cliente.

/remover/{id}         - POST      - Remove créditos de um cliente.

Campos para criação:

* credits - INT
* description - STRING

#####Estornar

Recurso que estorna os créditos do cliente.

/estornar/{id}         - POST      - Estornar créditos de um cliente.

Campos para criação:

* credits - INT
* description - STRING