<?php declare(strict_types = 1);
namespace noxkiwi\core\Helper;

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
abstract class FormHelper
{
    /**
     * @var string
     */
    public const FIELDTYPE_YESNO = 'yesno';
    /**
     * I am a list of Field types and their template names.
     *
     * @var array
     */
    public static array $fieldTypes = ['YESNO' => 'yesno'];
}
