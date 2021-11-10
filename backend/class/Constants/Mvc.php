<?php declare(strict_types = 1);
namespace noxkiwi\core\Constants;

/**
 * I am the constant storage for the integration of the core MVC Pattern.
 *
 * @package      noxkiwi\core
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2020 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
abstract class Mvc
{
    public const CONTEXT  = 'context';
    public const VIEW     = 'view';
    public const ACTION   = 'action';
    public const TEMPLATE = 'template';
    public const ALL      = [
        self::CONTEXT,
        self::VIEW,
        self::ACTION,
        self::TEMPLATE
    ];
}
