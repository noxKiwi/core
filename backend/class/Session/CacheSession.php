<?php declare(strict_types = 1);
namespace noxkiwi\core\Session;

use Exception;
use noxkiwi\cache\Cache;
use noxkiwi\core\Cookie;
use noxkiwi\core\ErrorHandler;
use noxkiwi\core\Session;
use function count;
use function is_array;

/**
 * I am
 *
 * @package      noxkiwi\core
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2016 - 2018 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class CacheSession extends Session
{
    /**
     * I am the timeout, that is used for the sessions if they are not used (touched) on the cache service.
     *
     * @var int
     */
    private int $timeout = 1200;

    /**
     * @inheritDoc
     *
     * @param array $data
     *
     * @throws \noxkiwi\singleton\Exception\SingletonException
     * @return \noxkiwi\core\Session
     */
    public function start(array $data): Session
    {
        if (isset($data['timeout']) && is_numeric($data['timeout']) && $data['timeout'] > 0) {
            $this->timeout = (int)$data['timeout'];
        }
        Cache::getInstance()->set(self::getCachegroup(), 'SESSION', $data, Cache::DEFAULT_TIMEOUT);

        return $this;
    }

    public const CACHE_GROUP = Cache::DEFAULT_PREFIX . 'SESSION_';

    /**
     * I will return a basic cache group name for this class
     *
     * @throws \noxkiwi\singleton\Exception\SingletonException
     * @return string
     */
    private static function getCachegroup(): string
    {
        return self::CACHE_GROUP . Cookie::getInstance()->get(Session::SESSIONKEY);
    }

    /**
     * @inheritDoc
     * @throws \noxkiwi\singleton\Exception\SingletonException
     */
    public function destroy(): void
    {
        Cache::getInstance()->clearKey(self::getCachegroup(), 'SESSION');
    }

    /**
     * @inheritDoc
     */
    public function identify(): bool
    {
        $this->makeData();
        $this->put($this->get());

        return is_array($this->get()) && count($this->get()) > 2;
    }

    /**
     * @inheritDoc
     */
    public function put(array $data): void
    {
        $_SESSION = $data;
    }

    /**
     * I will set the data property of the instance at first
     *
     * @return void
     */
    private function makeData(): void
    {
        try {
            $data = Cache::getInstance()->get(self::getCachegroup(), 'SESSION');
            if (! is_array($data) || empty($data)) {
                return;
            }
            $this->put($data);
            Cache::getInstance()->set(self::getCachegroup(), 'SESSION', $this->get(), $this->timeout);
            $this->get();

            return;
        } catch (Exception $exception) {
            ErrorHandler::handleException($exception);

            return;
        }
    }

    /**
     * @inheritDoc
     */
    public function exists(string $key): bool
    {
        $this->makeData();

        return $this->get($key) === null;
    }

    /**
     * @inheritDoc
     *
     * @param string $key
     * @param mixed  $data
     *
     * @throws \noxkiwi\singleton\Exception\SingletonException
     */
    public function set(string $key, mixed $data): void
    {
        $this->makeData();
        Cache::getInstance()->set(self::getCachegroup(), 'SESSION', $this->get(), $this->timeout);
    }

    /**
     * @inheritDoc
     *
     * @throws \noxkiwi\singleton\Exception\SingletonException
     */
    public function add(array $data): void
    {
        foreach ($data as $key => $value) {
            $this->set($key, $value);
        }
    }

    /**
     * @inheritDoc
     */
    public function remove(string $key): void
    {
        $data = $this->get();
        unset($data[$key]);
        # $this->set($key)
    }

    /**
     * @inheritDoc
     */
    public function get(string $key = null, mixed $default = null): mixed
    {
        return 0;
    }
}
