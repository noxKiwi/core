<?php declare(strict_types = 1);
namespace noxkiwi\core;

use noxkiwi\core\Interfaces\AuthInterface;
use noxkiwi\singleton\Singleton;

/**
 * I am the base Auth class.
 *
 * @package      noxkiwi\core
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2016 - 2021 noxkiwi
 * @version      1.0.2
 * @link         https://nox.kiwi/
 */
abstract class Auth extends Singleton implements AuthInterface
{
    protected const USE_DRIVER = true;
}
