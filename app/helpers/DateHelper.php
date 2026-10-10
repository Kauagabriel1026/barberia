<?php
/**
 * Classe DateHelper
 * 
 * Funções auxiliares para manipulação de datas e horários
 */
class DateHelper {
    /**
     * Formata uma data para exibição no formato brasileiro (dd/mm/yyyy)
     *
     * @param string $data Data no formato Y-m-d
     * @return string Data formatada
     */
    public static function formatarData($data) {
        $dataObj = DateTime::createFromFormat('Y-m-d', $data);
        if (!$dataObj) {
            return $data;
        }
        return $dataObj->format('d/m/Y');
    }
    
    /**
     * Formata uma data e hora para exibição no formato brasileiro
     *
     * @param string $dataHora Data e hora
     * @return string Data e hora formatadas
     */
    public static function formatarDataHora($dataHora) {
        $dataObj = new DateTime($dataHora);
        return $dataObj->format('d/m/Y H:i');
    }
    
    /**
     * Verifica se uma data é futura
     *
     * @param string $data Data no formato Y-m-d
     * @return bool Retorna true se a data for futura
     */
    public static function ehDataFutura($data) {
        $dataObj = DateTime::createFromFormat('Y-m-d', $data);
        if (!$dataObj) {
            return false;
        }
        
        $hoje = new DateTime();
        $hoje->setTime(0, 0, 0);
        
        return $dataObj > $hoje;
    }
    
    /**
     * Verifica se uma data é hoje ou futura
     *
     * @param string $data Data no formato Y-m-d
     * @return bool Retorna true se a data for hoje ou futura
     */
    public static function ehDataHojeOuFutura($data) {
        $dataObj = DateTime::createFromFormat('Y-m-d', $data);
        if (!$dataObj) {
            return false;
        }
        
        $hoje = new DateTime();
        $hoje->setTime(0, 0, 0);
        
        return $dataObj >= $hoje;
    }
    
    /**
     * Obter lista de horários disponíveis para agendamento
     *
     * @param array $horariosOcupados Array com horários já ocupados
     * @return array Lista de horários disponíveis
     */
    public static function getHorariosDisponiveis($horariosOcupados = []) {
        $todosHorarios = [
            '09:00', '09:30', '10:00', '10:30', '11:00', '11:30',
            '13:30', '14:00', '14:30', '15:00', '15:30', '16:00',
            '16:30', '17:00', '17:30', '18:00', '18:30', '19:00',
            '19:30', '20:00'
        ];
        
        return array_values(array_diff($todosHorarios, $horariosOcupados));
    }
    
    /**
     * Verifica se um horário está em um intervalo comercial válido
     *
     * @param string $hora Horário no formato H:i
     * @return bool Retorna true se o horário for válido
     */
    public static function ehHorarioComercial($hora) {
        $horaObj = DateTime::createFromFormat('H:i', $hora);
        if (!$horaObj) {
            return false;
        }
        
        $hora = (int)$horaObj->format('H');
        $minuto = (int)$horaObj->format('i');
        
        // Verifica se o horário está entre 9h e 20h
        if ($hora < 9 || $hora > 20) {
            return false;
        }
        
        // Se for 20h, o minuto precisa ser 0
        if ($hora === 20 && $minuto > 0) {
            return false;
        }
        
        // Verifica se é horário de almoço (12h às 13h30)
        if ($hora == 12 || ($hora == 13 && $minuto < 30)) {
            return false;
        }
        
        // Verifica se os minutos são 0 ou 30 (agendamentos de meia em meia hora)
        return $minuto === 0 || $minuto === 30;
    }
    
    /**
     * Retorna a data atual no formato Y-m-d
     *
     * @return string Data atual
     */
    public static function getDataAtual() {
        return date('Y-m-d');
    }
    
    /**
     * Adiciona dias a uma data
     *
     * @param string $data Data no formato Y-m-d
     * @param int $dias Número de dias a adicionar
     * @return string Nova data
     */
    public static function adicionarDias($data, $dias) {
        $dataObj = new DateTime($data);
        $dataObj->add(new DateInterval("P{$dias}D"));
        return $dataObj->format('Y-m-d');
    }
} 