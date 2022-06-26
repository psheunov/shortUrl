<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Facades\Link as LinkFacade;
use App\Helpers\VisitorStatistic;
use App\Http\Filters\LinksFilter;
use App\Http\Filters\QueryFilter;
use App\Http\Filters\VisitorsFilter;
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
     * @return mixed
     * @throws Exception
     */
    public function store(Request $request): mixed
    {
        $data = $request->json()->all();
        $result = LinkFacade::create($data);

        return \request()->json($result->getStatus(), $result);
    }

    /**
     * @param Link $link
     * @param Request $request
     * @return mixed|\Symfony\Component\HttpFoundation\ParameterBag|null
     */
    public function patch(Link $link, Request $request): mixed
    {
        $result = LinkFacade::update($link, $request->json()->all());
        return \request()->json($result->getStatus(), $result);
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
        $data = [
            'short_url' => $link->short_url,
            'long_url'  => $link->long_url,
            'title'     => $link->title,
            'tags'      => $link->tags
        ];
        return \request()->json(200, [$data]);
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
     * @return mixed
     */
    public function getStatistic(Link $link): mixed
    {
        return \request()->json(200, VisitorStatistic::getStaticLink($link));
    }

    /**
     * @param Visitor $visitors
     * @param Request $request
     * @return mixed
     */
    public function visitorsStatistics(Visitor $visitors, Request $request): mixed
    {
        $result = VisitorStatistic::getStatistic($visitors->filter(new VisitorsFilter($request->all())));
        return \request()->json($result->getStatus(), $result);
    }
}
