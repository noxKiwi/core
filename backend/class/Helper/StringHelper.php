<?php declare(strict_types = 1);
namespace noxkiwi\core\Helper;

use function is_array;
use function is_object;
use function method_exists;
use function strtr;

/**
 * I am the StringHelper class.
 *
 * @package      noxkiwi\core\Helper
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2016 - 2018 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
abstract class StringHelper
{
    /**
     * Interpolates context values into the message placeholders.
     *
     * @param string     $message
     * @param array|null $context
     *
     * @return string
     * @link    https://www.php-fig.org/psr/psr-3/
     */
    final public static function interpolate(string $message, array $context = null): string
    {
        if (empty($context)) {
            return $message;
        }
        $replace = [];
        foreach ($context as $key => $val) {
            if (! is_array($val) && (! is_object($val) || method_exists($val, '__toString'))) {
                $replace['{' . $key . '}'] = $val;
            }
        }

        return strtr($message, $replace);
    }
}
