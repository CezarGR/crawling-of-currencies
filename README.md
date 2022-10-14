# Crawling Of Currencies

Para inicalizar e rodar o projeto basta seguir os seguintes passos:

## 1. Clonar o projeto

Eu sei que você já sabe, mas...

```bash
git clone https://github.com/CezarGR/crawling-of-currencies.git
```

## 2. Rodar o container

Entre na pasta raiz do projeto clonado, abra o terminal e insira o seguinte código

obs: é necessário ter em sua máquina o docker e o docker compose instado, senão acesse o [link](https://docs.docker.com/compose/install/) para efetuar o download.

```bash
docker compose build
docker-compose up -d
```

## 3. Instalar as dependências

Agora, entre dentro do Container App, para isso excute o seguinte código em seu terminal na raiz do projeto:

```bash
docker exec -it container_app bash
```

Em seguida, já dentro do container, instale as dependências executando o comando:

```bash
/app# composer install
```
```bash
/app# npm install
```

## 4. Configuração do banco de dados

Primeiramente, vá até o gerenciado de banco de dados de sua escolha e crie uma nova conexão com os valores do .env (**raiz_do_projeto/.env**).

Após a conexão ser realizada com sucesso, volte para terminal interno do **container_app** e excute o comando:

```bash
/app# php artisan migrate
```

## 5. Configuração

Ao terminar de instalar as dependências, configure o seu ambiente. O arquivo .env para as configurações do Docker se encontra no caminho **raiz_do_projeto/.env** e para o projeto da API no caminho **raiz_do_projeto/src/.env**

## 6. Acessar a documentação

Há, dentro do projeto, uma rota que tem como responsabilidade exibir a documentação da API (/api/documentation), para isso foi utilizado o [Swagger](https://swagger.io/).

O projeto por padrão é servido na porta 8083, mas é de sua escolha a troca. Para efetuar a mudança, é necessário a alteração do valor da chave **DOCKER_HOST_HTTP_PORT_API**, a qual se encontra no arquivo **raiz_do_projeto/.env**

##
