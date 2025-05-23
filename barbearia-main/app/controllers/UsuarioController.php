<?php
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../helpers/SessionHelper.php';

class UsuarioController {
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new Usuario();
        // Iniciar sessão
        SessionHelper::iniciarSessao();
    }

    public function login() {
        // Verificar método da requisição
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['mensagem' => 'Método não permitido']);
            return;
        }
        
        // Receber dados do formulário
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Se o formulário for enviado sem JSON
        if (empty($data)) {
            $data = $_POST;
        }
        
        // Validar dados obrigatórios
        if (!isset($data['username']) || !isset($data['password'])) {
            http_response_code(400);
            echo json_encode(['mensagem' => 'Usuário e senha são obrigatórios.']);
            return;
        }
        
        // Extrair os dados
        $username = $data['username'];
        $senha = $data['password'];
        
        // Verificar login
        $usuario = $this->usuarioModel->login($username, $senha);
        
        if ($usuario) {
            // Iniciar sessão
            SessionHelper::set('admin_logged_in', true);
            SessionHelper::set('admin_username', $usuario['username']);
            SessionHelper::set('admin_id', $usuario['id']);
            
            // Redirecionar ou retornar sucesso
            http_response_code(200);
            echo json_encode(['mensagem' => 'Login realizado com sucesso', 'redirect' => '/admin/dashboard.php']);
        } else {
            http_response_code(401);
            echo json_encode(['mensagem' => 'Usuário ou senha inválidos']);
        }
    }
    
    public function logout() {
        // Destruir a sessão
        SessionHelper::destruir();
        
        // Redirecionar para a página inicial do site
        header("Location: /public/index.php");
        exit;
    }
    
    public function verificarLogin() {
        return SessionHelper::estaLogado();
    }
    
    public function getUsuarioLogado() {
        return SessionHelper::get('admin_username');
    }
    
    public function exibirMensagemFlash() {
        $flash = SessionHelper::getFlash();
        if ($flash) {
            echo '<div class="alert alert-' . $flash['tipo'] . ' mt-3">' . $flash['mensagem'] . '</div>';
        }
    }
}
?> 