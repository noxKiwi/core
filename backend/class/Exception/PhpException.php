<?php declare(strict_types = 1);
namespace noxkiwi\core\Exception;

use noxkiwi\core\Exception;

/**
 * I am the Exception that is used for all variety of Notice/Fatal/Errors.
 *
 * @package      noxkiwi\core
 * @example
 * set_errorhandler(function($error) {throw new PhpException("UNKNOWN ERROR", E_ERROR, $error);});
 *
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2019 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class PhpException extends Exception
{
}
