<?php declare(strict_types = 1);
namespace noxkiwi\core;

use noxkiwi\core\Interfaces\CookieInterface;
use noxkiwi\singleton\Singleton;
use function hash;
use function time;
use function uniqid;

/**
 * I am the basic cookie class
 *
 * @package      noxkiwi\core
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2016 - 2021 noxkiwi
 * @version      1.0.2
 * @link         https://nox.kiwi/
 */
abstract class Cookie extends Singleton implements CookieInterface
{
    protected const USE_DRIVER = true;

    /**
     * I will create a Session ID for the Session
     * @return string
     */
    final protected function makeSessionid(): string
    {
        return hash('sha512', uniqid((string)time()) . time());
    }

    /**
     * I will return the expiration of the Cookie.
     * @return int
     */
    final protected function getExpires(): int
    {
        return time() + 3600;
    }

    /**
     * I will return the Cookie's domain.
     * @return string
     */
    final protected function getDomain(): string
    {
        return '/';
    }
}
