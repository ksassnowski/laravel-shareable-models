<?php declare(strict_types=1);

namespace Sassnowski\LaravelShareableModel\Events;

use Sassnowski\LaravelShareableModel\Shareable\ShareableLink;

class LinkWasVisited
{
    /** @var ShareableLink  */
    public $link;

    public function __construct(ShareableLink $link)
    {
        $this->link = $link;
    }
}
