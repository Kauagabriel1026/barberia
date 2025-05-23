<?php
// Configurar cabeçalhos para permitir CORS
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Responder a solicitações pré-voo
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Incluir arquivos necessários
require_once __DIR__ . '/../../app/controllers/AgendamentoController.php';

// Criar instância do controlador
$controller = new AgendamentoController();

// Processar a requisição
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$segments = explode('/', trim($uri, '/'));

// Determinar qual ação executar
$action = isset($segments[count($segments) - 1]) ? $segments[count($segments) - 1] : '';
$id = isset($segments[count($segments) - 2]) && is_numeric($segments[count($segments) - 2]) ? $segments[count($segments) - 2] : 0;

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if ($action === 'cliente' && $id > 0) {
            $controller->listarPorCliente($id);
        } else if ($action === 'data') {
            $data = isset($_GET['data']) ? $_GET['data'] : date('Y-m-d');
            $controller->listarPorData($data);
        } else if ($action === 'horarios-disponiveis') {
            $data = isset($_GET['data']) ? $_GET['data'] : date('Y-m-d');
            $controller->listarHorariosDisponiveis($data);
        } else if (is_numeric($action) && $action > 0) {
            $controller->visualizar($action);
        } else {
            http_response_code(400);
            echo json_encode(['mensagem' => 'API de agendamentos: recurso inválido']);
        }
        break;
        
    case 'POST':
        $controller->criar();
        break;
        
    case 'DELETE':
        if (is_numeric($action) && $action > 0) {
            $controller->cancelar($action);
        } else {
            http_response_code(400);
            echo json_encode(['mensagem' => 'API de agendamentos: ID de agendamento inválido']);
        }
        break;
        
    default:
        http_response_code(405);
        echo json_encode(['mensagem' => 'Método não permitido.']);
        break;
}
?> 