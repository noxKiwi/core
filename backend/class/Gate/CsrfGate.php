<?php declare(strict_types = 1);
namespace noxkiwi\core\Gate;

use Exception;
use noxkiwi\core\Cookie;
use noxkiwi\core\ErrorHandler;
use noxkiwi\core\Gate;
use noxkiwi\core\Request;
use noxkiwi\core\Session;

/**
 * I am the Gate that checks for cross site request forgery.
 *
 * @package      noxkiwi\core
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2018 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class CsrfGate extends Gate
{
    private const CSRF_TOKEN = 'csrf';

    /**
     * @inheritDoc
     * @throws \noxkiwi\singleton\Exception\SingletonException
     * @return bool
     */
    public function isOpen(): bool
    {
        $fromGet = Request::getInstance()->get(self::CSRF_TOKEN, '');
        if (self::checkCsrf($fromGet)) {
            return true;
        }
        $fromPost = Request::getInstance()->get(self::CSRF_TOKEN, '');
        if (self::checkCsrf($fromPost)) {
            return true;
        }
        $fromCookie = Cookie::getInstance()->get(self::CSRF_TOKEN, '');
        if (self::checkCsrf($fromCookie)) {
            return true;
        }
        try {
            Session::getInstance()->destroy();
            Cookie::getInstance()->end();
        } catch (Exception $exception) {
            ErrorHandler::handleException($exception);
        }

        return false;
    }

    /**
     * I will validate the CSRF.
     *
     * @param string $csrfToken
     *
     * @return bool
     */
    private static function checkCsrf(string $csrfToken): bool
    {
        try {
            if (Session::getInstance()->get(self::CSRF_TOKEN, '_') === $csrfToken) {
                self::setCsrfToken();

                return true;
            }
        } catch (Exception $exception) {
            ErrorHandler::handleException($exception);
        }

        return false;
    }

    /**
     * I will solely set the new CSRF Token.
     */
    private static function setCsrfToken(): void
    {
        try {
            $csrfToken = self::generateCsrf();
            Cookie::getInstance()->set(self::CSRF_TOKEN, $csrfToken);
            Session::getInstance()->set(self::CSRF_TOKEN, $csrfToken);
        } catch (Exception $exception) {
            ErrorHandler::handleException($exception);
        }
    }

    /**
     * Function generateRandomUUID2
     *
     * @throws \Exception
     * @return string
     */
    private static function generateCsrf(): string
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            random_int(0, 0xffff),
            random_int(0, 0xffff),
            random_int(0, 0xffff),
            random_int(0, 0x0fff) | 0x4000,
            random_int(0, 0x3fff) | 0x8000,
            random_int(0, 0xffff),
            random_int(0, 0xffff),
            random_int(0, 0xffff)
        );
    }
}
