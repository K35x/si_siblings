<?php

class Model
{
    public const ORDER_STATUS_PENDING = 'pending';
    public const ORDER_STATUS_PROCESSING = 'processing';
    public const ORDER_STATUS_DONE = 'done';
    public const ORDER_STATUS_CANCELLED = 'cancelled';

    public const PAYMENT_STATUS_PAID = 'paid';
    public const PAYMENT_STATUS_VOID = 'void';

    public const PAYMENT_METHOD_CASH = 'Tunai';
    public const PAYMENT_METHOD_BANK_TRANSFER = 'Transfer Bank';
    public const PAYMENT_METHOD_E_WALLET = 'E-Wallet';

    public const ALLOWED_ORDER_STATUSES = [
        self::ORDER_STATUS_PENDING,
        self::ORDER_STATUS_PROCESSING,
        self::ORDER_STATUS_DONE,
        self::ORDER_STATUS_CANCELLED,
    ];

    public const ALLOWED_PAYMENT_STATUSES = [
        self::PAYMENT_STATUS_PAID,
        self::PAYMENT_STATUS_VOID,
    ];

    public const ALLOWED_PAYMENT_METHODS = [
        self::PAYMENT_METHOD_CASH,
        self::PAYMENT_METHOD_BANK_TRANSFER,
        self::PAYMENT_METHOD_E_WALLET,
    ];

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
