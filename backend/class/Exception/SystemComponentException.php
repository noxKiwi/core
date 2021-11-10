<?php declare(strict_types = 1);
namespace noxkiwi\core\Exception;

use noxkiwi\core\Exception;

/**
 * I am an exception that raises a system error.
 *
 * @package      noxkiwi\core
 * @example
 * class MemcachedCache extends Cache
 * {
 *     public function __construct() {
 *         if(! class_exists('\Memcached')) {
 *             throw new SystemComponentException("Cache driver not available. Please install php-memcached.", E_ERROR);
 *         }
 *     }
 * }
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2016 - 2018 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class SystemComponentException extends Exception
{
}
