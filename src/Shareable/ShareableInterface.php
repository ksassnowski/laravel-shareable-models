<?php

namespace Sassnowski\LaravelShareableModel\Shareable;

use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOneOrMany;

interface ShareableInterface
{
    /**
     * @return MorphOne|MorphMany|MorphOneOrMany
     */
    public function links();
}
