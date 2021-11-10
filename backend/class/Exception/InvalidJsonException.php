<?php declare(strict_types = 1);
namespace noxkiwi\core\Exception;

use noxkiwi\core\Exception;

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
final class InvalidJsonException extends Exception
{
    /**
     * Error that occured
     *
     * @var string
     */
    protected string $jsonError;
    /**
     * JSON string
     *
     * @var string
     */
    protected string $jsonString;

    /**
     * I will return a quite precise information about the error that occured
     * @return string
     */
    public function getJsonError(): string
    {
        return $this->jsonError;
    }

    /**
     * I will return the JSON string that was invalid
     * @return string
     */
    public function getJsonString(): string
    {
        return $this->jsonString;
    }
}
