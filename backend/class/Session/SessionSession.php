<?php declare(strict_types = 1);
namespace noxkiwi\core\Session;

use JetBrains\PhpStorm\Pure;
use noxkiwi\core\Exception\SessionException;
use noxkiwi\core\Exception\SystemComponentException;
use noxkiwi\core\Session;
use function array_key_exists;
use function extension_loaded;
use function session_destroy;
use function session_start;
use const E_ERROR;

/**
 * I am
 *
 * @package      noxkiwi\core
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      http://www.noxkiwi.de/license
 * @copyright    2016-2018 noxkiwi
 * @version      1.0.0
 * @link         http://www.noxkiwi.de/
 */
final class SessionSession extends Session
{
    /**
     * SessionSession constructor.
     *
     * @param array $data
     *
     * @throws \noxkiwi\core\Exception\SystemComponentException
     * @throws \noxkiwi\core\Exception\SessionException
     */
    protected function __construct(array $data = [])
    {
        if (! extension_loaded('session')) {
            throw new SystemComponentException('MISSING_EXTENSION_PHP_SESSION', E_ERROR);
        }
        if (! session_start()) {
            throw new SessionException('UNABLE_TO_START_SESSION', E_ERROR);
        }
        parent::__construct($data);
    }

    /**
     * Creates the instance and saves the data object in it
     *
     * @author       Jan Nox <jan.nox@pm.me>
     *
     * @param array $data
     *
     * @return       Session
     */
    public function start(array $data): Session
    {
        $_SESSION = $data;

        return $this;
    }

    /**
     * Ends the Session the current user is in
     * @author       Jan Nox <jan.nox@pm.me>
     */
    public function destroy(): void
    {
        session_destroy();
        unset($_SESSION);
    }

    /**
     * I will identify the Client and load the Session data to the instance.
     * <br />If identified successfully, I will return TRUE
     * <br />In any other case I will return FALSE
     *
     * @author       Jan Nox <jan.nox@pm.me>
     * @return       bool
     */
    #[Pure] public function identify(): bool
    {
        return $this->exists('user_username');
    }

    /**
     * @inheritDoc
     */
    public function add(array $data): void
    {
        foreach ($data as $key => $value) {
            $this->set((string)$key, $value);
        }
    }

    /**
     * @inheritDoc
     */
    #[Pure] public function get(string $key = null, mixed $default = null): mixed
    {
        if (empty($key)) {
            return $_SESSION;
        }
        if (! $this->exists($key)) {
            return $default;
        }

        return $_SESSION[$key];
    }

    /**
     * @inheritDoc
     */
    public function set(string $key, mixed $data): void
    {
        $_SESSION[$key] = $data;
    }

    /**
     * @inheritDoc
     */
    public function remove(string $key): void
    {
        $_SESSION[$key] = null;
        unset($_SESSION[$key]);
    }

    /**
     * @inheritDoc
     */
    public function exists(string $key): bool
    {
        return array_key_exists($key, $_SESSION ?? []);
    }

    /**
     * @inheritDoc
     */
    public function put(array $data): void
    {
        // TODO: Implement put() method.
    }
}
