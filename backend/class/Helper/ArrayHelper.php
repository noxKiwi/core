<?php declare(strict_types = 1);
namespace noxkiwi\core\Helper;

use function in_array;
use function is_array;
use function is_numeric;

/**
 * I am
 *
 * @package      noxkiwi\core
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2016 - 2021 noxkiwi
 * @version      1.0.1
 * @link         https://nox.kiwi/
 */
abstract class ArrayHelper
{
    /**
     * Merges/unifies two arrays recursively
     *
     * @link http://stackoverflow.com/questions/25712099/php-multidimensional-array-merge-recursive
     *
     * @param array $array1
     * @param array $array2
     *
     * @return       array
     */
    public static function arrayMergeRecursive(array $array1, array $array2): array
    {
        $merged = $array1;
        foreach ($array2 as $key => $value) {
            if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
                $merged[$key] = static::arrayMergeRecursive($merged[$key], $value);
            } elseif (is_numeric($key)) {
                if (! in_array($value, $merged, true)) {
                    $merged[] = $value;
                }
            } else {
                $merged[$key] = $value;
            }
        }

        return $merged;
    }
}
