<?php

class UserRepository implements UserRepositoryInterface
{
    private $db;
    
    public function __construct(Database $db)
    {
        $this->db = $db;
    }
    
    public function findAll(): array
    {
        $users = [];
        $stmt = $this->db->getConnection()->prepare("SELECT * FROM users");
        $stmt->execute();
        
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($results as $row) {
            $users[] = $this->createUserFromRow($row);
        }
        
        return $users;
    }
    
    public function findById(int $id): ?User
    {
        $stmt = $this->db->getConnection()->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$row) {
            return null;
        }
        
        return $this->createUserFromRow($row);
    }
    
    public function findByUsername(string $username): ?User
    {
        $stmt = $this->db->getConnection()->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$row) {
            return null;
        }
        
        return $this->createUserFromRow($row);
    }
    
    public function findByEmail(string $email): ?User
    {
        $stmt = $this->db->getConnection()->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$row) {
            return null;
        }
        
        return $this->createUserFromRow($row);
    }
    
    public function save(User $user): bool
    {
        // Si l'utilisateur a un ID, on fait un UPDATE, sinon un INSERT
        if ($user->getId()) {
            return $this->update($user);
        } else {
            return $this->insert($user);
        }
    }
    
    private function insert(User $user): bool
    {
        $stmt = $this->db->getConnection()->prepare(
            "INSERT INTO users (username, email, password, role, created_at) 
             VALUES (:username, :email, :password, :role, NOW())"
        );
        
        $username = $user->getUsername();
        $email = $user->getEmail();
        $password = $user->getPassword();
        $role = $user->getRole() ?: 'user'; // Valeur par défaut si non défini
        
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        $stmt->bindParam(':role', $role, PDO::PARAM_STR);
        
        if ($stmt->execute()) {
            $id = $this->db->getConnection()->lastInsertId();
            $user->setId($id);
            return true;
        }
        
        return false;
    }
    
    private function update(User $user): bool
    {
        $stmt = $this->db->getConnection()->prepare(
            "UPDATE users SET 
             username = :username, 
             email = :email,
             password = :password,
             role = :role
             WHERE id = :id"
        );
        
        $id = $user->getId();
        $username = $user->getUsername();
        $email = $user->getEmail();
        $password = $user->getPassword();
        $role = $user->getRole();
        
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        $stmt->bindParam(':role', $role, PDO::PARAM_STR);
        
        return $stmt->execute();
    }
    
    public function delete(int $id): bool
    {
        $stmt = $this->db->getConnection()->prepare("DELETE FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    private function createUserFromRow(array $row): User
    {
        $user = new User();
        $user->setId($row['id']);
        $user->setUsername($row['username']);
        $user->setEmail($row['email']);
        
        // Ajout des nouveaux champs
        if (isset($row['password'])) {
            // Utilisez une méthode spéciale pour définir le mot de passe hashé directement
            $user->setHashedPassword($row['password']); 
        }
        
        if (isset($row['role'])) {
            $user->setRole($row['role']);
        } else {
            $user->setRole('user'); // Valeur par défaut
        }
        
        if (isset($row['created_at'])) {
            $user->setCreatedAt($row['created_at']);
        }
        
        // Ajout de la date de modification
        if (isset($row['updated_at'])) {
            $user->setUpdatedAt($row['updated_at']);
        } else if (isset($row['created_at'])) {
            // Fallback si la colonne n'existe pas encore
            $user->setUpdatedAt($row['created_at']);
        }
        
        return $user;
    }
}