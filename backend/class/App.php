<?php declare(strict_types = 1);
namespace noxkiwi\core;

use JetBrains\PhpStorm\NoReturn;
use noxkiwi\core\Constants\Mvc;
use noxkiwi\core\Exception\ConfigurationException;
use noxkiwi\core\Gate\MaintenanceGate;
use noxkiwi\core\Helper\FrontendHelper;
use noxkiwi\core\Helper\LinkHelper;
use noxkiwi\core\Helper\WebHelper;
use noxkiwi\core\Interfaces\AppInterface;
use noxkiwi\core\Response\HttpResponse;
use noxkiwi\core\Traits\LanguageImprovementTrait;
use noxkiwi\hook\Hook;
use noxkiwi\log\Traits\LogTrait;
use noxkiwi\singleton\Singleton;
use ReflectionClass;
use function ucfirst;
use const E_ERROR;

/**
 * I am the "class" everyone wants to have but won't ever have.
 *   - pun intended
 *
 * @package      noxkiwi\core
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2016 - 2021 noxkiwi
 * @version      1.0.15
 * @link         https://nox.kiwi/
 */
abstract class App extends Singleton implements AppInterface
{
    use LogTrait;
    use LanguageImprovementTrait;

    /** @var string I am the called App's vendor. */
    private static string $vendor;
    /** @var string I am the called App's name. */
    private static string $app;

    /**
     * I will initialize the App class.
     * This step consists of loading an environment and loading an Application instance.
     * Also, I will check the maintenance status.
     * @throws \noxkiwi\singleton\Exception\SingletonException
     */
    protected function initialize(): void
    {
        parent::initialize();
        $namespaceName = (new ReflectionClass($this))->getNamespaceName();
        self::$app     = explode('\\', $namespaceName)[1];
        self::$vendor  = explode('\\', $namespaceName)[0];
        $this->checkMaintenance();
        Hook::run('APP_INITIALIZING');
        try {
            Environment::getInstance();
            Application::getInstance();
        } catch (\Exception $exception) {
            MaintenanceGate::getInstance()->close($exception->getCode());
            ErrorHandler::handleException(new ConfigurationException('APP_INIT_FAIL', E_ERROR, $exception));
        }
        Hook::run('APP_INITIALIZED');
    }

    /**
     * I will check the maintenance mode and stop the app if needed.
     */
    final protected function checkMaintenance(): void
    {
        try {
            if (MaintenanceGate::getInstance()->isOpen()) {
                return;
            }
        } catch (\Exception $exception) {
            ErrorHandler::handleException($exception);
        }
        Hook::run('APP_CONSTRUCT_MAINTENANCEMODE');
        FrontendHelper::outputExit(MaintenanceGate::MAINTENANCE_TEMPLATE, HttpResponse::HEADER_ERROR, WebHelper::HTTP_SERVER_ERROR);
    }

    /**
     * @inheritDoc
     * @throws \noxkiwi\singleton\Exception\SingletonException
     */
    #[NoReturn] public function run(): void
    {
        $request = Request::getInstance();
        Hook::run('APP_RUN_START');
        $request->set(Mvc::TEMPLATE, self::getTemplate());
        $this->logDebug(__METHOD__, $request->get());
        $contextName = static::getVendor();
        $contextName .= "\\{$this->returnIt(static::getApp())}";
        $contextName .= "\\Context";
        $contextName .= '\\' . ucfirst($request->get(Mvc::CONTEXT, '')) . 'Context';
        try {
            $contextInstance = Context::get($contextName);
            if (! $contextInstance->isAllowed()) {
                Hook::run('APP_RUN_FORBIDDEN');
                LinkHelper::forward([Mvc::CONTEXT => 'login', Mvc::VIEW => 'login']);
            }
            Hook::run('APP_RUN_ALLOWED');
            $contextInstance->dispatch($request);
            Hook::run('APP_RUN_END');
        } catch (\Exception $exception) {
            MaintenanceGate::getInstance()->close($exception->getCode());
            ErrorHandler::handleException($exception);
            exit(WebHelper::HTTP_SERVER_ERROR);
        }
        exit(WebHelper::HTTP_OKAY);
    }

    /**
     * I return the vendor of this App
     *
     * @return       string
     */
    final public static function getVendor(): string
    {
        if (empty(self::$vendor)) {
            return '';
        }

        return self::$vendor;
    }

    /**
     * Simply returns the App's name
     *
     * @return       string
     */
    final public static function getApp(): string
    {
        if (empty(self::$app)) {
            return '';
        }

        return self::$app;
    }

    /**
     * I will return the template that will be used for displaying the page.
     * It works by checking:
     *   -> Has a template been requested through the Request?
     *   -> Has the called $context/$view been configured to use a template?
     *   -> Has the called $app been configured to use a template?
     *   -> Otherwise return 'blank' template.
     * @throws \noxkiwi\singleton\Exception\SingletonException
     * @return string
     */
    private static function getTemplate(): string
    {
        $request  = Request::getInstance();
        $template = $request->get(Mvc::TEMPLATE);
        if (is_string($template)) {
            return $template;
        }
        $context  = $request->get(Mvc::CONTEXT);
        $view     = $request->get(Mvc::VIEW);
        $template = (string)Application::getInstance()->get("context>$context>view>$view>template", '');
        if (! empty($template)) {
            return $template;
        }
        $template = (string)Application::getInstance()->get("context>$context>template", '');
        if (! empty($template)) {
            return $template;
        }

        return Application::getInstance()->get('defaulttemplate', 'blank');
    }
}
