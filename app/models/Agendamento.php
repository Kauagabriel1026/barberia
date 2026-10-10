<?php
require_once __DIR__ . '/../config/Database.php';

class Agendamento {
    private $conn;
    private $table_name = "agendamentos";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    /**
     * Verifica se um horário já está ocupado para a data especificada
     *
     * @param string $data Data do agendamento no formato Y-m-d
     * @param string $hora Hora do agendamento no formato H:i
     * @return bool Retorna true se o horário estiver ocupado
     */
    public function verificarHorarioOcupado($data, $hora) {
        // Verifica se existe algum agendamento neste horário
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . "
                WHERE data = :data AND hora = :hora";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":data", $data);
        $stmt->bindParam(":hora", $hora);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'] > 0;
    }
    
    /**
     * Verifica se há sobreposição de horários (considerando a duração)
     *
     * @param string $data Data do agendamento
     * @param string $hora Hora de início
     * @param int $duracao Duração em minutos
     * @return bool Retorna true se houver sobreposição
     */
    public function verificarSobreposicaoHorarios($data, $hora, $duracao) {
        // Converter hora de início para DateTime
        $dataHoraInicio = new DateTime($data . ' ' . $hora);
        
        // Calcular hora final do novo agendamento
        $dataHoraFim = clone $dataHoraInicio;
        $dataHoraFim->add(new DateInterval('PT' . $duracao . 'M'));
        
        // Buscar agendamentos para a data
        $agendamentos = $this->listarPorData($data);
        
        // Para cada agendamento existente, verificar sobreposição
        foreach ($agendamentos as $agendamento) {
            // Determinar a duração do agendamento existente
            $duracaoExistente = $agendamento['incluir_barba'] ? 60 : 30;
            
            // Calcular hora de início e fim do agendamento existente
            $agendamentoInicio = new DateTime($data . ' ' . $agendamento['hora']);
            $agendamentoFim = clone $agendamentoInicio;
            $agendamentoFim->add(new DateInterval('PT' . $duracaoExistente . 'M'));
            
            // Verificar se há sobreposição
            if (
                ($dataHoraInicio < $agendamentoFim && $dataHoraFim > $agendamentoInicio) ||
                ($dataHoraInicio == $agendamentoInicio) 
            ) {
                return true; // Há sobreposição
            }
        }
        
        return false; // Não há sobreposição
    }
    
    /**
     * Calcula a duração do serviço em minutos
     *
     * @param bool $incluirBarba Se deve incluir barba no serviço
     * @return int Duração em minutos
     */
    public function calcularDuracaoServico($incluirBarba) {
        // Duração base para corte: 30 minutos
        // Adicionar 30 minutos se incluir barba
        return $incluirBarba ? 60 : 30;
    }
    
    /**
     * Obtém horários disponíveis para uma data específica
     *
     * @param string $data Data no formato Y-m-d
     * @return array Lista de horários disponíveis
     */
    public function getHorariosDisponiveis($data) {
        // Todos os horários possíveis da barbearia (estendido até 20:00)
        $todosHorarios = [
            '09:00', '09:30', '10:00', '10:30', '11:00', '11:30',
            '13:30', '14:00', '14:30', '15:00', '15:30', '16:00',
            '16:30', '17:00', '17:30', '18:00', '18:30', '19:00',
            '19:30', '20:00'
        ];
        
        // Filtrar horários que já passaram se a data for hoje
        if ($data === date('Y-m-d')) {
            $horaAtual = new DateTime();
            // Adicionar 30 minutos de margem
            $horaAtual->add(new DateInterval('PT30M'));
            $horaLimite = $horaAtual->format('H:i');
            
            // Filtrar apenas horários a partir da hora atual + 30 min
            $todosHorarios = array_filter($todosHorarios, function($hora) use ($horaLimite) {
                return $hora > $horaLimite;
            });
        }
        
        // Se não houver horários disponíveis para hoje (todos já passaram)
        if ($data === date('Y-m-d') && empty($todosHorarios)) {
            return [];
        }
        
        // Buscar agendamentos para a data
        $agendamentos = $this->listarPorData($data);
        
        // Marcar horários ocupados ou sobrepostos
        $horariosOcupados = [];
        foreach ($agendamentos as $agendamento) {
            // Determinar a duração (30 min para corte, 60 min para corte+barba)
            $duracao = $agendamento['incluir_barba'] ? 60 : 30;
            
            // Adicionar o horário de início
            $horariosOcupados[] = $agendamento['hora'];
            
            // Se for corte com barba, bloquear o próximo slot também
            if ($agendamento['incluir_barba']) {
                // Calcular o próximo slot de 30 minutos
                $horaObj = DateTime::createFromFormat('H:i', $agendamento['hora']);
                $horaObj->add(new DateInterval('PT30M'));
                $proximoSlot = $horaObj->format('H:i');
                
                // Adicionar aos ocupados se estiver na lista de todos horários
                if (in_array($proximoSlot, $todosHorarios)) {
                    $horariosOcupados[] = $proximoSlot;
                }
            }
        }
        
        // Retornar apenas horários disponíveis
        return array_values(array_diff($todosHorarios, $horariosOcupados));
    }
    
    public function criar($cliente_id, $data, $hora, $servico, $valor_total, $incluir_barba, $nome_cliente, $telefone) {
        // Formatar o telefone para o padrão (XX) XXXXX-XXXX
        $telefone_limpo = preg_replace('/[^0-9]/', '', $telefone);
        if (strlen($telefone_limpo) === 11) {
            $telefone_formatado = '(' . substr($telefone_limpo, 0, 2) . ') ' . 
                                 substr($telefone_limpo, 2, 1) . ' ' . 
                                 substr($telefone_limpo, 3, 4) . '-' . 
                                 substr($telefone_limpo, 7);
        } else if (strlen($telefone_limpo) === 10) {
            $telefone_formatado = '(' . substr($telefone_limpo, 0, 2) . ') ' . 
                                 substr($telefone_limpo, 2, 4) . '-' . 
                                 substr($telefone_limpo, 6);
        } else {
            $telefone_formatado = $telefone;
        }
        
        $query = "INSERT INTO " . $this->table_name . "
                (cliente_id, data, hora, servico, valor_total, incluir_barba, nome_cliente, telefone)
                VALUES
                (:cliente_id, :data, :hora, :servico, :valor_total, :incluir_barba, :nome_cliente, :telefone)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":cliente_id", $cliente_id, PDO::PARAM_INT);
        $stmt->bindParam(":data", $data);
        $stmt->bindParam(":hora", $hora);
        $stmt->bindParam(":servico", $servico);
        $stmt->bindParam(":valor_total", $valor_total);
        $stmt->bindParam(":incluir_barba", $incluir_barba, PDO::PARAM_INT);
        $stmt->bindParam(":nome_cliente", $nome_cliente);
        $stmt->bindParam(":telefone", $telefone_formatado);
        
        if($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        
        return false;
    }
    
    public function listarPorData($data) {
        $query = "SELECT a.*, c.nome as cliente_nome 
                FROM " . $this->table_name . " a
                LEFT JOIN clientes c ON a.cliente_id = c.id
                WHERE a.data = :data
                ORDER BY a.hora ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":data", $data);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function listarPorCliente($cliente_id) {
        $query = "SELECT * FROM " . $this->table_name . "
                WHERE cliente_id = :cliente_id
                ORDER BY data DESC, hora ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":cliente_id", $cliente_id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getById($id) {
        $query = "SELECT a.*, c.nome as cliente_nome 
                FROM " . $this->table_name . " a
                LEFT JOIN clientes c ON a.cliente_id = c.id
                WHERE a.id = :id
                LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function cancelar($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    public function listarTodos() {
        $query = "SELECT a.*, c.nome as cliente_nome 
                FROM " . $this->table_name . " a
                LEFT JOIN clientes c ON a.cliente_id = c.id
                ORDER BY a.data ASC, a.hora ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?> 