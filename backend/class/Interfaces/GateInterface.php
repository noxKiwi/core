<?php declare(strict_types = 1);
namespace noxkiwi\core\Interfaces;

/**
 * I am the interface for all Gate classes.
 *
 * @package      noxkiwi\core
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2018 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
interface GateInterface
{
    /**
     * I will check for this Gate class' requirements and perform an action if the gate is closed.
     * @return bool
     */
    public function isOpen(): bool;

    /**
     * I will simply open the gate.
     */
    public function open(): void;

    /**
     * I will simply close the gate.
     *
     * @param string|null $reason I am the reason why the gate has been closed.
     */
    public function close(?string $reason = null): void;
}
