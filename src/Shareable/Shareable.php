<?php declare(strict_types=1);

namespace Sassnowski\LaravelShareableModel\Shareable;

trait Shareable
{
    public function links()
    {
        return $this->morphMany(ShareableLink::class, 'shareable');
    }
}
