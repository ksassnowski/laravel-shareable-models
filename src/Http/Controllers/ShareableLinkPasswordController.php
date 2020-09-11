<?php declare(strict_types=1);

namespace Sassnowski\LaravelShareableModel\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Sassnowski\LaravelShareableModel\Shareable\ShareableLink;

class ShareableLinkPasswordController
{
    public function show(ShareableLink $shareableLink): View
    {
        return view('shareable-model::password', ['link' => $shareableLink]);
    }

    public function store(Request $request, ShareableLink $shareableLink): RedirectResponse
    {
        if (password_verify($request->get('password'), $shareableLink->password)) {
            session([$shareableLink->uuid => true]);
        }

        return redirect($shareableLink->url);
    }
}
