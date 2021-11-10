<?php declare(strict_types = 1);
namespace noxkiwi\core\Exception;

use noxkiwi\core\Exception;

/**
 * I am an exception that is thrown when a Session action fails.
 *
 * @package      noxkiwi\core
 * @example      Setting a value to session did not work out as expected.
 *
 * @example      The method session_start() returns false.
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2019 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class SessionException extends Exception
{
}
