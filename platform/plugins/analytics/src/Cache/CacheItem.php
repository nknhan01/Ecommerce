<?php

namespace Botble\Analytics\Cache;

use DateInterval;
use DateTimeImmutable;
use DateTimeInterface;
use Psr\Cache\CacheItemInterface;

class CacheItem implements CacheItemInterface
{
    /**
     * @var string
     */
    protected $key;

    /**
     * @var mixed|null
     */
    protected $value;

    /**
     * @var bool
     */
    protected $hit;

    /**
     * @var DateTimeInterface
     */
    protected $expires;

    /**
     * @param string $key
     * @param mixed $value
     * @param bool $hit
     */
    public function __construct($key, $value = null, $hit = false)
    {
        $this->key = $key;
        $this->hit = boolval($hit);
        $this->value = $this->hit ? $value : null;
    }

    /**
     * {@inheritDoc}
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * {@inheritDoc}
     */
    public function get()
    {
        return $this->value;
    }

    /**
     * {@inheritDoc}
     */
    public function isHit()
    {
        return $this->hit;
    }

    /**
     * {@inheritDoc}
     */
    public function set($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function expiresAt($expires)
    {
        if ($expires instanceof DateTimeInterface && !$expires instanceof DateTimeImmutable) {
            $timezone = $expires->getTimezone();
            $expires = DateTimeImmutable::createFromFormat('U', (string)$expires->getTimestamp(), $timezone);
            $expires->setTimezone($timezone);
        }

        $this->expires = $expires;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function expiresAfter($time)
    {
        if ($time === null) {
            $this->expires = null;

            return $this;
        }

        $this->expires = new DateTimeImmutable;

        if (!$time instanceof DateInterval) {
            $time = new DateInterval(sprintf('PT%sS', $time));
        }

        $this->expires = $this->expires->add($time);

        return $this;
    }

    /**
     * @return DateTimeInterface
     */
    public function getExpiresAt()
    {
        return $this->expires;
    }
}
