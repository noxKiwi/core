<?php declare(strict_types = 1);
namespace noxkiwi\core\Response;

use noxkiwi\core\Response;

/**
 * I am
 *
 * @package      noxkiwi\core
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2016 - 2018 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class CliResponse extends Response
{
    public const EXITCODE_OKAY       = 0;
    public const EXITCODE_ERROR      = 127;
    public const EXITCODE_FATALERROR = 254;
}
