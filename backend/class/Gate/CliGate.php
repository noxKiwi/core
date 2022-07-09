<?php declare(strict_types = 1);
namespace noxkiwi\core\Gate;

use JetBrains\PhpStorm\Pure;
use noxkiwi\core\Gate;
use const PHP_SAPI;

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
    #[Pure] public function isOpen(): bool
    {
        if (! parent::isOpen()) {
            return false;
        }

        return PHP_SAPI === 'cli';
    }
}
