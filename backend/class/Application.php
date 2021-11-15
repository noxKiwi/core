<?php declare(strict_types = 1);
namespace noxkiwi\core;

use noxkiwi\cache\Cache;
use noxkiwi\core\Config\JsonConfig;
use noxkiwi\core\Constants\Mvc;
use noxkiwi\core\Exception\ConfigurationException;
use noxkiwi\core\Helper\ArrayHelper;
use noxkiwi\core\Traits\DatacontainerTrait;
use noxkiwi\validator\Validator\Structure\Config\AppValidator;
use noxkiwi\validator\Validator\Structure\Config\ContextValidator;
use function is_array;
use noxkiwi\singleton\Singleton;

/**
 * I am the "class" everyone wants to have but won't ever have.
 *   - pun intended
 *
 * @package      noxkiwi\core
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2019 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class Application extends Singleton
{
    use DatacontainerTrait;

    private const CONFIG_DEFAULT_VIEW = 'defaultview';

    /**
     * I will construct the Application object.
     * Exceptions that occur here will break the application.
     * @throws \noxkiwi\core\Exception
     * @throws \noxkiwi\core\Exception\ConfigurationException
     * @throws \noxkiwi\core\Exception\InvalidArgumentException
     */
    protected function __construct()
    {
        parent::__construct();
        $this->init();
    }

    /**
     * I will initialize the Application class.
     * @throws \noxkiwi\core\Exception
     * @throws \noxkiwi\core\Exception\ConfigurationException
     * @throws \noxkiwi\core\Exception\InvalidArgumentException
     */
    private function init(): void
    {
        $cachedConfig = Cache::getInstance()->get(Cache::DEFAULT_PREFIX, '_CONFIG_APP');
        if (! empty($cachedConfig) && is_array($cachedConfig)) {
            $this->add($cachedConfig);

            return;
        }
        try {
            $appConfig = (new JsonConfig(Path::CONFIG_APPLICATION))->get();
        } catch (\Exception $exception) {
            ErrorHandler::handleException($exception);
            throw new ConfigurationException('app.json is invalid JSON', E_ERROR, 'INVALID_JSON');
        }
        if (! isset($appConfig[Mvc::CONTEXT])) {
            throw new ConfigurationException('EXCEPTION_GETCONFIG_APPCONFIGCONTAINSNOCONTEXT', E_ERROR, $appConfig);
        }
        $default   = (new JsonConfig(Path::getHomeDir() . Path::CONFIG_APPLICATION, true))->get();
        $appConfig = ArrayHelper::arrayMergeRecursive($appConfig, $default);
        foreach ($appConfig[Mvc::CONTEXT] as $contextName => $contextData) {
            if (! isset($contextData['type'])) {
                continue;
            }
            try {
                $contextType = (new JsonConfig(Path::CONFIG_CONTEXT_DIR . '/' . $appConfig[Mvc::CONTEXT][$contextName]['type'] . '.json'))->get();
            } catch (\Exception $exception) {
                ErrorHandler::handleException($exception);
                throw new ConfigurationException('EXCEPTION_GETCONFIG_APPCONFIGCONTEXTTYPEINVALID', E_ERROR, 'INVALID_JSON');
            }
            $errors = ContextValidator::getInstance()->validate($contextType);
            if (! empty($errors)) {
                throw new ConfigurationException('EXCEPTION_GETCONFIG_APPCONFIGCONTEXTTYPEINVALID', E_ERROR, $errors);
            }
            $appConfig[Mvc::CONTEXT][$contextName] = ArrayHelper::arrayMergeRecursive($appConfig[Mvc::CONTEXT][$contextName], $contextType);
            if (is_array($appConfig[Mvc::CONTEXT][$contextName][self::CONFIG_DEFAULT_VIEW]) > 1) {
                $appConfig[Mvc::CONTEXT][$contextName][self::CONFIG_DEFAULT_VIEW] = $appConfig[Mvc::CONTEXT][$contextName][self::CONFIG_DEFAULT_VIEW][0];
            }
        }
        $errors = AppValidator::getInstance()->validate($appConfig);
        if (! empty($errors)) {
            throw new ConfigurationException('EXCEPTION_GETCONFIG_APPCONFIGFILEINVALID', E_ERROR, $errors);
        }
        Cache::getInstance()->set(Cache::DEFAULT_PREFIX, '_CONFIG_APP', $appConfig);
        $this->add($appConfig);
    }

    /**
     * I will return true if the given $Context exists in the current App.
     *
     * @param string|null $context
     *
     * @return       bool
     */
    public function contextExists(?string $context = null): bool
    {
        $context ??= Request::getInstance()->get(Mvc::CONTEXT);
        try {
            return self::getInstance()->exists("context>$context");
        } catch (\Exception $exception) {
            ErrorHandler::handleException($exception);
        }

        return false;
    }

    /**
     * I will return true if the $view exists in the $Context of the current App.
     *
     * @param string|null $context
     * @param string|null $view
     *
     * @return       bool
     */
    public function viewExists(?string $context = null, ?string $view = null): bool
    {
        $context ??= Request::getInstance()->get(Mvc::CONTEXT);
        $view    ??= Request::getInstance()->get(Mvc::VIEW);
        try {
            return $this->exists("context>$context>view>$view");
        } catch (\Exception $exception) {
            ErrorHandler::handleException($exception);
        }

        return false;
    }
}
