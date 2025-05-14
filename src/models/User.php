<?php
class User {
    private $conn;
    private $table_name = "users";
    
    public $id;
    public $username;
    public $email;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Méthode pour lire tous les utilisateurs
    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }
    
    // Autres méthodes CRUD...
}