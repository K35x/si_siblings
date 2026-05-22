<?php

class UserModel extends Model
{
    public function findByUsername(string $username): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT user_id, username, password, role FROM user WHERE username = :username LIMIT 1",
        );
        $stmt->execute(["username" => $username]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }
}
