<?php declare(strict_types = 1);
namespace noxkiwi\core\Exception;

use noxkiwi\core\Exception;

/**
 * I am the Exception that is generated
 *
 * @package      noxkiwi\core
 * @example
 *       if (! Access::allowed()) {
 *           throw new AccessDeniedException('ACCESS DENIED FOR YOUR USER', E_WARNING);
 *       }
 *
 * @author       Jan Nox <jan.nox@pm.me>
 * @copyright    2021 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class ContextException extends Exception
{
}
