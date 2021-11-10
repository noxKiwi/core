<?php declare(strict_types = 1);
namespace noxkiwi\core;

use noxkiwi\core\Interfaces\DatacontainerInterface;
use noxkiwi\core\Traits\DatacontainerTrait;

/**
 * I am the DataContainer.
 *
 * @see \noxkiwi\core\Interfaces\DatacontainerInterface
 *
 * @package      noxkiwi\core
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2016 - 2018 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
class Datacontainer implements DatacontainerInterface
{
    use DatacontainerTrait;
}
