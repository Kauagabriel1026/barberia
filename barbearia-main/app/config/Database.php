<?php
require_once __DIR__ . '/SQLiteDatabase.php';

class Database {
    private $sqliteDatabase;
    private $conn;

    public function __construct() {
        $this->sqliteDatabase = new SQLiteDatabase();
    }

    public function getConnection() {
        // Usar a implementação SQLite
        return $this->sqliteDatabase->getConnection();
    }
}
?> 