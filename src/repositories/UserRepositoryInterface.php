<?php

interface UserRepositoryInterface
{
    // Méthodes de base CRUD
    public function findAll(): array;
    public function findById(int $id): ?User;
    public function save(User $user): bool;
    public function delete(int $id): bool;
    
    // Méthodes spécifiques au domaine
    public function findByUsername(string $username): ?User;
    public function findByEmail(string $email): ?User;
}