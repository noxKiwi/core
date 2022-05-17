<?php declare(strict_types = 1);
namespace noxkiwi\core\Traits;

use Exception;
use noxkiwi\core\Hook;

/**
 * I am the TranslationTrait.
 *
 * @package      noxkiwi\core
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2016 - 2018 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
trait HookTrait
{
    /**
     * I will add the given $callback to the given $event.
     *
     * @param string   $event
     * @param callable $callable
     *
     * @return void
     */
    final public function addHook(string $event, callable $callable): void
    {
        try {
            $hook = Hook::getInstance();
            $hook->add($event, $callable);
        } catch (Exception) {
            // IGNORE
        }
    }

    /**
     * I will fire the given $event and pass the given $arguments to each of the callbacks.
     *
     * @param string $event
     * @param mixed  $arguments
     *
     * @return void
     */
    final protected function fireHook(string $event, mixed $arguments = null): void
    {
        try {
            $hook = Hook::getInstance();
            $hook->fire($event, $arguments);
        } catch (Exception) {
            // IGNORE
        }
    }
}
