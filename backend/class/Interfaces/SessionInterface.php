<?php declare(strict_types = 1);
namespace noxkiwi\core\Interfaces;

use noxkiwi\core\Session;

/**
 * I am the class interface shared by all Session drivers.
 * I'm just an extended DatacontainerInterface, adding the methods to
 *  - identify,
 *  - start and
 *  - destroy a Session.
 *
 * @package      noxkiwi\core\Interfaces
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2016 - 2021 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
interface SessionInterface extends DatacontainerInterface
{
    /**
     * I will utilize the given $data to construct a new Session.
     * May this be bound to a PHP Session, a Cache-driven Session or a dummy one.
     *
     * @param array $data
     *
     * @return       \noxkiwi\core\Session
     */
    public function start(array $data): Session;

    /**
     * I will have the current Session be destroyed.
     */
    public function destroy(): void;

    /**
     * I will identify the Client and load the Session data to the instance.
     * <br />If identified successfully, I will return TRUE
     * <br />In any other case I will return FALSE
     *
     * @return       bool
     */
    public function identify(): bool;
}
