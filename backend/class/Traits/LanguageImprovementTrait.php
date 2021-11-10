<?php declare(strict_types = 1);
namespace noxkiwi\core\Traits;

/**
 * I am the Log trait. I extend an existing class for all methods that are related to the logging system.
 *
 * @package      noxkiwi\core
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2019 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
trait LanguageImprovementTrait
{
    /**
     * I will simply return the given $value without doing anything about it.
     *
     * <strong>WHY?</strong> You ask?
     *
     * <p>&lt;&lt;&lt;HTML
     * <br />Now we can use  {$this->returnIt(Class::CONSTANTS)} in Heredoc.
     * <br />Now we can use  {$this->returnIt(native($functions))} in Heredoc.
     * <br />&gt;&gt;&gt;HTML
     * </p>
     * <strong>That's why!</strong>
     *
     * @param mixed $value
     *
     * @return mixed
     */
    final public function returnIt(mixed $value): mixed
    {
        return $value;
    }
}
