<?php declare(strict_types = 1);
namespace noxkiwi\core\Interfaces;

use noxkiwi\core\Cookie;

/**
 * I am
 *
 * @package      noxkiwi\core
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2016 - 2020 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
interface CookieInterface extends DatacontainerInterface
{
    /**
     * I will start the Cookie and set the sessionid if not set yet.
     *
     * @return       \noxkiwi\core\Cookie
     */
    public function start(): Cookie;

    /**
     * I will unset any data in the Cookie.
     */
    public function end(): void;
}
