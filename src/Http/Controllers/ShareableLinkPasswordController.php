<?php

namespace Sassnowski\LaravelShareableModel\Http\Controllers;

use Illuminate\Http\Request;
use Sassnowski\LaravelShareableModel\Shareable\ShareableLink;

class ShareableLinkPasswordController
{
    /**
     * @param ShareableLink $shareableLink
     *
     * @return \Illuminate\View\View
     */
    public function show(ShareableLink $shareableLink)
    {
        return view('shareable-model::password', ['link' => $shareableLink]);
    }

    /**
     * @param Request       $request
     * @param ShareableLink $shareableLink
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, ShareableLink $shareableLink)
    {
        if (password_verify($request->get('password'), $shareableLink->password)) {
            session([$shareableLink->uuid => true]);
        }

        return redirect($shareableLink->url);
    }
}
