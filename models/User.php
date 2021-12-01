<?php

class User
{
    public int $id;
    public string $name;
    public string $lastname;
    public string $email;
    public string $password;
    public string $image;
    public string $bio;
    public string $token;

    public function getFullName(User $u): string
    {
        $fullName = $u->name .' '. $u->lastname;
        return $fullName;
    }

    public function generateToken(): string
    {
        $token = bin2hex(random_bytes(50));

        return $token;
    }

    public function generatePassword(string $password): string
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        return $hash;
    }

    public function generateImageName(): string
    {
        $imageName = bin2hex(random_bytes(60)) .'.jpg';
        return $imageName;
    }
}

interface UserDaoInterface
{
    public function buildUser(array $data);
    public function create(User $user, bool $authUser = false);
    public function update(User $user, bool $redirect = true);
    public function verifyToken(bool $protected = false);
    public function setTokenToSession(string $token, bool $redirect = true);
    public function authenticateUser(string $email, string $password);
    public function emailExists(string $email);
    public function findById(int $id);
    public function findByEmail(string $email);
    public function findByToken(string $token);
    public function destroyToken();
    public function changePassword(User $user);
}
