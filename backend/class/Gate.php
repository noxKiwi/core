<?php declare(strict_types = 1);
namespace noxkiwi\core;

use noxkiwi\core\Interfaces\GateInterface;
use noxkiwi\singleton\Singleton;

/**
 * I am the Gate class. My purpose is to either let a user pass or not.
 * The Gate class is utilized before asking a Policy class since it checks for
 *  - System compatability
 *  - Services' status
 *  - CSRF check
 *  - Network checks (CIDR and IP whitelist)
 *
 * Contrary to the Auth layer, a Gate is used to check any other variable the Request provides
 * to either let the user pass or deny the access.
 * For example have a look at the MaintenanceGate.
 * Other well working Gate objects are bound to network structure and date/time restrictions.
 *
 * @package      noxkiwi\core
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2018 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
abstract class Gate extends Singleton implements GateInterface
{
    /**
     * @inheritDoc
     */
    public function isOpen(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function open(): void
    {
    }

    /**
     * @inheritDoc
     */
    public function close(?string $reason = null): void
    {
    }
}
