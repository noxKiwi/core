<?php declare(strict_types = 1);
namespace noxkiwi\core\Traits;

use noxkiwi\core\Error;
use noxkiwi\core\ErrorStack;

/**
 * I am the trait for errorstack handling.
 *
 * @package      noxkiwi\core
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2016 - 2018 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
trait ErrorstackTrait
{
    /**
     * I will add a new error with the given information.
     *
     * @param string $code   I am the specific error code. I may be translatable.
     * @param mixed  $detail I am more information about the error that occured.
     *
     * @return       \noxkiwi\core\ErrorStack
     */
    public function addError(string $code, mixed $detail = null): ErrorStack
    {
        if (! isset($this->errorStack) || ! $this->errorStack instanceof ErrorStack) {
            $this->errorStack = new ErrorStack(static::class);
        }

        return $this->errorStack->addError($code, $detail);
    }

    /**
     * I will return the first error that exists in the stack.
     * If no errors occurred, I will return null.
     * @return \noxkiwi\core\Error|null
     */
    public function getFirstError(): ?Error
    {
        if (! isset($this->errorStack) || ! $this->errorStack instanceof ErrorStack) {
            $this->errorStack = new ErrorStack(static::class);
        }

        return $this->errorStack->getFirstError();
    }

    /**
     * I will return all errors that have been stored in the stack.
     *
     * @return       array
     */
    public function getErrors(): array
    {
        if (! isset($this->errorStack) || ! $this->errorStack instanceof ErrorStack) {
            $this->errorStack = new ErrorStack(static::class);
        }

        return $this->errorStack->getAll();
    }

    /**
     * I will return true if there are no errors in the stack.
     *
     * @return       bool
     */
    public function hasErrors(): bool
    {
        if (! isset($this->errorStack) || ! $this->errorStack instanceof ErrorStack) {
            $this->errorStack = new ErrorStack(static::class);
        }

        return $this->errorStack->isSuccess();
    }

    /**
     * I will remove all errors from the Errorstack instance.
     */
    public function clearErrors(): void
    {
        if (! isset($this->errorStack) || ! $this->errorStack instanceof ErrorStack) {
            $this->errorStack = new ErrorStack(static::class);
        }
        $this->errorStack->reset();
    }

    /**
     * Adds a callback for errors.
     * <br />Add the $object and the $function of the object that will be called
     *
     * @param \object $object
     * @param string  $function
     */
    public function setErrorCallback(object $object, string $function): void
    {
        if (! isset($this->errorStack) || ! $this->errorStack instanceof ErrorStack) {
            $this->errorStack = new ErrorStack(static::class);
        }
        $this->errorStack->setCallback($object, $function);
    }
}
