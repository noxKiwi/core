<?php declare(strict_types = 1);
namespace noxkiwi\core\Request;

use Exception;
use noxkiwi\core\Application;
use noxkiwi\core\Constants\Mvc;
use noxkiwi\core\Cookie;
use noxkiwi\core\Environment;
use noxkiwi\core\ErrorHandler;
use noxkiwi\core\Exception\InvalidJsonException;
use noxkiwi\core\Helper\JsonHelper;
use noxkiwi\core\Helper\LinkHelper;
use noxkiwi\core\Helper\WebHelper;
use noxkiwi\core\Request;
use noxkiwi\rewrite\Urlrewrite;
use function compact;
use function explode;
use function file_get_contents;
use function filter_input_array;
use function is_array;
use function json_decode;
use function parse_str;
use function str_contains;
use function strncmp;
use function strtok;
use function strtolower;
use function substr;
use const INPUT_COOKIE;
use const INPUT_GET;
use const INPUT_POST;
use const INPUT_SERVER;

/**
 * I am the
 *
 * @package      noxkiwi\core
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2016 - 2018 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
class HttpRequest extends Request
{
    /**
     * Contains data for redirecting the user after finishing the Request
     *
     * @var string
     */
    protected string $redirect;

    /**
     * @inheritDoc
     */
    protected function __construct(array $data = [])
    {
        parent::__construct();
        $this->add(filter_input_array(INPUT_SERVER) ?? []);
        $this->add(filter_input_array(INPUT_COOKIE) ?? []);
        $this->add(filter_input_array(INPUT_GET) ?? []);
        $this->add(filter_input_array(INPUT_POST) ?? []);
        $decoded = (array)(json_decode(file_get_contents('php://input') ?? '', true) ?? []);
        $this->add($decoded);
        if (! is_array($decoded))
        {
            return;
        }
        $this->add($data);
    }

    /**
     * @inheritDoc
     * @throws \noxkiwi\singleton\Exception\SingletonException
     * @return \noxkiwi\core\Request
     */
    public function build(): Request
    {
        parent::build();
        Cookie::getInstance()->start();
        $uri         = substr($_SERVER['REQUEST_URI'], 1);
        $requestData = $this->getRedirection($uri);
        if (! empty($uri) && ! empty($requestData)) {
            foreach ($requestData as $key => $value) {
                $this->set($key, $value);
            }
        } else {
            if (empty($_GET) && ! empty($uri)) {
                $noParams = explode('?', $_SERVER['REQUEST_URI'])[0];
                try {
                    $params = LinkHelper::decryptLink($noParams);
                    parse_str($params, $_GET);
                } catch (Exception) {
                    exit(WebHelper::HTTP_BAD_REQUEST);
                }
            }
            static::getInstance()->add($_GET);
            $defaultContext = Application::getInstance()->get('defaultcontext');
            $context        = static::getInstance()->get(Mvc::CONTEXT, $defaultContext);
            static::getInstance()->set(Mvc::CONTEXT, $context);
            $defaultView = Application::getInstance()->get('context>' . $context . '>defaultview');
            $view        = static::getInstance()->get(Mvc::VIEW, $defaultView);
            static::getInstance()->set(Mvc::VIEW, $view);
        }

        return $this;
    }

    /**
     * I may convert the given $readable path into real Request data
     *
     * @param string $readable
     *
     * @throws \noxkiwi\singleton\Exception\SingletonException
     * @return       array
     */
    protected function getRedirection(string $readable): array
    {
        if (! Environment::getInstance()->exists('urlrewrite')) {
            return [];
        }
        try {
            $readable = strtok(strtolower($readable), '?');

            return UrlRewrite::getInstance()->get($readable);
        } catch (Exception $exception) {
            ErrorHandler::handleException($exception);
        }

        return [];
    }

    /**
     * @deprecated
     *
     * @param string      $rl
     * @param string|null $context
     * @param string|null $view
     * @param string|null $action
     */
    public function setRedirect(string $rl, string $context = null, string $view = null, string $action = null): void
    {
        if (str_contains($rl, '://') || strncmp($rl, '/', 1) === 0) {
            $this->redirect = $rl;

            return;
        }
        $this->redirect = LinkHelper::makeParameters(compact('context', 'view', 'action'));
    }

    /**
     * I will try to deserialize the body of the POST data to an array.
     *
     * @deprecated
     */
    public function injectJsonData(): void
    {
        $jsonString = file_get_contents('php://input');
        if (empty($jsonString)) {
            return;
        }
        try {
            $data = JsonHelper::decodeStringToArray($jsonString);
            if (! is_array($data) || empty($data)) {
                return;
            }
        } catch (InvalidJsonException $exception) {
            ErrorHandler::handleException($exception);

            return;
        }
        $this->add($data);
    }
}
