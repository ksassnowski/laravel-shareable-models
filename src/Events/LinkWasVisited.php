<?php

namespace Sassnowski\LaravelShareableModel\Events;

use Sassnowski\LaravelShareableModel\Shareable\ShareableLink;

class LinkWasVisited
{
    /**
     * @var ShareableLink
     */
    public $link;

    /**
     * LinkWasVisited constructor.
     *
     * @param ShareableLink $link
     */
    public function __construct(ShareableLink $link)
    {
        $this->link = $link;
    }
}
