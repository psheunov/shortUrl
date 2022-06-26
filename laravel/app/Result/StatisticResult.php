<?php


namespace App\Result;


use JsonSerializable;

class StatisticResult implements JsonSerializable
{
    private int $status = 200;
    private int $uniqueViews = 0;
    private int $totalViews = 0;


    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status)
    {
        $this->status = $status;
    }

    public function setUniqueViews(int $views)
    {
        $this->uniqueViews = $views;
    }

    public function setTotalViews(int $views)
    {
        $this->totalViews = $views;
    }

    public function jsonSerialize()
    {
        return [
            'total_views' => $this->totalViews,
            'unique_views' => $this->uniqueViews
        ];
    }
}
