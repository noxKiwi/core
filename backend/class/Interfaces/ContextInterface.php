<?php declare(strict_types = 1);
namespace noxkiwi\core\Interfaces;

use noxkiwi\core\Request;

/**
 * I am the interface for all Contexts that are available in the application.
 *
 * @package      noxkiwi\core
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2016 - 2020 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
interface ContextInterface
{
    /**
     * I will dispatch the given $request and return the resulting Response instance.
     * Dispatching the request on a context side consists of performing each (backend and frontend) controller.
     *
     * @param \noxkiwi\core\Request $request
     */
    public function dispatch(Request $request): void;
}
