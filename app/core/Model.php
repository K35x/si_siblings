<?php

class Model
{
    protected array $data = [];
    protected PDO $db;

    public function __construct()
    {
        $config = require __DIR__ . '/../config/database.php';

        $dsn = sprintf(
            '%s:host=%s;port=%d;dbname=%s;charset=%s',
            $config['driver'],
            $config['host'],
            $config['port'],
            $config['database'],
            $config['charset']
        );

        $this->db = new PDO(
            $dsn,
            $config['username'],
            $config['password'],
            $config['options']
        );
    }
}
