<?php declare(strict_types = 1);
namespace noxkiwi\core\Gate;

use noxkiwi\core\Gate;

/**
 * I am the Gate that is always closed.
 * For whatever reason, I still do exists.
 *
 * @package      noxkiwi\core
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2018 - 2021 noxkiwi
 * @version      1.0.1
 * @link         https://nox.kiwi/
 */
final class ClosedGate extends Gate
{
    /**
     * @inheritDoc
     */
    public function isOpen(): bool
    {
        return false;
    }
}
