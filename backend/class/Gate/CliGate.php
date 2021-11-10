<?php declare(strict_types = 1);
namespace noxkiwi\core\Gate;

use noxkiwi\core\Gate;

/**
 * I am the CommandLineInterface Gate class.
 *
 * @package      noxkiwi\core
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2018 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class CliGate extends Gate
{
    /**
     * @inheritDoc
     */
    public function isOpen(): bool
    {
        return PHP_SAPI === 'cli';
    }
}
