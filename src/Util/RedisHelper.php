<?php
namespace App\Util;
 
use Redis;
 
class RedisHelper implements CacheHelper
{
    const MIN_TTL = 1;
    const MAX_TTL = 3600;
 
    /** @var Redis $redis */
    private $redis;
    private $host;
    private $port;
 
    public function __construct(string $redisHost, string $redisPort)
    {
        $this->host = $redisHost;
        $this->port = $redisPort;
    }
 
    /**
     * Get the value related to the specified key.
     */
    public function get(string $key)
    {
        $this->connect();
 
        return $this->redis->get($key);
    }
 
    /**
     * set(): Set persistent key-value pair.
     * setex(): Set non-persistent key-value pair.
     */
    public function set(array $options)
    {
        $this->connect();
 
        if (is_null($options['ttl'])) {
            $this->redis->set($options['key'], $options['value']);
        } else {
            $this->redis->setex($options['key'], $this->normaliseTtl($options['ttl']), $options['value']);
        }
    }
 
    /**
     * Returns 1 if the timeout was set.
     * Returns 0 if key does not exist or the timeout could not be set.
     */
    public function expire($key, $ttl = self::MIN_TTL)
    {
        $this->connect();
 
        return $this->redis->expire($key, $this->normaliseTtl($ttl));
    }
 
    /**
     * Removes the specified keys. A key is ignored if it does not exist.
     * Returns the number of keys that were removed.
     */
    public function delete(array $options)
    {
        $this->connect();
 
        return $this->redis->del($options['key']);
    }
 
    /**
     * Returns -2 if the key does not exist.
     * Returns -1 if the key exists but has no associated expire. Persistent.
     */
    public function getTtl($key)
    {
        $this->connect();
 
        return $this->redis->ttl($key);
    }
 
    /**
     * Returns 1 if the timeout was removed.
     * Returns 0 if key does not exist or does not have an associated timeout.
     */
    public function persist(array $options)
    {
        $this->connect();
 
        return $this->redis->persist($options['key']);
    }
 
    /**
     * The ttl is normalised to be 1 second to 1 hour.
     */
    private function normaliseTtl($ttl)
    {
        $ttl = ceil(abs($ttl));
 
        return ($ttl >= self::MIN_TTL && $ttl <= self::MAX_TTL) ? $ttl : self::MAX_TTL;
    }
 
    /**
     * Connect only if not connected.
     */
    private function connect()
    {
        if (!$this->redis || $this->redis->ping() != '+PONG') {
            $this->redis = new Redis();
            $this->redis->connect($this->host, $this->port);
        }
    }

    public function getAllKeys()
    {
        $this->connect();
 
        return $this->redis->keys("*");
    }
}