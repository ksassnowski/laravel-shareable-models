<?php

namespace Sassnowski\LaravelShareableModel\Shareable;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;

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
     * @var bool
     */
    private $shouldNotify = false;

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
     * @return $this
     */
    public function notifyOnVisit()
    {
        $this->shouldNotify = true;

        return $this;
    }

    /**
     * @return ShareableLink
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

    /**
     * @param string $uuid
     *
     * @return string
     */
    private function buildUrl($uuid)
    {
        if (!$this->prefix) {
            return url('/shared', [$uuid]);
        }

        return url('/shared', [$this->prefix, $uuid]);
    }
}
