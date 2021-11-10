<?php declare(strict_types = 1);
namespace noxkiwi\core\Validator\Structure\Config;

use noxkiwi\core\Constants\Mvc;
use noxkiwi\validator\Validator\Structure\ConfigValidator;
use noxkiwi\validator\Validator\Text\SecureNameValidator;

/**
 * I am
 *
 * @package      noxkiwi\Core
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2016 - 2018 noxkiwi
 * @version      1.0.0
 * @since        1.0.0  Stable (JG 2018-07-29)
 * @link         https://nox.kiwi/
 */
class ContextValidator extends ConfigValidator
{
    /**
     * @inheritDoc
     */
    protected array $structureDesign = ['defaultview' => SecureNameValidator::class, Mvc::VIEW => null];
}
