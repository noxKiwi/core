<?php declare(strict_types = 1);
namespace noxkiwi\core\Exception;

use noxkiwi\core\Exception;

/**
 * I am an Exception that handles cases that refer to a misconfiguration.
 * Examples:
 *      - Your code requires a bucket 'customerfiles' but that bucket doesn't exist.
 *      - Your code requires a rsKit client 'maincompany' but you don't even have any rsKit clients.
 *      - Your session driver is configured but the class file is missing.
 *
 * @package      noxkiwi\core
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2016 - 2018 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class ConfigurationException extends Exception
{
}
