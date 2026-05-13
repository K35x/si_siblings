<?php

class TransactionModel extends Model
{
    public function all(): array
    {
        return [
            [
                "id" => "#102",
                "customer" => "Ahmad",
                "status" => "Diproses",
                "total" => 12000000,
            ],
            [
                "id" => "#103",
                "customer" => "SMA 2",
                "status" => "Belum Lunas",
                "total" => 8500000,
            ],
            [
                "id" => "#104",
                "customer" => "SMA 2",
                "status" => "Belum Lunas",
                "total" => 8500000,
            ],
        ];
    }
}
