<?php declare(strict_types=1);

namespace Sassnowski\LaravelShareableModel\Shareable;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property string|null $password
 * @property string $uuid
 * @property string $url
 * @property boolean $active
 * @property string|null $expires_at
 * @property boolean $should_notify
 */
class ShareableLink extends Model
{
    /**
     * @var array<string, string>
     */
    protected $guarded = [];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'should_notify' => 'bool',
        'active' => 'bool',
    ];

    public static function buildFor(ShareableInterface $entity): ShareableLinkBuilder
    {
        return new ShareableLinkBuilder($entity);
    }

    /**
     * @return MorphTo<Model, ShareableLink>
     */
    public function shareable(): MorphTo
    {
        return $this->morphTo();
    }

    public function isActive(): bool
    {
        return (bool) $this->active;
    }

    public function isExpired(): bool
    {
        if ($this->expires_at === null) {
            return false;
        }

        return Carbon::now()->greaterThan(Carbon::parse($this->expires_at));
    }

    public function requiresPassword(): bool
    {
        return !is_null($this->password);
    }

    public function shouldNotify(): bool
    {
        return $this->should_notify;
    }
}
