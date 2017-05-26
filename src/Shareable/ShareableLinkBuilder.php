<?php

namespace Sassnowski\LaravelShareableModel\Shareable;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Hashids\HashidsInterface;

class ShareableLinkBuilder
{
    /**
     * @var ShareableInterface
     */
    private $entity;

    /**
     * @var string
     */
    private $prefix;

    /**
     * @var string
     */
    private $password;

    /**
     * @var bool
     */
    private $active = false;

    /**
     * @var Carbon
     */
    private $expirationDate;

    /**
     * ShareableLinkBuilder constructor.
     *
     * @param ShareableInterface $entity
     */
    public function __construct(ShareableInterface $entity)
    {
        $this->entity = $entity;
    }

    /**
     * @param string $prefix
     *
     * @return $this
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;

        return $this;
    }

    /**
     * @param string $password
     *
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return $this
     */
    public function setActive()
    {
        $this->active = true;

        return $this;
    }

    /**
     * @param Carbon $date
     *
     * @return $this
     */
    public function setExpirationDate(Carbon $date)
    {
        $this->expirationDate = $date;

        return $this;
    }

    /**
     * @return ShareableLink
     */
    public function build()
    {
        $uuid = Uuid::uuid4()->getHex();

        $hash = app()->make(HashidsInterface::class)->encodeHex($uuid);

        $link = new ShareableLink([
            'active' => $this->active,
            'password' => $this->password ? bcrypt($this->password) : null,
            'expires_at' => $this->expirationDate,
            'uuid' => $uuid,
            'hash' => $hash,
            'url' => $this->buildUrl($hash),
        ]);

        return $this->entity->links()->save($link);
    }

    /**
     * @param string $hash
     *
     * @return string
     */
    private function buildUrl($hash)
    {
        if (!$this->prefix) {
            return url('/shared', [$hash]);
        }

        return url('/shared', [$this->prefix, $hash]);
    }
}
