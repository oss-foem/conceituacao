# Gerenciamento de Usuários e Perfis

## Descrição do Projeto: Aplicação de Gerenciamento de Usuários e Perfis em Laravel
Este projeto é uma aplicação desenvolvida em Laravel destinada ao gerenciamento de usuários e seus perfis dentro de uma organização. A aplicação define diferentes níveis de acesso e funcionalidades, baseados nos seguintes tipos de usuário:

### Administrador
- Detém controle total sobre a aplicação.
- Possui a capacidade de criar, editar e gerenciar todos os perfis de usuário.
- Também possui capacidade criação, edição e gerenciamento de todos usuários.
- Tem a responsabilidade de atribuir perfis aos usuários.

### Gerente
- Caso um usuário receba o perfil de "Gerente" por um Administrador.
- Possui a permissão para criar, listar e editar usuários.
- O poder de edição do Gerente é limitado, não se estendendo a usuários que possuem o perfil de "Administrador" e não podendo atribuir perfis a usuários.

### Usuário Comum
- É um usuário sem um perfil administrativo específico.
- Tem acesso restrito às funções básicas, podendo apenas editar seu próprio perfil.
- Possui funcionalidades limitadas ao login e logout na aplicação.

## Configuração do Projeto

### Requisitos:

- PHP >= 8.0
- Composer
- Banco de dados relacional (MySQL, SQLite, PostgreSQL ou outro compatível com Laravel)
- Laravel >= 10.x
- Frontend: Livre escolha

### Utilizados:

- PHP 8.4.5
- Composer 2.8.6
- Banco de dados relacional SQLite
- Laravel 10.48.29
- Frontend: Bootstrap + Laravel Utilites

### Passos para Configurar o Ambiente:

1. Clone o repositório:
   ```sh
   git clone https://github.com/oss-foem/conceituacao.git
   cd gerenciadorUsuarios
   ```
2. Instale as dependências:
   ```sh
   composer install
   ```
3. Copie o arquivo `.env.example` e configure as variáveis de ambiente:
   ```sh
   cp .env.example .env
   ```
4. Gere a chave da aplicação:
   ```sh
   php artisan key:generate
   ```
5. Configure o banco de dados no arquivo `.env` colocando o caminho absoluto para a pasta database.
   ```sh
      DB_CONNECTION=sqlite
      DB_DATABASE=absolute\path\to\database\gerenciador.sqlite
   ```
### Executando Migrations e Seeders

1. Execute as migrations confirmando a criação do banco:
   ```sh
   php artisan migrate
   ```
2. Execute os seeders para criar dados do administrador:
   ```sh
   php artisan db:seed
   ```
3. Rodar na porta padrão:
   ```sh
   php artisan serve
   ```
4. ! Caso necessário instale as dependências do Vite: !
   ```sh
   npm install
   npm run dev
   npm run build
   composer install
   ```

---

## Usuário de Teste para Login

- **Email:** `admin@admin.com`
- **Senha:** `admin`

---

## Laravel Info


<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
