<?php declare(strict_types = 1);
namespace noxkiwi\core\Helper;

use DateTime;
use DateTimeZone;
use Exception;
use noxkiwi\core\Application;
use noxkiwi\core\ErrorHandler;
use noxkiwi\core\Session;
use const E_USER_NOTICE;

/**
 * I am the helper for
 *
 * @package      noxkiwi\core
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2018 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
abstract class DateTimeHelper
{
    public const CONFIG_TIMEZONE = 'timeZone';

    /**
     * I will convert the given $date to the server's time zone.
     *
     * @param string|\DateTime $date
     *
     * @return \DateTime|null
     */
    final public static function toServer(string|DateTime $date): ?DateTime
    {
        if (empty($date)) {
            return null;
        }
        try {
            return static::toTimeZone($date, new DateTimeZone(self::getServerTimeZone()));
        } catch (Exception $exception) {
            ErrorHandler::handleException($exception, E_USER_NOTICE);
        }

        return null;
    }

    /**
     * @param \DateTime          $date
     * @param \DateTimeZone|null $timeZone
     *
     * @throws \noxkiwi\singleton\Exception\SingletonException
     * @return \DateTime|null
     */
    final public static function toTimeZone(DateTime $date, DateTimeZone $timeZone = null): ?DateTime
    {
        if (empty($date)) {
            return null;
        }
        $date->setTimezone($timeZone ?? new DateTimeZone(self::getServerTimeZone()));

        return $date;
    }

    /**
     * @throws \noxkiwi\singleton\Exception\SingletonException
     * @return string
     */
    final public static function getServerTimeZone(): string
    {
        $application = Application::getInstance();

        return $application->get(self::CONFIG_TIMEZONE, 'UTC');
    }

    /**
     * I will return the formatted user time.
     *
     * @param null $date
     *
     * @return string|null
     */
    final public static function user($date = null): ?string
    {
        $dt = static::normalize($date, true);
        $dt = static::toUser($dt);
        if ($dt === null) {
            return '';
        }

        return $dt->format('d.m.Y H:i:s');
    }

    /**
     * I will normalize the given $date into a DateTime Object.
     *
     * @param string|\DateTime|null $date
     *
     * @param bool                  $now
     *
     * @return \DateTime|null
     */
    final public static function normalize(string|DateTime|null $date, bool $now = false): ?DateTime
    {
        if (empty($date) && $now === false) {
            return null;
        }
        if (empty($date) && $now) {
            $date = '';
        }
        if ($date instanceof DateTime) {
            return $date;
        }
        try {
            $returnValue = new DateTime($date);
            $returnValue->setTimezone(new DateTimeZone(self::getServerTimeZone()));

            return $returnValue;
        } catch (Exception $exception) {
            ErrorHandler::handleException($exception, E_USER_NOTICE);
        }

        return null;
    }

    /**
     * @param null $date
     *
     * @return \DateTime|null
     */
    final public static function toUser($date = null): ?DateTime
    {
        if (empty($date)) {
            return null;
        }
        try {
            $dt = static::normalize($date);
            if ($dt === null) {
                return null;
            }

            return static::toTimeZone($dt, new DateTimeZone(self::getUserTimeZone()));
        } catch (Exception $exception) {
            ErrorHandler::handleException($exception, E_USER_NOTICE);
        }

        return null;
    }

    /**
     * @throws \noxkiwi\singleton\Exception\SingletonException
     * @return string
     */
    final public static function getUserTimeZone(): string
    {
        $session = Session::getInstance();

        return $session->get(self::CONFIG_TIMEZONE, 'Europe/Berlin');
    }

    /**
     * I will format the given DateTime Object in the user language's date format
     * After putting it to the user time zone.
     *
     * @param \DateTime $date
     *
     * @return string
     */
    final public static function toUserFormat(DateTime $date): string
    {
        $user = self::toUser($date);
        if ($user === null) {
            return '';
        }

        return $user->format('d.m.Y H:i:s');
    }

    /**
     * I will return the server time in ISO.
     * @return string
     */
    final public static function iso(): string
    {
        $dt = self::normalize(null, true);
        if ($dt === null) {
            return '';
        }

        return $dt->format('c');
    }

    /**
     * I will return the formatted server time.
     *
     * @param string|\DateTime|null $date
     *
     * @return string|null
     */
    final public static function server(string|DateTime|null $date = null): ?string
    {
        $dt = static::normalize($date, true);
        if ($dt === null) {
            return '';
        }

        return $dt->format('d.m.Y H:i:s');
    }
}
