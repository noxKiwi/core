<?php declare(strict_types = 1);
namespace noxkiwi\core;

use noxkiwi\hook\Hook;
use function file_put_contents;

/**
 * I am the Core project's base exception.
 * I am abstract to force developers to introduce their own Exception types.
 *
 * @package      noxkiwi\core
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2016 - 2021 noxkiwi
 * @version      1.1.2
 * @link         https://nox.kiwi/
 */
abstract class Exception extends \Exception
{
    /** @var int I am the Exception's error level */
    private int $level;
    /** @var mixed I am the Exception error info */
    private mixed $info;

    /**
     * Create an errormessage that will stop execution of this Request.
     *
     * @param string $code
     * @param int    $level
     * @param mixed  $info
     *
     * @throws \noxkiwi\singleton\Exception\SingletonException
     */
    final public function __construct(string $code, int $level, $info = null)
    {
        file_put_contents('/var/www/_log/exceptions.log', chr(10) . $code, 8);
        parent::__construct($code, $level);
        $this->message = $code;
        $this->code    = $code;
        $this->level   = $level;
        $this->info    = $info;
        Hook::getInstance()->fire($code);
        Hook::getInstance()->fire('EXCEPTION');
    }

    /**
     * I will return the Level of this Exception.
     * @return int
     */
    final public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * I will return the info of the Exception
     * @return mixed
     */
    final public function getInfo(): mixed
    {
        return $this->info;
    }
}
