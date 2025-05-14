<?php
class Database {
    private $host = 'mariadb';
    private $db_name = 'mvc_project';
    private $username = 'mvc_user';
    private $password = 'mvc_password';
    private $conn;
    
    public function getConnection() {
        $this->conn = null;
        
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            echo "Erreur de connexion : " . $exception->getMessage();
        }
        
        return $this->conn;
    }
}