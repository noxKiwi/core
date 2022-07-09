<?php declare(strict_types = 1);
namespace noxkiwi\core\Cookie;

use noxkiwi\core\Cookie;
use noxkiwi\core\Exception\CookieException;
use noxkiwi\core\Session;
use const E_NOTICE;

/**
 * I am the default cookie driver for the core.
 *
 * @package      noxkiwi\core
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2016 - 2018 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class CookieCookie extends Cookie
{
    /**
     * @inheritDoc
     * @throws \noxkiwi\core\Exception\CookieException
     */
    public function start(): Cookie
    {
        if (! $this->exists(Session::SESSIONKEY)) {
            $this->set(Session::SESSIONKEY, $this->makeSessionid());
        }
        if ($this->get(Session::SESSIONKEY) === null) {
            throw new CookieException('EXCEPTION_COOKIES_NOT_WRITABLE', E_NOTICE);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function end(): void
    {
        foreach ($this->get() as $key => $value) {
            $this->remove($key);
        }
    }

    /**
     * @inheritDoc
     *
     * @throws \noxkiwi\core\Exception\CookieException
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
    public function get(string $key = null, mixed $default = null): mixed
    {
        if (empty($key)) {
            return $_COOKIE;
        }

        return $_COOKIE[$key] ?? $default;
    }

    /**
     * @inheritDoc
     *
     * @throws \noxkiwi\core\Exception\CookieException
     */
    public function set(string $key, mixed $data): void
    {
        if ($data === null) {
            $this->remove($key);

            return;
        }
        setcookie($key, $data, $this->getExpires(), '/', $_SERVER['HTTP_HOST'], false, true);
        $_COOKIE[$key] = $data;
        if ($this->get($key) !== $data) {
            throw new CookieException('EXCEPTION_COOKIES_NOT_WRITABLE', E_NOTICE);
        }
    }

    /**
     * @inheritDoc
     */
    public function remove(string $key): void
    {
        unset($_COOKIE[$key]);
        setcookie($key, '', -1, '/');
    }

    /**
     * @inheritDoc
     */
    public function exists(string $key): bool
    {
        return isset($_COOKIE[$key]);
    }

    /**
     * @inheritDoc
     */
    public function put(array $data): void
    {
    }
}
