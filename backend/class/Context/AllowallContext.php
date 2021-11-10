<?php declare(strict_types = 1);
namespace noxkiwi\core\Context;

use noxkiwi\core\Context;

/**
 * I am the public context. Every request is allowed to pass.
 *
 * @package      noxkiwi\core
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2016 - 2020 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
abstract class AllowallContext extends Context
{
    /**
     * @inheritDoc
     */
    public function isAllowed(): bool
    {
        parent::isAllowed();

        return true;
    }
}
