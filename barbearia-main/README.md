# Sistema de Agendamentos para Barbearia

Sistema web para gerenciamento de agendamentos de serviços em barbearias, desenvolvido em PHP seguindo a arquitetura MVC (Model-View-Controller).

## Características

- Agendamento online de serviços de barbearia
- Painel administrativo para gerenciamento de agendamentos
- Interface responsiva para uso em dispositivos móveis e desktop
- Autenticação de usuários para o painel administrativo
- Gerenciamento de clientes e histórico de agendamentos
- Banco de dados SQLite portátil (não requer servidor MySQL)
- Servidor PHP embutido (não requer XAMPP/WAMP)

## Pré-requisitos

- **PHP 7.4 ou superior** com as extensões:
  - PDO SQLite (geralmente habilitada por padrão)
  - JSON (geralmente habilitada por padrão)

### Instalação do PHP

**Windows:**
1. Baixe o PHP em [windows.php.net/download](https://windows.php.net/download/)
2. Escolha a versão Thread Safe (TS) 
3. Extraia para uma pasta (Ex: C:\php)
4. Adicione essa pasta ao PATH do sistema
5. Renomeie php.ini-development para php.ini
6. No php.ini, descomente a linha extension=pdo_sqlite removendo o ; do início

**macOS:**
```
brew install php
```

**Linux (Ubuntu/Debian):**
```
sudo apt update
sudo apt install php php-sqlite3
```

## Tecnologias Utilizadas

- PHP 7.4+
- SQLite (banco de dados baseado em arquivo)
- HTML5, CSS3, JavaScript
- TailwindCSS
- PDO para conexão com banco de dados
- Arquitetura MVC
- Servidor PHP embutido

## Estrutura do Projeto

```
barbearia-main/
├── admin/                # Área administrativa
│   ├── dashboard.php     # Painel de controle
│   ├── login.php         # Página de login admin
│   └── logout.php        # Script de logout
├── api/                  # Endpoints da API
│   ├── endpoints/        # Endpoints específicos
│   └── index.php         # Informações gerais da API
├── app/                  # Core da aplicação
│   ├── config/           # Configurações
│   ├── controllers/      # Controladores
│   ├── helpers/          # Funções auxiliares
│   ├── models/           # Modelos de dados
│   └── views/            # Templates e visualizações
├── database/             # Scripts de banco de dados
│   ├── migrations/       # Migrations do banco (MySQL)
│   └── sqlite/           # SQLite database e schema
│       ├── barbearia.db  # Arquivo de banco de dados SQLite
│       └── schema.sql    # Esquema para SQLite
├── public/               # Arquivos públicos
│   ├── css/              # Folhas de estilo
│   ├── images/           # Imagens
│   ├── js/               # Scripts JavaScript
│   ├── agendamento.php   # Formulário de agendamento
│   └── index.php         # Página inicial
├── iniciar_servidor.bat  # Script para iniciar o servidor (Windows)
├── iniciar_servidor.sh   # Script para iniciar o servidor (Unix/Linux/Mac)
└── server.php            # Script PHP do servidor embutido
```

## Instalação e Uso

### Método Simplificado (Recomendado)

1. Clone ou baixe o repositório:
   ```
   git clone https://github.com/seu-usuario/barbearia.git
   ```

2. No Windows, simplesmente execute o arquivo:
   ```
   iniciar_servidor.bat
   ```

3. Em sistemas Unix/Linux/Mac, execute:
   ```
   chmod +x iniciar_servidor.sh
   ./iniciar_servidor.sh
   ```

4. Acesse a aplicação através do navegador:
   ```
   http://localhost:8000/
   ```

### Configuração Manual (Alternativa)

1. Se desejar usar um servidor web como Apache, configure-o para apontar para o diretório raiz do projeto.

2. O banco de dados SQLite será criado automaticamente no primeiro acesso em `database/sqlite/barbearia.db`

## Acesso ao Painel Administrativo

- URL: `http://localhost:8000/admin/login.php`
- Usuário padrão: `admin`
- Senha padrão: `admin123`

## Portabilidade

Para mover o sistema para outro computador, basta copiar a pasta inteira do projeto. O banco de dados SQLite está contido dentro do projeto e todas as tabelas serão criadas automaticamente no primeiro acesso.

## Solução de Problemas

**PHP não encontrado**
- Verifique se o PHP está instalado executando `php -v` no terminal/prompt
- Caso não esteja, siga as instruções na seção "Instalação do PHP"
- Se estiver instalado mas não é reconhecido, certifique-se de que está no PATH do sistema

**Erro ao criar banco de dados**
- Verifique se a pasta `database/sqlite` tem permissão de escrita
- Em sistemas Unix/Linux/Mac: `chmod -R 777 database/sqlite`

**Porta 8000 já em uso**
- Edite o arquivo `server.php` e altere a variável `$port` para outro valor (ex: 8080)

## Licença

Este projeto está licenciado sob a Licença MIT.

## Agradecimentos

- [TailwindCSS](https://tailwindcss.com/)
- [Font Awesome](https://fontawesome.com/) 