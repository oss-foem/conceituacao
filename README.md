## Projeto Laravel - Dashboard 

### Descrição do Projeto
Este é um projeto da painel administrador, com foco na criação e gerenciamento de usuários e suas permissões (roles). A partir de uma API onde utilizamos JWT para autenticação de usuários, podemos gerir todas as regras e usuários cadastrados, assim como fazer novos cadastros. Utilizamos um banco de dados MySQL para armazenar os dados de usuários e roles.

## Passos para Configurar o Ambiente

```
git clone https://link-do-seu-repositorio.git
cd nome-do-repositorio
```
### Instalar as dependências:
`composer install `

### Configurar o arquivo .env
Copie o arquivo de exemplo .env.example para criar o seu próprio .env:
`cp .env.example .env`- Para terminais linux
ou utilize o método de copiar tradicional do windows.

Abra o arquivo .env e configure as variáveis do banco de dados conforme a configuração do seu ambiente local. Exemplo:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```
### Gere a chave da aplicação
`php artisan key:generate`

### Gere a chave JWT para autenticação
`php artisan jwt:secret`

### Crie o banco de dados
Acesse o mysql como user root e crie o banco antes de rodar as migrations.
`mysql -u root -p`
`CREATE DATABASE seu_banco`

É importante conceder privilégios ao usuário que vamos utilizar (que está no arquivo .env)
```
GRANT ALL PRIVILEGES ON laravel.* TO 'seu_user'@'localhost' IDENTIFIED BY 'sua_senha';
FLUSH PRIVILEGES;
```

### Rodar as migrations
`php artisan migrate`

### Rodas as seeds
`php artisan db:seed` 

### Iniciar o servidor
`php artisan serve`

### Logar como user padrão 
user: `admin@example.com`
senha: `password`

