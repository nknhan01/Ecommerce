<?php

namespace Botble\Analytics\Cache;

use DateTimeImmutable;
use DateTimeInterface;
use Exception;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Contracts\Cache\Store;
use InvalidArgumentException;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;

class CacheItemPool implements CacheItemPoolInterface
{
    /**
     * @var Repository
     */
    protected $repository;

    /**
     * @var CacheItemInterface[]
     */
    protected $deferred = [];

    /**
     * @param Repository $repository
     */
    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Destructor.
     */
    public function __destruct()
    {
        $this->commit();
    }

    /**
     * {@inheritDoc}
     */
    public function getItem($key)
    {
        $this->validateKey($key);

        if (isset($this->deferred[$key])) {
            return clone($this->deferred[$key]);
        } elseif ($this->repository->has($key)) {
            return new CacheItem($key, unserialize($this->repository->get($key)), true);
        } else {
            return new CacheItem($key);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getItems(array $keys = [])
    {
        return array_combine($keys, array_map(function ($key) {
            return $this->getItem($key);
        }, $keys));
    }

    /**
     * {@inheritDoc}
     */
    public function hasItem($key)
    {
        $this->validateKey($key);

        if (isset($this->deferred[$key])) {
            $item = $this->deferred[$key];
            $expiresAt = $this->getExpiresAt($item);

            if (!$expiresAt) {
                return true;
            }

            return $expiresAt > new DateTimeImmutable;
        }

        return $this->repository->has($key);
    }

    /**
     * {@inheritDoc}
     */
    public function clear()
    {
        try {
            $this->deferred = [];
            $store = $this->repository;
            /* @var Store $store */
            $store->flush();
        } catch (Exception $exception) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function deleteItem($key)
    {
        $this->validateKey($key);

        unset($this->deferred[$key]);

        if (!$this->hasItem($key)) {
            return true;
        }

        return $this->repository->forget($key);
    }

    /**
     * {@inheritDoc}
     */
    public function deleteItems(array $keys)
    {
        // Validating all keys first.
        foreach ($keys as $key) {
            $this->validateKey($key);
        }

        $success = true;

        foreach ($keys as $key) {
            $success = $success && $this->deleteItem($key);
        }

        return $success;
    }

    /**
     * {@inheritDoc}
     */
    public function save(CacheItemInterface $item)
    {
        $expiresAt = $this->getExpiresAt($item);

        if (!$expiresAt) {
            try {
                $this->repository->forever($item->getKey(), serialize($item->get()));
            } catch (Exception $exception) {
                return false;
            }

            return true;
        }

        $lifetime = LifetimeHelper::computeLifetime($expiresAt);

        if ($lifetime <= 0) {
            $this->repository->forget($item->getKey());

            return false;
        }

        try {
            $this->repository->put($item->getKey(), serialize($item->get()), $lifetime);
        } catch (Exception $exception) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function saveDeferred(CacheItemInterface $item)
    {
        $expiresAt = $this->getExpiresAt($item);

        if ($expiresAt && ($expiresAt < new DateTimeImmutable)) {
            return false;
        }

        $item = (new CacheItem($item->getKey(), $item->get(), true))->expiresAt($expiresAt);

        $this->deferred[$item->getKey()] = $item;

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function commit()
    {
        $success = true;

        foreach ($this->deferred as $key => $item) {
            $success = $success && $this->save($item);
        }

        $this->deferred = [];

        return $success;
    }

    /**
     * @param string $key
     * @throws \Psr\Cache\InvalidArgumentException
     */
    protected function validateKey($key)
    {
        if (!is_string($key) || preg_match('#[{}\(\)/\\\\@:]#', $key)) {
            throw new InvalidArgumentException;
        }
    }

    /**
     * @param CacheItem $item
     * @return DateTimeInterface
     */
    protected function getExpiresAt(CacheItem $item)
    {
        return $item->getExpiresAt();
    }
}
