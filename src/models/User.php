<?php

class User 
{
    private $id;
    private $username;
    private $email;
    private $password;
    private $role;
    private $createdAt;
    private $updatedAt;
    
    // Getters et setters
    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }
    
    public function getUsername(): string
    {
        return $this->username;
    }
    
    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }
    
    public function getEmail(): string
    {
        return $this->email;
    }
    
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }
    
    // Pour le mot de passe, on le hash automatiquement
    public function getPassword(): string
    {
        return $this->password;
    }
    
    public function setPassword(string $password): self
    {
        // Si le mot de passe n'est pas déjà hashé, on le hash
        if (strlen($password) < 60) { // Longueur typique d'un hash bcrypt
            $this->password = password_hash($password, PASSWORD_DEFAULT);
        } else {
            $this->password = $password;
        }
        return $this;
    }
    
    /**
     * Définit le mot de passe hashé directement (sans le rehacher)
     * À utiliser UNIQUEMENT pour charger un mot de passe depuis la base de données
     * 
     * @param string $hashedPassword Le mot de passe déjà hashé
     * @return self
     */
    public function setHashedPassword(string $hashedPassword): self
    {
        $this->password = $hashedPassword;
        return $this;
    }
    
    /**
     * Vérifie si le mot de passe fourni correspond au mot de passe hashé stocké.
     * 
     * @param string $password Le mot de passe en clair à vérifier
     * @return bool True si le mot de passe est valide, false sinon
     */
    public function verifyPassword(string $password): bool
    {
        // Si le mot de passe stocké n'est pas défini, retourner false
        if (empty($this->password)) {
            return false;
        }
        
        // Fix pour les mots de passe qui n'utilisent pas le format standard de PHP
        if (strlen($this->password) !== 60 || substr($this->password, 0, 4) !== '$2y$') {
            // Le hash ne semble pas être au format bcrypt standard
            // Comparer directement (à utiliser seulement temporairement)
            return $password === $this->password;
        }
        
        // Vérification standard avec password_verify
        return password_verify($password, $this->password);
    }
    
    // Getters et setters pour le rôle
    public function getRole(): string
    {
        return $this->role;
    }
    
    public function setRole(string $role): self
    {
        $this->role = $role;
        return $this;
    }
    
    // Méthodes pour vérifier le rôle
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
    
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }
    
    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }
    
    public function setCreatedAt(string $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }
    
    public function getUpdatedAt(): ?string
    {
        return $this->updatedAt;
    }
    
    public function setUpdatedAt(string $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
    
    // Méthodes métier
    public function isValid(): bool
    {
        // Validation du modèle
        if (empty($this->username) || strlen($this->username) < 3) {
            return false;
        }
        
        if (empty($this->email) || !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        
        // Validation du mot de passe (uniquement pour les nouveaux utilisateurs ou changements de mot de passe)
        if (empty($this->password)) {
            return false;
        }
        
        return true;
    }
}