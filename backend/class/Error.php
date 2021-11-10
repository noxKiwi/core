<?php declare(strict_types = 1);
namespace noxkiwi\core;

use JetBrains\PhpStorm\Pure;
use Throwable;

/**
 * I am an arbitrary Error.
 * Class StackOf
 * @package noxkiwi\core
 */
class Error extends \Error
{
    public const KEY_CODE = 'CODE';
    /** @var array I am the Error's detail. */
    private array $detail;

    /**
     * Error constructor.
     *
     * @param string          $message
     * @param int             $code
     * @param \Throwable|null $previous
     */
    #[Pure] public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->detail = [];
    }

    /**
     * I will return the detail array.
     * @return array
     */
    final public function getDetail(): array
    {
        return $this->detail;
    }

    /**
     * I will set the detail to the given array.
     *
     * @param array|null $detail
     *
     * @return \noxkiwi\core\Error
     */
    final public function setDetail(array $detail = null): Error
    {
        $this->detail = $detail ?? [];

        return $this;
    }
}
