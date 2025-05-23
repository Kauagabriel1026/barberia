<?php
/**
 * Classe FormValidator
 * 
 * Responsável por validar dados de formulário
 */
class FormValidator {
    /**
     * Verifica se o telefone tem um formato válido (com ou sem formatação)
     *
     * @param string $telefone Número de telefone a ser validado
     * @return bool Retorna true se o telefone for válido
     */
    public static function validarTelefone($telefone) {
        // Remove caracteres não numéricos
        $telefone = preg_replace('/[^0-9]/', '', $telefone);
        
        // Verifica se o telefone tem entre 10 e 11 dígitos (com ou sem DDD)
        return (strlen($telefone) >= 10 && strlen($telefone) <= 11);
    }

    /**
     * Verifica se a data está em um formato válido e é uma data futura
     *
     * @param string $data Data a ser validada (formato Y-m-d)
     * @return bool Retorna true se a data for válida e futura
     */
    public static function validarData($data) {
        $dataObj = DateTime::createFromFormat('Y-m-d', $data);
        
        // Verifica se o formato da data é válido
        if ($dataObj && $dataObj->format('Y-m-d') === $data) {
            $hoje = new DateTime();
            $hoje->setTime(0, 0, 0);
            
            // A data precisa ser hoje ou no futuro
            return $dataObj >= $hoje;
        }
        
        return false;
    }

    /**
     * Verifica se o horário está em um formato válido
     *
     * @param string $hora Horário a ser validado (formato H:i)
     * @param string $data Data do agendamento (formato Y-m-d)
     * @return bool Retorna true se o horário for válido
     */
    public static function validarHora($hora, $data = null) {
        $horaObj = DateTime::createFromFormat('H:i', $hora);
        if (!$horaObj || $horaObj->format('H:i') !== $hora) {
            return false;
        }
        
        // Verificar se o horário está no passado para o dia atual
        if ($data === date('Y-m-d')) {
            $dataHoraAtual = new DateTime();
            $dataHoraAgendamento = new DateTime($data . ' ' . $hora);
            
            // Adicionar 30 minutos ao tempo atual para dar uma margem
            $dataHoraAtual->add(new DateInterval('PT30M'));
            
            // Se o horário de agendamento for anterior à hora atual + 30min, é inválido
            if ($dataHoraAgendamento < $dataHoraAtual) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Verifica se o email tem um formato válido
     *
     * @param string $email Email a ser validado
     * @return bool Retorna true se o email for válido
     */
    public static function validarEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Sanitiza uma string para evitar injeção de código
     *
     * @param string $string String a ser sanitizada
     * @return string String sanitizada
     */
    public static function sanitizarString($string) {
        return htmlspecialchars(trim($string), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Verifica se uma string não está vazia
     *
     * @param string $string String a ser verificada
     * @return bool Retorna true se a string não estiver vazia
     */
    public static function campoPreenchido($string) {
        return !empty(trim($string));
    }

    /**
     * Valida o campo de serviço contra uma lista de serviços permitidos
     *
     * @param string $servico Serviço a ser validado
     * @return bool Retorna true se o serviço for válido
     */
    public static function validarServico($servico) {
        $servicosPermitidos = [
            'Corte Clássico', 
            'Low Fade', 
            'High Fade', 
            'Militar', 
            'Social', 
            'Mullet'
        ];
        
        return in_array($servico, $servicosPermitidos);
    }
} 