<?php declare(strict_types = 1);
namespace noxkiwi\core\Gate;

use JetBrains\PhpStorm\Pure;
use noxkiwi\core\Gate;
use function in_array;

/**
 * I am the Gate that checks the hostname that was given.
 *
 * @package      noxkiwi\core
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2018 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class HostnameGate extends Gate
{
    /**
     * I am the list of allowed host names.
     * @var string[]
     */
    private array $hostNames;

    /**
     * I will set the allowed host names.
     *
     * @param array $hostNames
     */
    public function setHostNames(array $hostNames): void
    {
        $this->hostNames = $hostNames ?? [];
    }

    /**
     * @inheritDoc
     */
    #[Pure] public function isOpen(): bool
    {
        if (! parent::isOpen()) {
            return false;
        }

        return in_array($_SERVER['HTTP_HOST'], $this->hostNames, true);
    }
}
