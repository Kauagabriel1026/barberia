<?php
class SQLiteDatabase {
    private $db_file;
    private $conn;

    public function __construct() {
        // Obtem o caminho absoluto para o diretório do projeto
        $project_root = realpath(__DIR__ . '/../../');
        
        // Define o arquivo do banco de dados na pasta database/sqlite
        $this->db_file = $project_root . "/database/sqlite/barbearia.db";
        
        // Cria o diretório se não existir
        $db_dir = dirname($this->db_file);
        if (!file_exists($db_dir)) {
            mkdir($db_dir, 0777, true);
        }
        
        // Log para debug
        $debugLog = fopen($project_root . '/debug_database.log', 'a');
        fwrite($debugLog, date('Y-m-d H:i:s') . " - DB Path: " . $this->db_file . "\n");
        fwrite($debugLog, date('Y-m-d H:i:s') . " - DB Dir exists: " . (file_exists($db_dir) ? 'Yes' : 'No') . "\n");
        fclose($debugLog);
    }

    public function getConnection() {
        $this->conn = null;
        $logFile = realpath(__DIR__ . '/../../') . '/debug_database.log';
        $log = fopen($logFile, 'a');

        try {
            // Conexão com SQLite
            fwrite($log, date('Y-m-d H:i:s') . " - Connecting to SQLite\n");
            $this->conn = new PDO("sqlite:" . $this->db_file);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Habilitar chaves estrangeiras
            $this->conn->exec('PRAGMA foreign_keys = ON');
            fwrite($log, date('Y-m-d H:i:s') . " - Connected successfully\n");
            
            // Verificar se as tabelas existem, caso contrário, criar
            $this->initializeDatabase();
            
        } catch(PDOException $exception) {
            fwrite($log, date('Y-m-d H:i:s') . " - Error: " . $exception->getMessage() . "\n");
            echo "Erro de conexão: " . $exception->getMessage();
        }
        
        fclose($log);
        return $this->conn;
    }
    
    private function initializeDatabase() {
        $logFile = realpath(__DIR__ . '/../../') . '/debug_database.log';
        $log = fopen($logFile, 'a');
        
        // Verifica se o banco de dados já está inicializado
        fwrite($log, date('Y-m-d H:i:s') . " - Checking if tables exist\n");
        $result = $this->conn->query("SELECT name FROM sqlite_master WHERE type='table' AND name='admin_users'");
        $exists = !empty($result->fetchAll());
        
        fwrite($log, date('Y-m-d H:i:s') . " - Tables exist: " . ($exists ? 'Yes' : 'No') . "\n");
        
        if (!$exists) {
            // Carrega e executa o schema SQL
            $schemaFile = __DIR__ . "/../../database/sqlite/schema.sql";
            fwrite($log, date('Y-m-d H:i:s') . " - Loading schema from: " . $schemaFile . "\n");
            fwrite($log, date('Y-m-d H:i:s') . " - Schema file exists: " . (file_exists($schemaFile) ? 'Yes' : 'No') . "\n");
            
            $schema = file_get_contents($schemaFile);
            fwrite($log, date('Y-m-d H:i:s') . " - Schema content length: " . strlen($schema) . " bytes\n");
            
            // Executar cada query separadamente para SQLite
            $queries = explode(';', $schema);
            foreach ($queries as $query) {
                $query = trim($query);
                if (!empty($query)) {
                    try {
                        $this->conn->exec($query);
                        fwrite($log, date('Y-m-d H:i:s') . " - Executed query successfully\n");
                    } catch (PDOException $e) {
                        fwrite($log, date('Y-m-d H:i:s') . " - Error executing query: " . $e->getMessage() . "\n");
                    }
                }
            }
        }
        
        fclose($log);
    }
}
?> 