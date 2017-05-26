<?php

namespace Sassnowski\LaravelShareableModel\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Sassnowski\LaravelShareableModel\Shareable\Shareable;
use Sassnowski\LaravelShareableModel\Shareable\ShareableInterface;

class Upload extends Model implements ShareableInterface
{
    use Shareable;

    /**
     * @var array
     */
    protected $guarded = [];
}
