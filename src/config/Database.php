<?php

class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $port;
    private $conn;
    
    public function __construct() {
        $this->host = getenv('DB_HOST') ?: 'mariadb';
        $this->port = getenv('DB_PORT') ?: '3306';
        $this->db_name = getenv('DB_DATABASE') ?: 'mvc_project';
        $this->username = getenv('DB_USERNAME') ?: 'mvc_user';
        $this->password = getenv('DB_PASSWORD') ?: 'mvc_password';
    }
    
    public function getConnection() {
        $this->conn = null;
        
        try {
            $dsn = "mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name;
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->exec("set names utf8");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Erreur de connexion : " . $exception->getMessage();
        }
        
        return $this->conn;
    }
}