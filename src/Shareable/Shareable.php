<?php

declare(strict_types=1);

namespace Sassnowski\LaravelShareableModel\Shareable;

use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Shareable
{
    public function links(): MorphMany
    {
        return $this->morphMany(ShareableLink::class, 'shareable');
    }
}
