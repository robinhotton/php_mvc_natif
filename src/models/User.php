<?php

class User 
{
    private $id;
    private $username;
    private $email;
    private $createdAt;
    private $updatedAt;
    
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
    
    // Méthodes métier - la logique du modèle
    public function isValid(): bool
    {
        // Validation du nom d'utilisateur
        if (empty($this->username) || strlen($this->username) < 3) {
            return false;
        }
        
        // Validation de l'email
        if (empty($this->email) || !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        
        return true;
    }
}