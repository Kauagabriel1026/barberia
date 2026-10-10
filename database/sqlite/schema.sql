-- Tabela de clientes
CREATE TABLE IF NOT EXISTS clientes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nome TEXT NOT NULL,
    email TEXT,
    telefone TEXT NOT NULL UNIQUE
);

-- Tabela de agendamentos
CREATE TABLE IF NOT EXISTS agendamentos (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    cliente_id INTEGER,
    data TEXT NOT NULL,
    hora TEXT NOT NULL,
    servico TEXT NOT NULL,
    valor_total REAL DEFAULT 0,
    incluir_barba INTEGER DEFAULT 0,
    nome_cliente TEXT,
    telefone TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id)
);

-- Tabela de usuários administradores
CREATE TABLE IF NOT EXISTS admin_users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Inserir um usuário admin padrão (senha: admin123)
-- Hash gerado com password_hash('admin123', PASSWORD_DEFAULT)
INSERT OR REPLACE INTO admin_users (username, password) VALUES 
('admin', '$2y$10$YYN0PLzWYnRJG1/I5SZNJ.4w84Gjcpc7xsYlCeLA6qE5VFrSAB8v2'); 