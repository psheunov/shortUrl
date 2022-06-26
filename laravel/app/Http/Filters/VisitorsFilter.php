<?php


namespace App\Http\Filters;


use Carbon\Carbon;

class VisitorsFilter extends QueryFilter
{
    public function from($value)
    {
        if ($value) {
            $this->builder->where('created_at', '>=', Carbon::create($value));
        }
    }

    public function to($value) {
        if ($value) {
            $this->builder->where('created_at', '<=', Carbon::create($value));
        }
    }
}
