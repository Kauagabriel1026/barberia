<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Incluir controlador
require_once __DIR__ . '/../../app/controllers/UsuarioController.php';

// Log para debug
$debugLog = fopen(__DIR__ . '/../../debug_auth.log', 'a');
fwrite($debugLog, "==== " . date('Y-m-d H:i:s') . " ====\n");
fwrite($debugLog, "REQUEST_METHOD: " . $_SERVER['REQUEST_METHOD'] . "\n");
fwrite($debugLog, "GET params: " . print_r($_GET, true) . "\n");
fwrite($debugLog, "POST params: " . print_r($_POST, true) . "\n");
fwrite($debugLog, "Raw input: " . file_get_contents('php://input') . "\n");

// Responder a solicitações pré-voo
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Criar instância do controlador
$controller = new UsuarioController();

// Processar a requisição
$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        if ($action === 'login') {
            $controller->login();
        } else {
            http_response_code(400);
            echo json_encode(['mensagem' => 'Ação inválida']);
        }
        break;
    
    case 'GET':
        if ($action === 'logout') {
            $controller->logout();
        } else if ($action === 'check') {
            $logado = $controller->verificarLogin();
            echo json_encode(['logado' => $logado]);
        } else {
            http_response_code(400);
            echo json_encode(['mensagem' => 'Ação inválida']);
        }
        break;
        
    default:
        http_response_code(405);
        echo json_encode(['mensagem' => 'Método não permitido']);
        break;
}

// Fechar arquivo de log
fwrite($debugLog, "==== END ====\n\n");
fclose($debugLog);
?> 