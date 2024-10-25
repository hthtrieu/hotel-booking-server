<?php

namespace App\Dtos\Payment;

class CreateRefundRequestDTO
{
    public string $order_id;
    public float $amount;
    public string $transaction_type;
    public string $userName;
    // public string $transaction_date;
    //     "transaction_type":"02",
    // "order_id":"20241017152034",
    // "transaction_date":"20241017152122",
    // "user": "user",
    // "price":1000000

    public function __construct(string $order_id, float $amount, string $transaction_type, string $userName)
    {
        $this->amount = $amount;
        $this->transaction_type = $transaction_type;
        $this->userName = $userName;
        // $this->transaction_date = $transaction_date;
        $this->order_id = $order_id;
    }
}
