<?php


namespace App\Helpers;


use App\Models\Link;
use App\Models\Visitor;
use App\Result\StatisticResult;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class VisitorStatistic
{
    public static function getStaticLink(Link $link): StatisticResult
    {
        $result = new StatisticResult();

        try {
            $result->setTotalViews($link->visitors()->count());
            $result->setUniqueViews($link->visitors()->select(['ip', 'user_agent'])->groupByRaw('ip, user_agent')->count());
        } catch (\Exception $e) {
            $result->setStatus(500);
        }

        return $result;
    }


    public static function getStatistic(Builder $visitors): StatisticResult
    {
        $result = new StatisticResult();

        try {
            $result->setTotalViews($visitors->count());
            $result->setUniqueViews($visitors->select(['ip', 'user_agent'])->groupByRaw('ip, user_agent')->count());
        } catch (\Exception $e) {
            $result->setStatus(500);
        }

        return $result;
    }
}
