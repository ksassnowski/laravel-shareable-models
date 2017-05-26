<?php

namespace Sassnowski\LaravelShareableModel\Shareable;

trait Shareable
{
    public function links()
    {
        return $this->morphMany(ShareableLink::class, 'shareable');
    }
}
