<?php


namespace App\Helpers;


use App\Models\Link;
use App\Models\Visitor;
use Carbon\Carbon;

class VisitorStatistic
{
    public static function getStaticLink(Link $link): array
    {
        $uniqueViews = $link->visitors()->select(['ip', 'user_agent'])->groupByRaw('ip, user_agent')->count();
        $totalViews = $link->visitors()->count();

        return ['total_views' => $totalViews, 'unique_views' => $uniqueViews];
    }

    /**
     * @param Carbon|null $from
     * @param Carbon|null $to
     * @return array
     */
    public static function getStatisticForPeriod(Carbon|null $from ,Carbon|null $to): array
    {
        if ($from) {
            $uniqueViews = Visitor::select(['ip', 'user_agent'])
                ->where('created_at', '>', $from)
                ->where('created_at', '<', $to)
                ->groupByRaw('ip, user_agent')
                ->count();

            $totalViews = Visitor::where('created_at', '>', $from)
                ->where('created_at', '<', $to)
                ->count();
        } else {
            $uniqueViews = Visitor::select(['ip', 'user_agent'])
                ->where('created_at', '<', $to)
                ->groupByRaw('ip, user_agent')
                ->count();

            $totalViews = Visitor::where('created_at', '<', $to)->count();
        }

        return  [
            'total_views'  => $totalViews,
            'unique_views' => $uniqueViews
        ];
    }
}
