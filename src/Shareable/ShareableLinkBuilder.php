<?php declare(strict_types=1);

namespace Sassnowski\LaravelShareableModel\Shareable;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Type\Hexadecimal;

class ShareableLinkBuilder
{
    /** @var ShareableInterface  */
    private $entity;

    /** @var string  */
    private $prefix = '';

    /** @var string|null */
    private $password = null;

    /** @var bool  */
    private $active = false;

    /** @var Carbon|null  */
    private $expirationDate = null;

    /** @var bool  */
    private $shouldNotify = false;

    /** @var string */
    private $baseUrl;

    public function __construct(ShareableInterface $entity)
    {
        $this->entity = $entity;
        $this->baseUrl = config('shareable-model.base_url');
    }

    public function setPrefix(string $prefix): self
    {
        $this->prefix = $prefix;

        return $this;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function setActive(): self
    {
        $this->active = true;

        return $this;
    }

    public function setExpirationDate(Carbon $date): self
    {
        $this->expirationDate = $date;

        return $this;
    }

    public function notifyOnVisit(): self
    {
        $this->shouldNotify = true;

        return $this;
    }

    /**
     * @return false|ShareableLink
     */
    public function build()
    {
        $uuid = Uuid::uuid4()->getHex();

        $link = new ShareableLink([
            'active' => $this->active,
            'password' => $this->password ? bcrypt($this->password) : null,
            'expires_at' => $this->expirationDate,
            'uuid' => $uuid,
            'url' => $this->buildUrl($uuid),
            'should_notify' => $this->shouldNotify
        ]);

        return $this->entity->links()->save($link);
    }

    private function buildUrl(Hexadecimal $uuid): string
    {
        if (!$this->prefix) {
            return url($this->baseUrl, [$uuid]);
        }

        return url($this->baseUrl, [$this->prefix, $uuid]);
    }
}
