# Task System

Sistema de gerenciamento de projetos e tarefas com cálculo de progresso ponderado baseado na dificuldade das tarefas.

## 🚀 Tecnologias

- **Laravel 12** (última versão)
- **PHP 8.4** com PHP-FPM
- **Composer** para gerenciamento de dependências
- **SQLite** para testes
- **MySQL 8.0** como banco de dados principal
- **Redis 7** para cache e sessões
- **Nginx** como servidor web

## 📋 Pré-requisitos

- Docker
- Docker Compose

## 🚀 Instalação e Execução

### 1. Clonar o repositório

```bash
git clone git@github.com:Chris7T/task-system.git
cd task-system
```

### 2. Construir e iniciar os containers

```bash
docker-compose up -d --build
```

### 3. Instalar dependências do Composer

```bash
docker-compose exec app composer install
```

### 4. Configurar o arquivo .env

```bash
docker-compose exec app cp .env.example .env
docker-compose exec app php artisan key:generate
```

### 5. Executar migrations

```bash
docker-compose exec app php artisan migrate
```

### 6. Acessar a aplicação

A aplicação estará disponível em: **http://localhost:8080**

## 🧪 Executar Testes

```bash
docker-compose exec app php artisan test
```

## 📚 Documentação da API

A documentação Swagger está disponível em: **http://localhost:8080/api/documentation**

Para gerar/atualizar a documentação:

```bash
docker-compose exec app php artisan l5-swagger:generate
```
