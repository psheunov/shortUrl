<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

abstract class QueryFilter
{

    /**
     * @var
     */
    protected $builder;

    /**
     * @var array
     */
    protected array $queryVars;

    public function __construct($queryVars)
    {
        $this->queryVars = $queryVars;
    }

    /**
     *
     * @param Builder $builder
     * @return Builder
     */
    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;

        foreach ($this->queryVars as $filter => $value) {
            if (method_exists($this, $filter)) {
                $this->$filter($value);
            }
        }
        return $this->builder;
    }
}
