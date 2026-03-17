<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>
<p align="center"><a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a><a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a><a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a><a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a></p>

🚀 Sobre o Projeto

Este projeto é um microsserviço desenvolvido em Laravel para o gerenciamento de pedidos de viagem corporativa.  
O microsserviço expõe uma API REST que permite:

📊 Funcionalidades
- Autenticação
    - Login e registro de usuários com JWT.
    - Middleware de autenticação para proteger rotas.
    - Rate limiting para limitar requisições.
- Pedidos de Viagem
    - Criação de pedidos com destino, data de ida e volta.
    - Status inicial sempre como "solicitado".
    - Atualização de status apenas para "aprovado" ou "cancelado".
    - Regras de negócio: dono do pedido não pode alterar o próprio status.
- Notificações
    - Registro automático de notificações em mudanças de status.
    - Eventos e listeners para monitorar alterações.
    - Histórico de notificações vinculado ao pedido e ao usuário.
- Usuários
    - Gerenciamento de dados pessoais.
    - Controle de permissões via Policies.
    - Logout e edição de informações.

🛠 Tecnologias utilizadas
Backend
- PHP 8.5
- Laravel 12
- Eloquent ORM
- Middleware de autenticação
- JWT (JSON Web Token) – autenticação e autorização

Banco de Dados
- MySQL
- Migrations
- Seeders & Factories

Documentação
- OpenAPI 3.0.3 (Swagger)
- Swagger UI
- Schemas reutilizáveis – padronização de modelos

Infraestrutura
- Composer - gerenciador de dependências PHP
- PHPUnit - testes automatizados
- Docker - containerização do ambiente
  - App - Laravel/PHP-FPM → porta 8000  
  - Nginx - proxy reverso
  - MySQL - banco de dados

📐 Versionamento da API
- Todas as rotas estão versionadas sob o prefixo `/api/v1/*`, garantindo compatibilidade futura e permitindo evolução da API sem quebra de clientes existentes.

📂 Estrutura do Projeto
- Models → entidades principais da aplicação (User, TravelOrder, TravelOrderNotification)
- Controllers → responsáveis por receber requisições e aplicar regras de negócio
- Policies → controle de permissões e regras de autorização
- Events & Listeners → disparo e tratamento de eventos quando o status de uma ordem muda
- Exceptions → classes customizadas para regras de negócio
- Middleware → autenticação JWT, rate limiting e proteção das rotas
- Routes → organizadas sob /api/v1/* (login, register, travel-orders, status)
- Migrations & Seeders → definição e popularização do banco de dados
- Resources (Transformers) → padronização das respostas JSON
- Swagger/OpenAPI → documentação integrada dentro da API

⚙️ Práticas adicionais
- Exceções customizadas: tratamento diferenciado para ambientes de teste e produção
- Consultas otimizadas: uso de `whenLoaded` para evitar N+1
- Segurança: rate limiting para proteção contra abuso
- Autorização: policies para regras de permissão
- Eventos e listeners: monitoramento e execução automática em mudanças de status
- Boas práticas de dados: datas sempre em UTC
- Documentação integrada: Swagger disponível dentro da API (http://localhost:8000/api/documentation)

🧪 Testes
O projeto inclui testes utilizando PHPUnit, cobrindo cenários essenciais:
- Postman / cURL → testes manuais das rotas
- PHPUnit → testes automatizados
- Swagger UI → execução de chamadas diretamente na documentação
- Login de usuário → valida credenciais e autenticação via JWT.
- Criação de pedidos de viagem → garante que os dados obrigatórios (destino, datas) sejam informados corretamente.
- Atualização de status → valida regras de negócio (somente "aprovado" ou "cancelado").
- Exceções → assegura que exceções customizadas sejam lançadas corretamente em casos inválidos.
- Notificações → verifica se eventos e listeners disparam notificações ao alterar status.

▶️ Como Executar
Clonar o repositório
 - git clone https://github.com/robsoncff2010/test-laravel-api-travel-orders.git

Instalar dependencias
 - composer install

▶️ Executando com Docker
Subir os containers
 - docker compose up -d --build

Gerar chave
 - docker compose exec app php artisan key:generate

Criar migrations
 - docker compose exec app php artisan migrate

Limpar caches
 - docker compose exec app php artisan config:clear
 - docker compose exec app php artisan cache:clear
 - docker compose exec app php artisan route:clear

Acessar o API (porta configurada no docker-compose)
- http://localhost:8000/api/v1

📜 Licença
Este projeto está licenciado sob a MIT License, permitindo uso, modificação e distribuição, desde que seja mantida a nota de licença original.