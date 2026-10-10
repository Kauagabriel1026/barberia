<?php
require_once __DIR__ . '/../config/Database.php';

class Cliente {
    private $conn;
    private $table_name = "clientes";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function create($nome, $email, $telefone) {
        $query = "INSERT INTO " . $this->table_name . "
                (nome, email, telefone)
                VALUES
                (:nome, :email, :telefone)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":nome", $nome);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":telefone", $telefone);

        if($stmt->execute()) {
            return $this->conn->lastInsertId();
        }

        return false;
    }

    public function getByEmail($email) {
        $query = "SELECT * FROM " . $this->table_name . "
                WHERE email = :email
                LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $query = "SELECT * FROM " . $this->table_name . "
                WHERE id = :id
                LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getClienteByTelefone($telefone) {
        $query = "SELECT id, nome FROM " . $this->table_name . " 
                WHERE telefone = :telefone
                LIMIT 1";
                
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":telefone", $telefone);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function criarCliente($nome, $telefone) {
        $query = "INSERT INTO " . $this->table_name . " 
                (nome, telefone) 
                VALUES 
                (:nome, :telefone)";
                
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nome", $nome);
        $stmt->bindParam(":telefone", $telefone);
        
        if($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        
        return false;
    }
    
    public function atualizarNome($id, $nome) {
        $query = "UPDATE " . $this->table_name . " 
                SET nome = :nome 
                WHERE id = :id";
                
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nome", $nome);
        $stmt->bindParam(":id", $id);
        
        return $stmt->execute();
    }
}
?> 