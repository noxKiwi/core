<?php declare(strict_types = 1);
namespace noxkiwi\core\Gate;

use JetBrains\PhpStorm\Pure;
use noxkiwi\core\Gate;
use noxkiwi\core\Helper\WebHelper;

/**
 * I am the CIDR Whitelisting Gate.
 *
 * @package      noxkiwi\core
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2018 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class CidrGate extends Gate
{
    /**
     * I am the array of CIDRs that are allowed to pass the Gate.
     * @var array
     */
    protected array $allowedRanges = [];

    /**
     * I will set the allowed ranges.
     *
     * @param array $ranges
     */
    public function setRanges(array $ranges): void
    {
        $this->allowedRanges = $ranges;
    }

    /**
     * @inheritDoc
     */
    #[Pure] public function isOpen(): bool
    {
        foreach ($this->allowedRanges as $allowedRange) {
            if (WebHelper::isCidr($allowedRange) === true) {
                return true;
            }
        }

        return false;
    }
}
