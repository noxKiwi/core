<?php declare(strict_types = 1);
namespace noxkiwi\core\Helper;

use function abs;
use function date;
use function in_array;
use function round;
use function strtotime;
use function time;

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
abstract class DateHelper
{
    /**
     *
     */
    public const INTERVAL_DAY = 'day';
    /**
     *
     */
    public const INTERVAL_MONTH = 'month';
    /**
     *
     */
    public const INTERVAL_YEAR = 'year';

    /**
     * Returns the current date as a DB readible formatted date
     *
     * @example      2013-10-01
     * @return       string
     */
    public static function getCurrentDateAsDbdate(): string
    {
        return date('Y-m-d', static::getCurrentTimestamp());
    }

    /**
     * Returns the timestamp of the current time
     *
     * @return       int
     */
    public static function getCurrentTimestamp(): int
    {
        return time();
    }

    /**
     * Returns the current date as a readible format.
     * <br />Uses the translation key DATETIME.FORMAT_DATE of your current localisation
     *
     * @return       string
     */
    public static function getCurrentDateAsReadible(): string
    {
        return date('d.m.Y', static::getCurrentTimestamp());
    }

    /**
     * This method will return an array of timestamps that will differ from each other by the given $interval
     *
     * @param int    $start
     * @param int    $end
     * @param string $interval
     *
     * @return       array
     */
    public static function getIntervalArrayFromStartUntilEnd(int $start, int $end, string $interval): array
    {
        $intervals = [];
        if (! in_array($interval, [static::INTERVAL_MONTH, static::INTERVAL_DAY, static::INTERVAL_YEAR], true)) {
            echo $interval . 'Fehlt!';

            return $intervals;
        }
        $lastStart = $start;
        while ($start < $end) {
            $newStart = strtotime(date('Y-m-d', $start) . ' +1 ' . $interval);
            if ($newStart > $end) {
                break;
            }
            $intervals[] = ['start' => $lastStart, 'end' => $start];
            $lastStart   = $newStart;
        }

        return $intervals;
    }

    /**
     * This function returns an array of unix timestamps when an article shall be invoiced & provisioned again
     *
     * @param int    $start
     * @param string $interval
     *
     * @return       array
     */
    public static function getIntervalsFromStartUntilNow(int $start, string $interval): array
    {
        return static::getIntervalsFromStartUntilEnd($start, static::getCurrentTimestamp(), $interval);
    }

    /**
     * This method will return an array of timestamps that will differ from each other by the given $interval
     *
     * @param int    $start
     * @param int    $end
     * @param string $interval
     *
     * @return       array
     */
    public static function getIntervalsFromStartUntilEnd(int $start, int $end, string $interval): array
    {
        $intervals = [$start];
        if (! in_array($interval, [static::INTERVAL_MONTH, static::INTERVAL_DAY, static::INTERVAL_YEAR], true)) {
            return $intervals;
        }
        while ($start < $end) {
            $newStart = strtotime(date('Y-m-d', $start) . ' +1 ' . $interval);
            if ($newStart > $end) {
                break;
            }
            $intervals[] = $newStart;
        }

        return $intervals;
    }

    /**
     * Returns the current date as a DB-conform '2016-11-25 13:57:12' Format
     * @example 2016-11-25
     * @return       string
     */
    public static function getCurrentDateTimeAsDbdate(): string
    {
        return date('Y-m-d H:i:s', static::getCurrentTimestamp());
    }

    /**
     * Returns the current date as a DB-conform '2016-11-25 13:57:12' Format
     *
     * @example 2016-12-09
     *
     * @param int $timestamp
     *
     * @return       string
     */
    public static function getTimestampAsDbdate(int $timestamp): string
    {
        return date('Y-m-d H:i:s', $timestamp);
    }

    /**
     * I will translate the given $date into text.
     *
     * @param      $date
     * @param bool $withTime
     *
     * @return string
     */
    public static function timeInWords($date, bool $withTime = null): string
    {
        if (! $date) {
            return 'N/A';
        }
        $withTime  ??= true;
        $timestamp = strtotime($date);
        $distance  = round(abs(time() - $timestamp) / 60);
        if ($distance <= 1) {
            $return = $distance == 0 ? 'a few seconds ago' : '1 minute ago';
        } elseif ($distance < 60) {
            $return = $distance . ' minutes ago';
        } elseif ($distance < 119) {
            $return = 'an hour ago';
        } elseif ($distance < 1440) {
            $return = round($distance / 60.0) . ' hours ago';
        } elseif ($distance < 2880) {
            $return = 'Yesterday' . ($withTime ? ' at ' . date('g:i A', $timestamp) : '');
        } elseif ($distance < 14568) {
            $return = date('l, F d, Y', $timestamp) . ($withTime ? ' at ' . date('g:i A', $timestamp) : '');
        } else {
            $return = date('F d ', $timestamp) . date('Y') !== date('Y', $timestamp) ? ' ' . date('Y', $timestamp) : ($withTime ? ' at ' . date('g:i A', $timestamp) : '');
        }

        return $return;
    }
}
