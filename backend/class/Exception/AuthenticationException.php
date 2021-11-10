<?php declare(strict_types = 1);
namespace noxkiwi\core\Exception;

use noxkiwi\core\Exception;

/**
 * I am the exception for authentication erros.
 *
 * @package      noxkiwi\core
 * @example
 *       if (! ftp_login($this->connection, $data['ftpserver']['user'], $data['ftpserver']['pass'])) {
 *           throw new AuthenticationException('EXCEPTION_CONSTRUCTOR_LOGIN_FAILED', E_WARNING);
 *       }
 *
 * @author       Jan Nox <jan.nox@pm.me>
 * @copyright    2016 - 2018 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class AuthenticationException extends Exception
{
}
