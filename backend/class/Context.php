<?php declare(strict_types = 1);
namespace noxkiwi\core;

use noxkiwi\core\Constants\Mvc;
use noxkiwi\core\Exception\ContextException;
use noxkiwi\core\Exception\InvalidArgumentException;
use noxkiwi\core\Exception\SystemComponentException;
use noxkiwi\core\Helper\FrontendHelper;
use noxkiwi\core\Interfaces\ContextInterface;
use noxkiwi\core\Traits\LanguageImprovementTrait;
use noxkiwi\log\Log;
use noxkiwi\log\LogLevel;
use noxkiwi\log\Traits\LogTrait;
use noxkiwi\singleton\Singleton;
use noxkiwi\translator\Traits\TranslatorTrait;
use function class_exists;
use function extension_loaded;
use function is_subclass_of;
use function method_exists;
use function newrelic_name_transaction;
use function strtoupper;
use function ucfirst;
use const E_ERROR;
use const E_USER_NOTICE;
use const E_WARNING;

/**
 * I am the base Context class.
 * I handle the request and fill the response with data.
 *
 * @package      noxkiwi\core
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2016 - 2021 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
abstract class Context extends Singleton implements ContextInterface
{
    use LanguageImprovementTrait;
    use LogTrait;
    use TranslatorTrait;

    protected const LOG_LEVELS = [
        LogLevel::EMERGENCY,
        LogLevel::ALERT,
        LogLevel::CRITICAL,
        LogLevel::ERROR,
        LogLevel::WARNING
    ];
    /** @var \noxkiwi\core\Request I am the Request that is being processed by the Context. */
    protected Request $request;
    /** @var \noxkiwi\core\Response I am the Response that the Context creates upon the Response. */
    protected Response $response;
    /** @var \noxkiwi\core\Session I am the Session that is used for the Context. */
    protected Session $session;

    /**
     * Context constructor.
     * @throws \noxkiwi\singleton\Exception\SingletonException
     */
    protected function __construct()
    {
        $this->addLogger(
            static::LOG_LEVELS,
            Log::getInstance()
        );
        $this->request  = Request::getInstance();
        $this->response = Response::getInstance();
        $this->session  = Session::getInstance();
        parent::__construct();
        $this->logDebug(static::class . ' created');
        if (extension_loaded('newrelic')) {
            newrelic_name_transaction(static::class . '_' . $this->request->get(Mvc::VIEW));
        }
    }

    /**
     * I will return the given $contextName's Context object.
     *
     * @param string $contextName
     *
     * @throws \noxkiwi\core\Exception\SystemComponentException The desired context is not available.
     * @throws \noxkiwi\core\Exception\SystemComponentException The desired class is not a descendant of the Context class.
     * @throws \noxkiwi\singleton\Exception\SingletonException  Obviously, if singleton fails.
     * @return \noxkiwi\core\Context
     */
    public static function get(string $contextName): Context
    {
        if (! class_exists($contextName)) {
            throw new SystemComponentException('CONTEXT_NOT_AVAILABLE', E_ERROR, $contextName);
        }
        if (! is_subclass_of($contextName, self::class)) {
            throw new SystemComponentException('CLASS_NOT_A_CONTEXT', E_ERROR, $contextName);
        }

        return $contextName::getInstance();
    }

    /**
     * I will make sure that the client is allowed to take these actions.
     *
     * @throws \noxkiwi\singleton\Exception\SingletonException
     * @return       bool
     */
    public function isAllowed(): bool
    {
        return Session::getInstance()->identify();
    }

    /**
     * @inheritDoc
     * @throws \noxkiwi\core\Exception
     */
    public function dispatch(Request $request): void
    {
        try {
            $this->validate($request);
            $response = $this->backendController($request);
        } catch (\Exception $exception) {
            ErrorHandler::handleException($exception);
            throw new ContextException('Dispatching failed while running Backend Controller method.', E_ERROR, $exception);
        }
        try {
            $this->frontendController($response);
        } catch (\Exception $exception) {
            ErrorHandler::handleException($exception);
            throw new ContextException('Dispatching failed while running Frontend Controller method.', E_ERROR, $exception);
        }
    }

    /**
     * I will validate the given Request against the configured parameter structure of the action and view.
     *
     * @param \noxkiwi\core\Request $request
     *
     * @throws \noxkiwi\core\Exception\InvalidArgumentException
     * @return void
     */
    protected function validate(Request $request): void
    {
        $actionConst = static::class . '::' . strtoupper("REQUEST_{$request->get(Mvc::ACTION)}_ACTION");
        $errors      = [];
        if (defined($actionConst)) {
            $design = constant($actionConst);
            foreach ($design as $name => $validator) {
                $val   = $validator::get($validator);
                $error = $val->validate($request->get($name));
                if (empty($error)) {
                    continue;
                }
                $errors[$name] = $error;
            }
        }
        $viewConst = static::class . '::' . strtoupper("REQUEST_{$request->get(Mvc::VIEW)}_VIEW");
        if (defined($viewConst)) {
            $design = constant($viewConst);
            foreach ($design as $name => $validator) {
                $val   = $validator::get($validator);
                $error = $val->validate($request->get($name));
                if (empty($error)) {
                    continue;
                }
                $errors[$name] = $error;
            }
        }
        if (empty($errors)) {
            return;
        }
        throw new InvalidArgumentException('REQUEST_PARAMETERS_VIOLATED', E_WARNING, $errors);
    }

    /**
     * I will perform any actions that belong to the backend of the application.
     *
     * @param \noxkiwi\core\Request $request
     *
     * @return \noxkiwi\core\Response
     */
    protected function backendController(Request $request): Response
    {
        $this->doAction($request);
        $this->doView($request);

        return $this->response;
    }

    /**
     * I will perform the action if it is required.
     *
     * @param \noxkiwi\core\Request $request
     */
    final protected function doAction(Request $request): void
    {
        $action = $request->get('action');
        if ($action === null) {
            return;
        }
        $action = 'action' . ucfirst($action);
        if (! method_exists($this, $action)) {
            return;
        }
        try {
            $this->$action();
        } catch (Exception $exception) {
            ErrorHandler::handleException($exception, E_USER_NOTICE);
        }
    }

    /**
     * I will execute the desired view function on the context if it is found.
     *
     * @param \noxkiwi\core\Request $request
     */
    final protected function doView(Request $request): void
    {
        $view   = $request->get(Mvc::VIEW, '');
        $method = Mvc::VIEW . ucfirst($view);
        if (! method_exists($this, $method)) {
            return;
        }
        try {
            $this->$method();
        } catch (Exception $exception) {
            ErrorHandler::handleException($exception, E_USER_NOTICE);
        }
    }

    /**
     * I will use the results of the frontendController to produce a valid response for the result data.
     *
     * @param \noxkiwi\core\Response $response
     */
    protected function frontendController(Response $response): void
    {
        $this->doShow($response);
        $this->doOutput($response);
    }

    /**
     * I will parse the view's front-end file before processing the template's frontend file.
     *
     * @param \noxkiwi\core\Response $response
     */
    protected function doShow(Response $response): void
    {
        // View
        $viewFile    = "{$this->returnIt(Path::VIEW_DIR)}/{$this->returnIt($response->get(Mvc::CONTEXT))}/{$this->returnIt($response->get(Mvc::VIEW))}.php";
        $viewPath    = Path::getInheritedPath($viewFile);
        $viewContent = FrontendHelper::parseFile($viewPath, $response);
        $response->set('content', $viewContent);
        // Template
        $templateFile    = Path::TEMPLATE_DIR . '/' . Request::getInstance()->get(Mvc::TEMPLATE) . '/' . Path::TEMPLATE_FILE;
        $templatePath    = Path::getInheritedPath($templateFile);
        $templateContent = FrontendHelper::parseFile($templatePath, $response);
        $response->setOutput($templateContent);
    }

    /**
     * I will generate the finalizing output of the given $response.
     *
     * @param \noxkiwi\core\Response $response
     */
    final protected function doOutput(Response $response): void
    {
        $response->pushOutput();
    }
}
