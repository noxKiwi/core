<?php declare(strict_types = 1);
namespace noxkiwi\core;

use JetBrains\PhpStorm\Pure;
use noxkiwi\core\Observer\ErrorstackObserver;
use noxkiwi\observing\Traits\ObservableTrait;
use noxkiwi\stack\StackOf;
use function call_user_func;
use function compact;
use function count;

/**
 * I am
 * Class StackOf
 * @package noxkiwi\core
 */
final class ErrorStack extends StackOf
{
    use ObservableTrait;

    /** @var array I am the list of all existing errorStack instances. */
    private static array $errorStacks;
    /** @var string I am the name of the ErrorStack. */
    private string $name;
    /** @var array I contain an action that will be executed when an error is added */
    private array $callback;

    /**
     * ErrorStack constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        parent::__construct(Error::class);
        $this->attach(new ErrorstackObserver());
        $this->notify(ErrorstackObserver::NOTIFY_ADDINSTANCE);
        $this->name = $name;
    }

    /**
     * Returns an ErrorStack instance identified by the given $type
     *
     * @param string $type
     *
     * @return       \noxkiwi\core\ErrorStack
     */
    public static function getErrorStack(string $type): ErrorStack
    {
        if (! isset(self::$errorStacks[$type])) {
            self::$errorStacks[$type] = new Errorstack($type);
        }

        return self::$errorStacks[$type];
    }

    /**
     * I will
     * @return array
     */
    public static function getErrorStacks(): array
    {
        return self::$errorStacks;
    }

    /**
     * I will return whether the ErrorStack is failed.
     * If there's even only ONE single error, the Stack is assumed to have failed.
     * @return bool
     */
    #[Pure] public function isFailed(): bool
    {
        return ! $this->isSuccess();
    }

    /**
     * I will return whether the ErrorStack is successful.
     * If there's not even ONE single error, the Stack is considered successful.
     * @return bool
     */
    public function isSuccess(): bool
    {
        return count($this) === 0;
    }

    /**
     * I will add an error with the given $code and $detail to the Stack.
     *
     * @param string $code
     * @param null   $detail
     *
     * @return $this
     */
    public function addError(string $code, $detail = null): ErrorStack
    {
        $error = new Error("$this->name.$code");
        $error->setDetail((array)$detail);
        $this->add($error);
        if (! empty($this->callback)) {
            call_user_func([$this->callback['object'], $this->callback['function']], $this->getErrors());
        }
        $this->notify(ErrorstackObserver::NOTIFY_ADDERROR);

        return $this;
    }

    /**
     * I will return all Errors that have been added to the Stack.
     *
     * @return \noxkiwi\core\Error[]
     */
    public function getErrors(): array
    {
        $errors = $this->getAll();
        if (! empty($errors)) {
            $this->reset();
        }

        return $errors;
    }

    /**
     * I will solely set the callback on the ErrorStack for the given $name and function.
     *
     * @param object $object
     * @param string $function
     */
    public function setCallback(object $object, string $function): void
    {
        $this->callback = compact('object', 'function');
    }

    /**
     * I will return the first error in the stack. If not defined, I will return null.
     * @return \noxkiwi\core\Error|null
     */
    public function getFirstError(): ?Error
    {
        if (count($this) === 0) {
            return null;
        }

        return $this[0];
    }
}
