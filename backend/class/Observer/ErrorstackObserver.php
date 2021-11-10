<?php declare(strict_types = 1);
namespace noxkiwi\core\Observer;

use noxkiwi\observing\Observable\ObservableInterface;
use noxkiwi\observing\Observer;

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
class ErrorstackObserver extends Observer
{
    public const NOTIFY_ADDERROR    = 'adderror';
    public const NOTIFY_ADDINSTANCE = 'addinstance';
    /**
     * Contains the count of errors that occured
     *
     * @var int
     */
    public static int $countErrors = 0;
    /**
     * Contains the amount of errorstack instances that were created
     *
     * @var int
     */
    public static int $countInstances = 0;

    /**
     * @inheritDoc
     */
    public function update(ObservableInterface $observable, string $type): void
    {
        if ($type === self::NOTIFY_ADDERROR) {
            static::$countErrors++;
        }
        if ($type === self::NOTIFY_ADDINSTANCE) {
            static::$countInstances++;
        }
    }
}

