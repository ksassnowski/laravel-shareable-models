<?php

namespace Sassnowski\LaravelShareableModel\Shareable;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface ShareableInterface
{
    /**
     * @return MorphMany<ShareableLink>
     */
    public function links(): MorphMany;
}
