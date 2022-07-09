<?php declare(strict_types = 1);
namespace noxkiwi\core\Gate;

use JetBrains\PhpStorm\Pure;
use noxkiwi\core\Gate;
use noxkiwi\core\Helper\WebHelper;
use function in_array;

/**
 * I am the Ip Whitelist Gate.
 *
 * @package      noxkiwi\core
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2018 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class IpGate extends Gate
{
    /**
     * I am the array of IP addresses that are allowed to pass the Gate.
     * @var string[]
     */
    private array $allowedHosts;

    /**
     * I will set the allowed IPv4 addresses.
     *
     * @param string[] $allowedIps
     */
    public function setAllowedHosts(array $allowedIps): void
    {
        $this->allowedHosts = $allowedIps ?? [];
    }

    /**
     * @inheritDoc
     */
    #[Pure] public function isOpen(): bool
    {
        if (! parent::isOpen()) {
            return false;
        }

        return in_array(WebHelper::getClientIp(), $this->allowedHosts, true);
    }
}
