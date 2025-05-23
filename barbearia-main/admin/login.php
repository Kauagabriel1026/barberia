<?php
require_once __DIR__ . '/../app/helpers/SessionHelper.php';
require_once __DIR__ . '/../app/config/Database.php';

// Verificar se está sendo solicitado um logout forçado
SessionHelper::forcarTelaLogin();

// Redirecionar se já estiver logado
if (SessionHelper::estaLogado()) {
    header('Location: dashboard.php');
    exit;
}

// Mensagem de erro padrão
$mensagem = '';

// Processar o formulário quando enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Validação simples
    if (empty($username) || empty($password)) {
        $mensagem = '<div class="alert alert-danger">Preencha todos os campos</div>';
    } else {
        // Para o usuário admin, aceitar diretamente
        if ($username === 'admin' && $password === 'admin123') {
            // Forçar login para admin
            SessionHelper::set('admin_logged_in', true);
            SessionHelper::set('admin_username', 'admin');
            SessionHelper::set('admin_id', 1);
            
            // Redirecionar para o dashboard
            header('Location: dashboard.php');
            exit;
        } else {
            // Verificar outros usuários no banco de dados
            try {
                $database = new Database();
                $conn = $database->getConnection();
                
                $stmt = $conn->prepare("SELECT id, username, password FROM admin_users WHERE username = :username");
                $stmt->bindParam(':username', $username);
                $stmt->execute();
                
                if ($stmt->rowCount() > 0) {
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    if (password_verify($password, $user['password'])) {
                        SessionHelper::set('admin_logged_in', true);
                        SessionHelper::set('admin_username', $user['username']);
                        SessionHelper::set('admin_id', $user['id']);
                        
                        header('Location: dashboard.php');
                        exit;
                    } else {
                        $mensagem = '<div class="alert alert-danger">Senha incorreta</div>';
                    }
                } else {
                    $mensagem = '<div class="alert alert-danger">Usuário não encontrado</div>';
                }
            } catch (PDOException $e) {
                $mensagem = '<div class="alert alert-danger">Erro ao conectar: ' . $e->getMessage() . '</div>';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .login-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .login-title {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }
        .brand-icon {
            color: #FFD166;
            font-size: 2rem;
            margin-bottom: 10px;
        }
        .btn-primary {
            background-color: #343a40;
            border-color: #343a40;
        }
        .btn-primary:hover {
            background-color: #23272b;
            border-color: #23272b;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <div class="text-center mb-4">
                <i class="bi bi-scissors brand-icon"></i>
                <h2 class="login-title">Login Administrativo</h2>
            </div>
            
            <?php if (!empty($mensagem)) echo $mensagem; ?>
            
            <form method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Usuário</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Senha</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100 mt-3">Entrar</button>
                <div class="text-center mt-3">
                    <a href="../public/index.php" class="text-decoration-none">
                        <i class="bi bi-arrow-left"></i> Voltar ao site
                    </a>
                </div>
            </form>
            <div class="mt-3 text-center">
                <small class="text-muted">Para acesso imediato, use <strong>admin</strong> / <strong>admin123</strong></small>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 