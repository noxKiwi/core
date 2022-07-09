<?php declare(strict_types = 1);
namespace noxkiwi\core\Gate;

use JetBrains\PhpStorm\Pure;
use noxkiwi\core\Gate;
use noxkiwi\core\Helper\WebHelper;
use function gethostbyname;

/**
 * I am the Gate that checks the hostname that was given.
 *
 * @package      noxkiwi\core
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2020 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class RemoteHostnameGate extends Gate
{
    /**
     * I am the list of allowed host names.
     * @var string[]
     */
    private static array $remoteHostnames = [];

    /**
     * @inheritDoc
     */
    protected function __construct(?array $remoteHostnames = null)
    {
        parent::__construct();
        self::$remoteHostnames = $remoteHostnames ?? [];
    }

    /**
     * @inheritDoc
     */
    #[Pure] public function isOpen(): bool
    {
        if (! parent::isOpen()) {
            return false;
        }
        foreach (self::$remoteHostnames as $remoteHostname) {
            $ipAddress = gethostbyname($remoteHostname);
            if (WebHelper::getClientIp() === $ipAddress) {
                return true;
            }
        }

        return false;
    }
}
