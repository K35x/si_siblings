<?php

class Model
{
    public const ORDER_STATUS_PENDING_PAYMENT = 'pending_payment';
    public const ORDER_STATUS_CONFIRMED = 'confirmed';
    public const ORDER_STATUS_IN_PROGRESS = 'in_progress';
    public const ORDER_STATUS_READY = 'ready';
    public const ORDER_STATUS_COMPLETED = 'completed';
    public const ORDER_STATUS_CANCELLED = 'cancelled';

    public const PAYMENT_STATUS_PENDING = 'pending';
    public const PAYMENT_STATUS_PAID = 'paid';
    public const PAYMENT_STATUS_VOID = 'void';
    public const PAYMENT_STATUS_REFUNDED = 'refunded';

    public const ROLE_KASIR = 'kasir';
    public const ROLE_OWNER = 'owner';

    public const FULFILLMENT_CUSTOM = 'custom';
    public const FULFILLMENT_READY_STOCK = 'ready_stock';

    public const SLEEVE_SHORT = 'short';
    public const SLEEVE_LONG = 'long';

    public const STOCK_IN_STOCK = 'IN_STOCK';
    public const STOCK_OUT_OF_STOCK = 'OUT_OF_STOCK';

    public const PAYMENT_STATE_UNPAID = 'unpaid';
    public const PAYMENT_STATE_PARTIAL = 'partial';
    public const PAYMENT_STATE_OVERPAID = 'overpaid';
    public const PAYMENT_STATE_PAID = 'paid';

    public const DP_THRESHOLD = 0.5;
    public const MAX_DESIGN_UPLOADS = 3;

    public const ALLOWED_ORDER_STATUSES = [
        self::ORDER_STATUS_PENDING_PAYMENT,
        self::ORDER_STATUS_CONFIRMED,
        self::ORDER_STATUS_IN_PROGRESS,
        self::ORDER_STATUS_READY,
        self::ORDER_STATUS_COMPLETED,
        self::ORDER_STATUS_CANCELLED,
    ];

    public const ALLOWED_PAYMENT_STATUSES = [
        self::PAYMENT_STATUS_PENDING,
        self::PAYMENT_STATUS_PAID,
        self::PAYMENT_STATUS_VOID,
        self::PAYMENT_STATUS_REFUNDED,
    ];

    public const ORDER_STATUS_MAP = [
        self::ORDER_STATUS_PENDING_PAYMENT => ['label' => 'Menunggu Konfirmasi', 'class' => 'status-pending', 'icon' => 'fa-clock'],
        self::ORDER_STATUS_CONFIRMED => ['label' => 'Dikonfirmasi', 'class' => 'status-confirm', 'icon' => 'fa-check'],
        self::ORDER_STATUS_IN_PROGRESS => ['label' => 'Sedang Diproses', 'class' => 'status-proses', 'icon' => 'fa-spinner'],
        self::ORDER_STATUS_READY => ['label' => 'Siap Diambil', 'class' => 'status-ready', 'icon' => 'fa-box-open'],
        self::ORDER_STATUS_COMPLETED => ['label' => 'Selesai', 'class' => 'status-selesai', 'icon' => 'fa-check-circle'],
        self::ORDER_STATUS_CANCELLED => ['label' => 'Dibatalkan', 'class' => 'status-batal', 'icon' => 'fa-times-circle'],
    ];

    public const BADGE_STATUS_MAP = [
        self::ORDER_STATUS_PENDING_PAYMENT => 'badge--pending',
        self::ORDER_STATUS_CONFIRMED => 'badge--info',
        self::ORDER_STATUS_IN_PROGRESS => 'badge--warning',
        self::ORDER_STATUS_READY => 'badge--success',
        self::ORDER_STATUS_COMPLETED => 'badge--success',
        self::ORDER_STATUS_CANCELLED => 'badge--danger',
    ];

    public const PAYMENT_METHODS = [
        'cash' => 'Tunai',
        'transfer' => 'Transfer',
        'qris' => 'QRIS',
        'card' => 'Kartu',
        'debit' => 'Debit',
        'credit' => 'Kredit',
    ];

    public const VALID_ORDER_TRANSITIONS = [
        self::ORDER_STATUS_PENDING_PAYMENT => [self::ORDER_STATUS_CONFIRMED, self::ORDER_STATUS_CANCELLED],
        self::ORDER_STATUS_CONFIRMED => [self::ORDER_STATUS_IN_PROGRESS, self::ORDER_STATUS_CANCELLED],
        self::ORDER_STATUS_IN_PROGRESS => [self::ORDER_STATUS_READY, self::ORDER_STATUS_CANCELLED],
        self::ORDER_STATUS_READY => [self::ORDER_STATUS_COMPLETED, self::ORDER_STATUS_CANCELLED],
        self::ORDER_STATUS_COMPLETED => [],
        self::ORDER_STATUS_CANCELLED => [],
    ];

    public const ORDER_TRANSITION_ROLES = [
        self::ORDER_STATUS_PENDING_PAYMENT => [self::ORDER_STATUS_CONFIRMED => [self::ROLE_KASIR]],
        self::ORDER_STATUS_CONFIRMED => [self::ORDER_STATUS_IN_PROGRESS => [self::ROLE_OWNER]],
        self::ORDER_STATUS_IN_PROGRESS => [self::ORDER_STATUS_READY => [self::ROLE_OWNER]],
        self::ORDER_STATUS_READY => [self::ORDER_STATUS_COMPLETED => [self::ROLE_KASIR, self::ROLE_OWNER]],
    ];

    protected array $data = [];
    protected PDO $db;

    public function getDb(): PDO
    {
        return $this->db;
    }

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
