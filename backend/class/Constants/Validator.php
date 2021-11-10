<?php declare(strict_types = 1);
namespace noxkiwi\core\Constants;

/**
 * I am the constant storage for HTML Attributes.
 *
 * @package      noxkiwi\core
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2020 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
abstract class Validator
{
    public const TEXT   = 'text';
    public const NUMBER = 'number';
    public const ANY    = '';
    public const ALL    = [
        self::TEXT,
        self::NUMBER,
        self::ANY
    ];
}
