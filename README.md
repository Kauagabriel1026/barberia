# Sistema de Agendamento para Barbearia

Aplicação web em PHP para uma barbearia receber agendamentos online e gerenciá-los por um painel administrativo. Segue a arquitetura MVC, expõe uma API REST e usa SQLite, então roda sem instalar servidor de banco de dados.

## Funcionalidades

- Agendamento online pelo cliente, com consulta de horários disponíveis por data
- Painel administrativo com login para acompanhar e gerenciar os agendamentos
- Cadastro de clientes e histórico de agendamentos por cliente
- API REST em JSON para agendamentos e autenticação
- Senhas armazenadas com `password_hash`
- Layout responsivo com TailwindCSS

## Tecnologias

- PHP 7.4+ com PDO
- SQLite
- HTML, CSS, JavaScript e TailwindCSS

## Como rodar

Pré-requisito: PHP 7.4 ou superior com a extensão `pdo_sqlite` habilitada.

```bash
git clone https://github.com/Kauagabriel1026/barberia.git
cd barberia/barbearia-main
php server.php
```

Depois acesse:

- Site: `http://localhost:8000/`
- Painel administrativo: `http://localhost:8000/admin/login.php`

O banco SQLite é criado automaticamente no primeiro acesso, em `database/sqlite/barbearia.db`, com um usuário administrador de demonstração (`admin` / `admin123`). Troque essa senha antes de usar o sistema fora do ambiente local.

Se a porta 8000 estiver ocupada, altere a variável `$port` em `server.php`.

## Estrutura

```
barbearia-main/
├── admin/            # Login, logout e painel administrativo
├── api/endpoints/    # Endpoints REST (agendamento, auth)
├── app/
│   ├── config/       # Conexão com o banco (PDO + SQLite)
│   ├── controllers/  # Regras de agendamento e de usuário
│   ├── helpers/      # Datas, validação de formulário, sessão
│   ├── models/       # Agendamento, Cliente, Usuario
│   └── views/        # Partes comuns das páginas
├── database/         # Esquemas SQL (SQLite e MySQL)
├── public/           # Página inicial, CSS e páginas de erro
└── server.php        # Sobe o servidor embutido do PHP
```

## API

Endpoints de agendamento (`api/endpoints/agendamento.php`), com respostas em JSON:

| Método | Rota | O que faz |
| --- | --- | --- |
| GET | `/{id}` | Retorna um agendamento |
| GET | `/data?data=AAAA-MM-DD` | Lista os agendamentos de uma data |
| GET | `/horarios-disponiveis?data=AAAA-MM-DD` | Lista os horários livres de uma data |
| GET | `/{id}/cliente` | Lista os agendamentos de um cliente |

A API também aceita `POST` e `DELETE` para criar e cancelar agendamentos.

## Licença

MIT
