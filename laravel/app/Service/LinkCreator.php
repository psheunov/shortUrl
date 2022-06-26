<?php


namespace App\Service;


use App\Result\Result;

interface LinkCreator
{
    public function create(array $data): Result;
}
