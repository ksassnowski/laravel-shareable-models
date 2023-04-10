<?php

declare(strict_types=1);

namespace Sassnowski\LaravelShareableModel\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Sassnowski\LaravelShareableModel\Events\LinkWasVisited;
use Sassnowski\LaravelShareableModel\Shareable\ShareableLink;

class ValidateShareableLink
{
    /**
     * Handle an incoming request.
     *
     *
     * @return RedirectResponse|Response
     */
    public function handle(Request $request, Closure $next)
    {
        /** @var ShareableLink $link */
        $link = $request->route('shareable_link');

        if (! $link->isActive()) {
            return redirect(config('shareable-model.redirect_routes.inactive'));
        }

        if ($link->isExpired()) {
            return redirect(config('shareable-model.redirect_routes.expired'));
        }

        if ($link->requiresPassword() && ! session($link->uuid)) {
            return redirect(url(config('shareable-model.redirect_routes.password_protected'), $link->uuid));
        }

        $response = $next($request);

        if ($link->shouldNotify()) {
            event(new LinkWasVisited($link));
        }

        return $response;
    }
}
