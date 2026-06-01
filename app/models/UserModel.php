<?php

class UserModel extends Model
{
    public function findByUsername(string $username): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT user_id, username, password_hash, role FROM users WHERE username = :username AND is_active = 1 LIMIT 1',
        );
        $stmt->execute(['username' => $username]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }
}
