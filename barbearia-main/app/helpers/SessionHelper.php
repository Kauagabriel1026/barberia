<?php
/**
 * Classe SessionHelper
 * 
 * Fornece métodos para gerenciar sessões de usuário
 */
class SessionHelper {
    /**
     * Inicia a sessão se ainda não estiver iniciada
     */
    public static function iniciarSessao() {
        if (session_status() === PHP_SESSION_NONE) {
            // Define tempo de vida da sessão para 30 minutos
            ini_set('session.gc_maxlifetime', 1800); // 30 minutos em segundos
            session_start();
            
            // Verificar se precisa renovar o timestamp da sessão
            if (!isset($_SESSION['ultima_atividade'])) {
                $_SESSION['ultima_atividade'] = time();
            } else if (time() - $_SESSION['ultima_atividade'] > 1800) {
                // Se passou mais de 30 minutos, destruir a sessão
                self::destruir();
                session_start();
            } else {
                // Atualizar timestamp
                $_SESSION['ultima_atividade'] = time();
            }
        }
    }
    
    /**
     * Define uma variável de sessão
     *
     * @param string $chave Nome da variável
     * @param mixed $valor Valor a ser armazenado
     */
    public static function set($chave, $valor) {
        self::iniciarSessao();
        $_SESSION[$chave] = $valor;
    }
    
    /**
     * Obtém o valor de uma variável de sessão
     *
     * @param string $chave Nome da variável
     * @param mixed $padrao Valor padrão caso a variável não exista
     * @return mixed Valor da variável ou o valor padrão
     */
    public static function get($chave, $padrao = null) {
        self::iniciarSessao();
        return isset($_SESSION[$chave]) ? $_SESSION[$chave] : $padrao;
    }
    
    /**
     * Verifica se uma variável de sessão existe
     *
     * @param string $chave Nome da variável
     * @return bool Retorna true se a variável existir
     */
    public static function existe($chave) {
        self::iniciarSessao();
        return isset($_SESSION[$chave]);
    }
    
    /**
     * Remove uma variável de sessão
     *
     * @param string $chave Nome da variável
     */
    public static function remover($chave) {
        self::iniciarSessao();
        if (isset($_SESSION[$chave])) {
            unset($_SESSION[$chave]);
        }
    }
    
    /**
     * Destroi a sessão atual
     */
    public static function destruir() {
        self::iniciarSessao();
        session_unset();
        session_destroy();
        
        // Garantir que os cookies de sessão também sejam removidos
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
    }
    
    /**
     * Verifica se o usuário está logado
     *
     * @return bool Retorna true se o usuário estiver logado
     */
    public static function estaLogado() {
        self::iniciarSessao();
        return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
    }
    
    /**
     * Redireciona para a página de login se o usuário não estiver logado
     *
     * @param string $loginPage Caminho para a página de login
     */
    public static function verificarLogin($loginPage = '/admin/login.php') {
        if (!self::estaLogado()) {
            header("Location: $loginPage");
            exit;
        }
    }
    
    /**
     * Força login mesmo que já esteja logado
     * 
     * @param string $loginPage Caminho para a página de login
     * @return bool Retorna true se forçou o login
     */
    public static function forcarTelaLogin($loginPage = '/admin/login.php') {
        // Verifica o parâmetro force_login na URL
        if (isset($_GET['force_login']) && $_GET['force_login'] === '1') {
            self::destruir();
            header("Location: $loginPage");
            exit;
            return true;
        }
        return false;
    }
    
    /**
     * Define uma mensagem flash para ser exibida na próxima requisição
     *
     * @param string $tipo Tipo da mensagem (success, error, warning, info)
     * @param string $mensagem Conteúdo da mensagem
     */
    public static function setFlash($tipo, $mensagem) {
        self::iniciarSessao();
        $_SESSION['flash'] = [
            'tipo' => $tipo,
            'mensagem' => $mensagem
        ];
    }
    
    /**
     * Obtém a mensagem flash e a remove da sessão
     *
     * @return array|null Mensagem flash ou null se não existir
     */
    public static function getFlash() {
        self::iniciarSessao();
        if (isset($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);
            return $flash;
        }
        return null;
    }
} 