<?php declare(strict_types = 1);
namespace noxkiwi\core\Gate;

use JetBrains\PhpStorm\Pure;
use noxkiwi\core\Gate;
use noxkiwi\core\Helper\WebHelper;

/**
 * I am the Gate that checks for transfer protocol.
 *
 * @package      noxkiwi\core
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2018 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class ProtocolGate extends Gate
{
    /**
     * I am the array of CIDRs that are allowed to pass the Gate.
     * @var array
     */
    protected static array $allowedRanges = [];

    /**
     * @inheritDoc
     */
    protected function __construct(?array $options = null)
    {
        parent::__construct();
        self::$allowedRanges = $options ?? [];
    }

    /**
     * @inheritDoc
     */
    #[Pure] public function isOpen(): bool
    {
        foreach (self::$allowedRanges as $allowedRange) {
            if (WebHelper::isCidr($allowedRange) === true) {
                return true;
            }
        }

        return false;
    }
}
