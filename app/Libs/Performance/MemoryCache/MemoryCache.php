<?php

namespace App\Libs\Performance\MemoryCache;

use Closure;

/**
 * @author  : Bilal ATLI
 * @date    : 30.03.2019 15:44
 * @mail    : <bilal@sistemkoin.com>, <ytbilalatli@gmail.com>
 * @phone   : +90 0-542-433-09-19
 *
 * @package App\Libs\Performance\MemoryCache;
 */
trait MemoryCache
{
    /**
     * @var bool
     */
    private static $_staticCacheActive = true;

    /**
     * Get Static Cache
     *
     * @param string  $cacheKey
     *
     * @param Closure $default
     *
     * @return mixed
     */
    protected static function cacheGet(string $cacheKey, Closure $default)
    {
        if (self::cacheExist($cacheKey) && self::$_staticCacheActive) {
            return MemoryCacheStorage::$_staticCache[$cacheKey];
        } else {
            $data = call_user_func($default);
            self::cacheSet($cacheKey, $data);
            return $data;
        }
    }

    /**
     * Flush Static Cache Data
     */
    protected static function cacheFlush()
    {
        MemoryCacheStorage::$_staticCache = [];
    }

    /**
     * Delete Cache & Return Affected Item Count
     *
     * @param string $cacheKey
     *
     * @return int
     */
    protected static function cacheDel(string $cacheKey)
    {
        if (self::cacheExist($cacheKey)) {
            unset(MemoryCacheStorage::$_staticCache[$cacheKey]);
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * Cache Set
     *
     * @param string $cacheKey
     * @param mixed  $data
     */
    protected static function cacheSet(string $cacheKey, $data)
    {
        MemoryCacheStorage::$_staticCache[$cacheKey] = $data;
    }

    /**
     * Get Cache Key
     *
     * @param string $method
     * @param mixed  ...$args
     *
     * @return string
     */
    protected static function cacheKey(string $method, ...$args)
    {
        $parts = [ $method ];
        foreach ($args as $arg) {
            if (!is_string($arg) && !filter_var($arg, FILTER_VALIDATE_INT)) {
                continue;
            }
            $parts[] = $arg;
        }

        $cacheKey = md5(implode('-', $parts));

        return $cacheKey;
    }

    /**
     * Cache Exist ?
     *
     * @param string $cacheKey
     *
     * @return bool
     */
    protected static function cacheExist(string $cacheKey)
    {
        return isset(MemoryCacheStorage::$_staticCache[$cacheKey]);
    }

    /**
     * Set Static Cache is Active
     *
     * @param bool $isActive
     */
    final public static function _setStaticCacheActive(bool $isActive)
    {
        self::$_staticCacheActive = $isActive;
    }

    /**
     * Get Cache List
     *
     * @return array
     */
    final protected static function cacheList()
    {
        return MemoryCacheStorage::$_staticCache;
    }
}
