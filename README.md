# Comerc Pastry

Este projeto é uma aplicação desenvolvida em Laravel para gerenciar uma pastelaria. O sistema permite realizar operações CRUDL (Criar, Ler, Listar, Atualizar e Deletar) em clientes, produtos e pedidos, com suporte para busca, filtragem e paginação.

Para detalhes sobre como usar os endpoints, consulte a [DOCUMENTAÇÃO DA API](https://documenter.getpostman.com/view/22300616/2sAY4uC3Sf).

## Tecnologias utilizadas

-   PHP 8.3
-   Laravel 11
-   Pest
-   MySql

## Requerimentos

Necessário sistema operacional macOS, Linux ou Windows (via [WSL2](https://docs.microsoft.com/en-us/windows/wsl/about)) e Docker.

## Rodando localmente

Clone o projeto

```bash
git clone https://github.com/dandevweb/comerc-pastry.git
```

Entre no diretório do projeto

```bash
cd comerc-pastry
```

Crie o arquivo .env a partir do arquivo .env.example

```bash
cp .env.example .env
```

Com o Docker "startado", suba o container

```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs
```

Inicie o servidor

```bash
./vendor/bin/sail up -d
```

Crie um alias para facilitar os comandos do Sail

```bash
alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'
```

Gere a chave da aplicação

```bash
sail artisan key:generate
```

Execute as migrations

```bash
sail artisan migrate
```

Execute os seeders

```bash
sail artisan db:seed
```

Instale as dependências javascript

```bash
sail npm install
```

Acesse o projeto em:

    - http://localhost

Acesse o serviço de e-mail em:

    - http://localhost:8025

## Testes

Para rodar os testes, execute o comando:

```bash
sail test
```

## Usuário para login

    -   E-mail: test@example.com
    -   Senha: Password1

### Configuração de Funcionalidades

-   **Husky**: Utilizamos a ferramenta Husky como uma dependência de desenvolvimento para automatizar a execução de testes, realizar análise estática e formatar o código em cada commit realizado. Isso garante que o código mantenha um padrão de qualidade e esteja sempre funcional.

-   **Documentação da API**: Para mais informações sobre como utilizar a API, siga a [Documentação da API](https://documenter.getpostman.com/view/22300616/2sAY4uC3Sf). Lembre-se de alterar a URL base para `http://localhost` para testes locais.
