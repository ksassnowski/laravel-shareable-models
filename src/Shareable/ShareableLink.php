<?php

namespace Sassnowski\LaravelShareableModel\Shareable;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ShareableLink extends Model
{
    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * Returns a new shareable link builder instance for the
     * provided entity.
     *
     * @param ShareableInterface $entity
     *
     * @return ShareableLinkBuilder
     */
    public static function buildFor(ShareableInterface $entity)
    {
        return new ShareableLinkBuilder($entity);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function shareable()
    {
        return $this->morphTo();
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return (bool) $this->active;
    }

    /**
     * @return bool
     */
    public function isExpired()
    {
        if (!$this->expires_at) {
            return false;
        }

        return Carbon::now()->greaterThan(Carbon::parse($this->expires_at));
    }

    /**
     * @return bool
     */
    public function requiresPassword()
    {
        return !is_null($this->password);
    }
}
