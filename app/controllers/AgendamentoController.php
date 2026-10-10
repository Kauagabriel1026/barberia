<?php
require_once __DIR__ . '/../models/Cliente.php';
require_once __DIR__ . '/../models/Agendamento.php';
require_once __DIR__ . '/../helpers/FormValidator.php';

class AgendamentoController {
    private $clienteModel;
    private $agendamentoModel;
    
    public function __construct() {
        $this->clienteModel = new Cliente();
        $this->agendamentoModel = new Agendamento();
    }
    
    public function criar() {
        // Verificar método da requisição
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['mensagem' => 'Método não permitido']);
            return;
        }
        
        // Receber dados do formulário
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Validar dados obrigatórios
        if (!isset($data['nome_cliente']) || !isset($data['telefone']) || 
            !isset($data['data']) || !isset($data['hora']) || !isset($data['servico'])) {
            http_response_code(400);
            echo json_encode(['mensagem' => 'Preencha todos os campos obrigatórios.']);
            return;
        }
        
        // Extrair os dados
        $nome_cliente = FormValidator::sanitizarString($data['nome_cliente']);
        $telefone = $data['telefone'];
        $data_agendamento = $data['data'];
        $hora = $data['hora'];
        $servico = $data['servico'];
        $valor = isset($data['valor']) ? (float)$data['valor'] : 0;
        $barba = isset($data['barba']) ? $data['barba'] : 'Não';
        
        // Validar os dados usando FormValidator
        if (!FormValidator::campoPreenchido($nome_cliente)) {
            http_response_code(400);
            echo json_encode(['mensagem' => 'Nome do cliente é obrigatório.']);
            return;
        }
        
        if (!FormValidator::validarTelefone($telefone)) {
            http_response_code(400);
            echo json_encode(['mensagem' => 'Telefone inválido. Use o formato (00) 00000-0000.']);
            return;
        }
        
        if (!FormValidator::validarData($data_agendamento)) {
            http_response_code(400);
            echo json_encode(['mensagem' => 'Selecione uma data válida (hoje ou futura).']);
            return;
        }
        
        // Verificar se a data está dentro do mês atual
        $dataAtual = new DateTime();
        $dataAgendamento = new DateTime($data_agendamento);
        $ultimoDiaMes = new DateTime('last day of this month');
        
        if ($dataAgendamento > $ultimoDiaMes) {
            http_response_code(400);
            echo json_encode(['mensagem' => 'Agendamentos só podem ser feitos para o mês atual.']);
            return;
        }
        
        if (!FormValidator::validarHora($hora, $data_agendamento)) {
            http_response_code(400);
            
            // Verificar se a rejeição é devido ao horário estar no passado
            if ($data_agendamento == date('Y-m-d')) {
                $horaAtual = new DateTime();
                $horaAgendamento = DateTime::createFromFormat('H:i', $hora);
                
                if ($horaAgendamento < $horaAtual) {
                    echo json_encode(['mensagem' => 'Não é possível agendar para um horário que já passou.']);
                    return;
                }
            }
            
            echo json_encode(['mensagem' => 'Horário inválido.']);
            return;
        }
        
        if (!FormValidator::validarServico($servico)) {
            http_response_code(400);
            echo json_encode(['mensagem' => 'Serviço inválido.']);
            return;
        }
        
        // Transformar barba em 0 ou 1
        $incluirBarba = $barba === 'Sim' ? 1 : 0;
        
        // Verificar disponibilidade do horário escolhido
        $duracao = $this->agendamentoModel->calcularDuracaoServico($incluirBarba);
        
        if ($this->agendamentoModel->verificarSobreposicaoHorarios($data_agendamento, $hora, $duracao)) {
            http_response_code(400);
            echo json_encode(['mensagem' => 'Horário não disponível. Por favor, escolha outro horário.']);
            return;
        }
        
        // Calcular valor total
        $valorServico = $valor;
        $valorBarba = $incluirBarba ? 25 : 0;
        $valorTotal = $valorServico + $valorBarba;
        
        // Verificar se o cliente existe
        $cliente = $this->clienteModel->getClienteByTelefone($telefone);
        $clienteId = 0;
        
        if (!$cliente) {
            // Criar novo cliente
            $clienteId = $this->clienteModel->criarCliente($nome_cliente, $telefone);
            if (!$clienteId) {
                http_response_code(500);
                echo json_encode(['mensagem' => 'Erro ao criar novo cliente.']);
                return;
            }
        } else {
            $clienteId = $cliente['id'];
            
            // Atualizar nome se for diferente
            if ($cliente['nome'] !== $nome_cliente) {
                $this->clienteModel->atualizarNome($clienteId, $nome_cliente);
            }
        }
        
        // Criar agendamento
        $agendamentoId = $this->agendamentoModel->criar(
            $clienteId,
            $data_agendamento,
            $hora,
            $servico,
            $valorTotal,
            $incluirBarba,
            $nome_cliente,
            $telefone
        );
        
        if ($agendamentoId) {
            http_response_code(201);
            echo json_encode(['mensagem' => 'Agendamento salvo com sucesso!', 'id' => $agendamentoId]);
        } else {
            http_response_code(500);
            echo json_encode(['mensagem' => 'Erro ao salvar o agendamento.']);
        }
    }
    
    public function listarPorData($data = null) {
        if (!$data) {
            $data = date('Y-m-d'); // Data atual
        }
        
        $agendamentos = $this->agendamentoModel->listarPorData($data);
        echo json_encode($agendamentos);
    }
    
    public function listarPorCliente($id) {
        $agendamentos = $this->agendamentoModel->listarPorCliente($id);
        echo json_encode($agendamentos);
    }
    
    public function visualizar($id) {
        $agendamento = $this->agendamentoModel->getById($id);
        
        if ($agendamento) {
            echo json_encode($agendamento);
        } else {
            http_response_code(404);
            echo json_encode(['mensagem' => 'Agendamento não encontrado.']);
        }
    }
    
    public function cancelar($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            http_response_code(405);
            echo json_encode(['mensagem' => 'Método não permitido']);
            return;
        }
        
        if ($this->agendamentoModel->cancelar($id)) {
            echo json_encode(['mensagem' => 'Agendamento cancelado com sucesso.']);
        } else {
            http_response_code(500);
            echo json_encode(['mensagem' => 'Erro ao cancelar o agendamento.']);
        }
    }

    public function listarTodos() {
        $agendamentos = $this->agendamentoModel->listarTodos();
        return $agendamentos;
    }
    
    /**
     * Lista os horários disponíveis para uma data específica
     *
     * @param string $data Data no formato Y-m-d
     */
    public function listarHorariosDisponiveis($data) {
        // Validar a data
        if (!FormValidator::validarData($data)) {
            http_response_code(400);
            echo json_encode(['mensagem' => 'Data inválida ou passada']);
            return;
        }
        
        // Verificar se a data está dentro do mês atual
        $dataAtual = new DateTime();
        $dataAgendamento = new DateTime($data);
        $ultimoDiaMes = new DateTime('last day of this month');
        
        if ($dataAgendamento > $ultimoDiaMes) {
            http_response_code(400);
            echo json_encode(['mensagem' => 'Agendamentos só podem ser feitos para o mês atual']);
            return;
        }
        
        // Obter horários disponíveis
        $horariosDisponiveis = $this->agendamentoModel->getHorariosDisponiveis($data);
        
        // Retornar resposta
        echo json_encode([
            'data' => $data,
            'horarios_disponiveis' => $horariosDisponiveis
        ]);
    }
}
?> 