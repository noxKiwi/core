<?php declare(strict_types = 1);
namespace noxkiwi\core\Interfaces;

use noxkiwi\core\Request;

/**
 * I am
 *
 * @package      noxkiwi\core
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2016 - 2020 noxkiwi
 * @version      1.1.0
 * @link         https://nox.kiwi/
 */
interface RequestInterface extends DatacontainerInterface
{
    /**
     * I will build a new Request and fill it with the core data.
     * @return \noxkiwi\core\Request
     */
    public function build(): Request;
}
