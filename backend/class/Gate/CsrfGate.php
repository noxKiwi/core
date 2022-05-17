<?php declare(strict_types = 1);
namespace noxkiwi\core\Gate;

use Exception;
use noxkiwi\core\Cookie;
use noxkiwi\core\Gate;
use noxkiwi\core\Session;
use function random_int;
use function sprintf;

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
    private Session $session;
    private Cookie  $cookie;

    /**
     * @throws \noxkiwi\singleton\Exception\SingletonException
     */
    protected function __construct()
    {
        parent::__construct();
        $this->session = Session::getInstance();
        $this->cookie  = Cookie::getInstance();
    }

    /**
     * @inheritDoc
     * @return bool
     */
    public function isOpen(): bool
    {
        if (! parent::isOpen()) {
            return false;
        }
        try {
            if ($this->checkCsrf()) {
                return true;
            }
        } catch (Exception) {
            // IGNORED
        } finally {
            $this->createToken();
        }

        return false;
    }

    /**
     * I will validate the CSRF.
     *
     * @return bool
     */
    private function checkCsrf(): bool
    {
        try {
            return $this->session->get(self::CSRF_TOKEN, '') === $this->cookie->get(self::CSRF_TOKEN, '');
        } catch (Exception) {
            //IGNORED
        }

        return false;
    }

    /**
     * I will solely set the new CSRF Token.
     */
    public function createToken(): void
    {
        try {
            $newToken = CsrfGate::generateCsrf();
            $this->cookie->set(self::CSRF_TOKEN, $newToken);
            $this->session->set(self::CSRF_TOKEN, $newToken);
        } catch (Exception) {
            //IGNORED
        }
    }

    /**
     * Function generateCsrf
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
