<?php declare(strict_types = 1);
namespace noxkiwi\core\Traits;

use Exception;
use noxkiwi\core\ErrorHandler;
use noxkiwi\translator\Translator;
use function strtoupper;

/**
 * I am the TranslationTrait.
 *
 * @package      noxkiwi\core
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2016 - 2018 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
trait TranslationTrait
{
    /**
     * Transllates the given key. If there's no PERIOD in the key, the function will use APP.$CONTEXT_$VIEW as prefix
     * automatically. I will suppress every Exception.
     *
     * @param string     $key
     * @param array|null $context
     *
     * @return       string
     */
    public function translate(string $key, array $context = null): string
    {
        $context ??= [];
        $key     = strtoupper($key);
        try {
            return Translator::getInstance()->translate($key, $context);
        } catch (Exception $exception) {
            ErrorHandler::handleException($exception);

            return '';
        }
    }
}
