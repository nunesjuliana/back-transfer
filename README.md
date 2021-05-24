# Backend-Transfer

O objetivo deste projeto é a criação de uma API para a realização de transferências monetárias entre usuários. 
No processamento há a utilização de apis externas para a autorização das transferências e para o envio de emails, os quais são simulados através de mocks.
O ambiente do projeto utiliza Docker e o envio de emails é feito através de filas.

# Regra de negócio

Os usuários podem ser comuns, identificados por cpf, ou lojistas, identificados por cnpj.

O Lojista somente pode receber dinheiro.

O usuário só pode realizar uma transferencia caso tenha saldo para realiza-la.

# Modelagem de dados

O projeto possui apenas uma tabela, idenficada como **Users**, a qual possuem os seguinte atributos:

* **id:** 
  * **Tipo:** inteiro 
  * **Descrição:** Identificador do usuário.
  * **Regra:** É um atributo obrigatório 
  
* **name:** 
  * **Tipo:** string  
  * **Descrição:** O nome do usuário comun ou lojista.
  * **Regra:** É um atributo obrigatório
  
* **email:**
  * **Tipo:** string  
  * **Descrição:** O email do usuário comun ou lojista.
  * **Regra:** Não deve haver repetições do seu valor e é obrigatório

* **tipouser:** 
  * **Tipo:** integer  
  * **Descrição:** É o tipo do usuário. Pode ser o valor 0( usuário comun) ou 1 ( usuário lojista ) 
  * **Regra:** É um atributo obrigatório

* **cpf:** 
  * **Tipo:** string  
  * **Descrição:** é o cpf do usuário comun.
  * **Regra:** Torna-se um atributo obrigatório caso o valor do **tipouser** seja **0(zero)**

* **cnpj:** 
  * **Tipo:** string  
  * **Descrição:** é o cpf do usuário comun.
  * **Regra:** Torna-se um atributo obrigatório caso o valor do **tipouser** seja **0(zero)**
  
* **balance:** 
  * **Tipo:** string  
  * **Descrição:** É o saldo da carteira do usuário
  * **Regra:** É um atributo obrigatório e deve ser igual ou maior que 0(zero).
  
* **password:** 
  * **tipo:** string  
  * **Descrição:** É a senha do usuário
  * **Regra:** É um atributo obrigatório 
  

# Estrutura de diretórios do repositório
 
``` 
- Dockerfile : Contém as configurações para o uso de containers.
- docker-compose.yml : Contém as configurações para o uso de containers.
- nginx.conf : Contém as configuração para o uso do servidor nginx.
- supervisord.conf : Contém as onfiguração para o supervisor php e o nginx no mesmo container.
- app 
   |- Exception : Encontra-se os arquivos específicos de exceção.
       |- CustomException.php = arquivo usado para exceções customizadas.   
   |- Http
       |- Constants = Encontra-se os arquivos de constantes
           |- TransactionConstant = Constantes do Model de Transação
           |- UserCostant = Constantes do Model de Usuário
       |- Controllers = Encontra-se os controlles do models.
           |- TransactionController = Controller do Model de Transação.
           |- UserController = Controller do Model de Usuário
       |- Events : Encontra-se os eventos
           |- CompletedTransactionEvent : Evento para o término da transação
           |- TransactionInProcessEvent : Evento para quando a transação está em processo
       |- Listeners
           |- CheckAuthorizationListener : Listener para a realização da requisição ao serviço de autorização de transação. Disparado pelo evento **TransactionInProcessEvent**
           |- SendEmailCompletedTransactionListener : Listener para a realização da requisição ao serviço de envio de email. Disparado pelo evento **CompletedTransactionEvent**
       |- Models
           |- Transaction : Transação
           |- User : usuário
       |- Repositories : Repositorio de acesso a base de dados
           |- UserRepository : Repositorio do Model de Usuário. 
       |- Request : Encontra-se o tratamento dos atributos dos Models
           |- UserRequest : Tratamento para o model de Usuário.
       |- Services 
           |- TransactionService : Serviços do Model de Transação
       |- Validations : Contém os arquivos de validações especificas
           |- UserValidation : Arquivo de validação do model de Usuários.
   |- Routes
       |- api.php : Contém a declaração das rotas.
   |- Tests
       |- Feaure
           |- transactiontest.php : Contém os testes relacionados a api de Transação.
       |- Unit
           |- usertest.php : Contém os testes relacionados ao Model de Usuário.
           
```  
# Ambiente de Desenvolvimento
## Requisitos:
* Docker > 20.10
* Composer > 1.10

## Passos para subir o ambiente:
* Clone o repositório
* Execute o comando: sudo 777 -R <pasta_backend-transfer>. observação: Isto é para evitar erros de falta de permissão ao subirs os containers.
* Execute o comando: cd app/
* Copie o arquivo .env.example para .env ( Variáveis de ambiente)
* Eecute o comando : make start ( Subirá o ambiente e colocará o gerenciador de fila para trabalhar )

## Realização de testes:
* Copie o arquivo .env.testing.example para .env.testing  ( Variáveis de ambiente para o ambiente de teste)
* Crie a database com nome colocado na variável DB_DATABASE do arquivo .env.testing.
* Execute o comando : make start (caso seja o primeiro acesso) ou make run( caso já tenha executado o make start antes. Isto subirá o ambiente e colocará a fila para trabalhar
* Abra outra aba do terminal. Observação: Certifique que esta dentro a pasta **app**
* Execute o comando : make run_tests

## Makefile
 É uma arquivo com a lista de comandos para auxiliar na execução de tarefas específicas ou conjuntos de tarefas do projeto. Abaixo contém a lista de comandos para este projeto
  
``` 
* make tdd: Executa os testes
* make up_database: Sobe o container do database
* make up_serve: Sobe o container do servidor
* make stop: Derruba os containers de database e o servidor
* make migrates: Roda as migrates da base de dados
* make queue: Coloca a fila para trabalhar.
* composer_install: Roda o compose install
* make run: Sobe os container do servidor, do database e coloca a fila para trabalhar.
* make start: Sobe os container do servidor, do database, compose intall, os migrates e coloca a fila para trabalhar.

```  
 
# Apis

## Transação

O objetivo é realizar a transferência monetária entre os usuários.
 
### Endpoint
 
> `https://localhost:8080/api/transaction`
 
* Método: PUT
 
### Request
```json
{
    "payer": "string",
    "payee": "string",
    "value": "float"
   } 
```
#### Atributos
* **payer:**
  * **tipo:** string 
  * **descrição:** É o email do usuário pagador, a origem da transferencia
* **payee:**
  * **tipo:** string 
  * **descrição:** É o email do usuário beneficiário, o destino da transferencia
* **value:** 
  * **tipo:** float 
  * **Descrição:** valor a ser transferido. Deve ser maior que 0(zero).
  
* **Exemplo do request:**
```
{
  "payer" : "cst@gmail.com",
  "payee" : "frt@gmail.com",
  "value" : 50.9
}
```

### Response
* Status: 200 - OK  
  * Transação realizada com sucesso
  
* Status: 412 - PRECONDITION FAILED 
  * Ocorre quando uma pré-condição não foi atendida para o realizamento da transação, a qual pode ser:
  1. O atributo Payer do request sendo um usuário lojista o qual só é permitido recebe transferencias.
  2. O atributo Payer do request não tendo o saldo suficiente para realizar a transação. Seu saldo pode está igual a 0(zero) ou seu saldo se tornará negativo com transferencia.
  3. O atributo Value do request da transferência é menor que 0(zero)

* Status 422 - UNPROCESSABLE ENTITY  
  * Ocorre quando não existe o Payer ou/e o Payee cadastrados com os emails colocados na requisição. 

* Status 500 - INTERNAL SERVER ERROR 
  * Ocorre quando há um erro no processo interno ou com a api de autorização de transação.
  * Observação: Erros na api de email não pararam o processamento da transação mas serão enviados para alguma ferramenta de reportagem de erros.
 
## Usuários
### Inserção
 
O objetivo é a inserção de novos usuários

#### Endpoint
 
> `https://localhost:8080/api/user`
 
* Método: GET
 
#### Request
```json
{
    "name": "string",
    "tipouser": "integer",
    "cnpj": "string",
    "cpf": "string",
    "email": "string",
    "senha": "string",
    "balance" : "string",
} 
```
* Exemplo de request de usuário comun, com cpf:
```
{
    "name":"juliana nunes",
    "email": "cst@gmail.com",
    "tipouser": "0",
    "cpf": "45576652096",
    "password": "123",
    "balance": 100
}
```
* Exemplo de request de usuário lojista, com cnpj:
```
{
    "name":"juliana nunes",
    "email": "cst@gmail.com",
    "tipouser": "1",
    "cnpj": "86254851000186",
    "password": "123",
    "balance": 100
}
```

#### Response

* **Status: 201 - Created**
 
```json
{
    "id": "integer",
    "name": "string",
    "tipouser": "integer",
    "cnpj": "string",
    "cpf": "string",
    "email": "string",
    "senha": "string",
    "balance" : "string",
}   
```
* **Status: 422 - Unprocessable Entity**
 
  * Ocorre quando a validação de algum atributo é infrigida.
 
### Listagem
 
O objetivo é listar todos os usuários

#### Endpoint
 
> `https://localhost:8080/api/user`
 
* Método: GET
 
#### Request 
* Nenhuma
 
#### Response
* **Status: 200 - OK**
 
```json
[{
    "id": "integer",
    "name": "string",
    "tipouser": "integer",
    "cnpj": "string",
    "cpf": "string",
    "email": "string",
    "senha": "string",
    "balance" : "string",
}   
```
  
### Busca
 
O objetivo é busca um determinado usuário

#### Endpoint
 
> `https://localhost:8080/api/user/{id_usuário}`
 
* Método: GET
#### Request 
  * id_usuário : número de atributo **id** do usuário.
 
#### Response
* **Status: 200 - OK**
 
```json
{
    "id": "integer",
    "name": "string",
    "tipouser": "integer",
    "cnpj": "string",
    "cpf": "string",
    "email": "string",
    "senha": "string",
    "balance" : "string",
}   
```

### Alteração
 
O objetivo é alterar algum(uns) campos de um determinado usuário.

#### Endpoint
 
> `https://localhost:8080/api/user/{id_usuário}`

* Método: PUT
#### Request 
  * id_usuário : número de atributo **id** do usuário.
 
#### Response
* **Status: 200 - OK**
 
```json
{
    "id": "integer",
    "name": "string",
    "tipouser": "integer",
    "cnpj": "string",
    "cpf": "string",
    "email": "string",
    "senha": "string",
    "balance" : "string",
}   
```

### Exclusão
 
O objetivo é excluir um determinado usuário
#### Endpoint
 
> `https://localhost:8080/api/user/{id_usuário}`
 
* Método: DELETE
* Request 
  * id_usuário : número de atributo **id** do usuário.
 
#### Response 
Status: 200 - OK
 
