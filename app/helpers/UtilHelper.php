<?php
/**
 * Classe UtilHelper
 * 
 * Funções utilitárias diversas para o sistema
 */
class UtilHelper {
    /**
     * Converte o valor monetário para formato numérico
     *
     * @param string $valor Valor no formato R$ 0,00
     * @return float Valor numérico
     */
    public static function formatarValorMonetario($valor) {
        // Remove 'R$' e espaços
        $valor = str_replace(['R$', ' '], '', $valor);
        // Substitui vírgula por ponto
        $valor = str_replace(',', '.', $valor);
        
        return (float) $valor;
    }
    
    /**
     * Formata um valor numérico para exibição monetária
     *
     * @param float $valor Valor numérico
     * @return string Valor formatado em R$
     */
    public static function exibirValorMonetario($valor) {
        return 'R$ ' . number_format($valor, 2, ',', '.');
    }
    
    /**
     * Gera um slug (URL amigável) a partir de uma string
     *
     * @param string $texto Texto a ser convertido
     * @return string Slug gerado
     */
    public static function gerarSlug($texto) {
        $texto = strtolower($texto);
        $texto = preg_replace('/[^a-z0-9\s-]/', '', $texto);
        $texto = preg_replace('/[\s-]+/', ' ', $texto);
        $texto = preg_replace('/\s/', '-', $texto);
        
        return $texto;
    }
    
    /**
     * Trunca um texto para o tamanho máximo especificado
     *
     * @param string $texto Texto a ser truncado
     * @param int $tamanho Tamanho máximo
     * @param string $sufixo Sufixo a ser adicionado quando truncado
     * @return string Texto truncado
     */
    public static function truncarTexto($texto, $tamanho, $sufixo = '...') {
        if (strlen($texto) <= $tamanho) {
            return $texto;
        }
        
        return substr($texto, 0, $tamanho) . $sufixo;
    }
    
    /**
     * Gera um token aleatório
     *
     * @param int $tamanho Tamanho do token
     * @return string Token gerado
     */
    public static function gerarToken($tamanho = 32) {
        return bin2hex(random_bytes($tamanho / 2));
    }
    
    /**
     * Redireciona para uma URL específica
     *
     * @param string $url URL para redirecionamento
     * @param int $statusCode Código de status HTTP
     */
    public static function redirecionar($url, $statusCode = 302) {
        header("Location: $url", true, $statusCode);
        exit;
    }
    
    /**
     * Verifica se a requisição foi feita via AJAX
     *
     * @return bool Retorna true se for uma requisição AJAX
     */
    public static function isAjax() {
        return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
    }
    
    /**
     * Obtém o IP real do usuário
     *
     * @return string Endereço IP
     */
    public static function getIpUsuario() {
        $ipKeys = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'];
        
        foreach ($ipKeys as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);
                    if (filter_var($ip, FILTER_VALIDATE_IP) !== false) {
                        return $ip;
                    }
                }
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }
} 