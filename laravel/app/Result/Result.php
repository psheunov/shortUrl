<?php


namespace App\Result;

use JsonSerializable;

class Result implements JsonSerializable
{
    private int $status = 200;

    private array $message = [];

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status)
    {
        $this->status = $status;
    }


    public function getMessage(): array
    {
        return $this->message;
    }

    public function addMessage(string|array $message){
        $this->message[] = $message;
    }

    public function jsonSerialize()
    {
        return $this->message;
    }
}
