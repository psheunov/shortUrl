<?php

namespace App\Http\Controllers;

use App\Facades\Link as LinkFacade;
use App\Helpers\VisitorStatistic;
use App\Models\Link;
use App\Models\Visitor;
use Carbon\Carbon;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use \Exception;
use Illuminate\Routing\Redirector;

class LinkController extends Controller
{
    /**
     * @param Request $request
     * @return mixed|\Symfony\Component\HttpFoundation\ParameterBag|null
     */
    public function store(Request $request): mixed
    {
        try {
            $response = $request->json()->all();
            $response = isset($response['long_url']) ? [$response] : $response;

            return \request()->json(200, LinkFacade::create($response));
        } catch (Exception $e) {
            return \request()->json(200, ['message' => $e->getMessage()]);
        }
    }

    /**
     * @param Link $link
     * @param Request $request
     * @return mixed|\Symfony\Component\HttpFoundation\ParameterBag|null
     */
    public function patch(Link $link, Request $request): mixed
    {
        return \request()->json(200, LinkFacade::update($link, $request->json()->all()));
    }

    /**
     * @param Link $link
     * @return mixed|\Symfony\Component\HttpFoundation\ParameterBag|null
     */
    public function delete(Link $link): mixed
    {
        $result = $link->delete() ? 'success' : 'error';

        return \request()->json(200, ['result' => $result]);
    }

    /**
     * @param Link $link
     * @param Request $request
     * @return Redirector|Application|RedirectResponse
     */
    public function redirect(Link $link, Request $request): Redirector|Application|RedirectResponse
    {
        Visitor::create([
            'ip'         => $request->ip(),
            'user_agent' => $request->header('user-agent'),
            'link_id'    => $link->id
        ]);

        return redirect($link->long_url);
    }

    /**
     * @param Link $link
     * @return mixed|\Symfony\Component\HttpFoundation\ParameterBag|null
     */
    public function show(Link $link): mixed
    {
        $response = [
            'short_url' => $link->short_url,
            'long_url'  => $link->long_url,
            'title'     => $link->title,
            'tags'      => $link->tags
        ];
        return \request()->json(200, [$response]);
    }

    /**
     * @return mixed|\Symfony\Component\HttpFoundation\ParameterBag|null
     */
    public function links(): mixed
    {
        return \request()->json(200, Link::select(['id', 'long_url', 'short_url', 'title', 'tags'])->get());
    }

    /**
     * @param Link $link
     * @return mixed|\Symfony\Component\HttpFoundation\ParameterBag|null
     */
    public function getStatistic(Link $link): mixed
    {
        return \request()->json(200, VisitorStatistic::getStaticLink($link));
    }

    /**
     * @param Request $request
     * @return mixed|\Symfony\Component\HttpFoundation\ParameterBag|null
     */
    public function visitorsStatistics(Request $request): mixed
    {
        $from = $request->get('from') ? Carbon::create($request->get('form')) : null;
        $to = $request->get('to') ? Carbon::create($request->get('to')) : Carbon::now();

        return \request()->json(200, VisitorStatistic::getStatisticForPeriod($from, $to));
    }
}
