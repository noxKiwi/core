<?php declare(strict_types = 1);
namespace noxkiwi\core;

use JetBrains\PhpStorm\Pure;
use noxkiwi\core\Constants\Mvc;
use noxkiwi\core\Helper\WebHelper;
use noxkiwi\core\Interfaces\ResponseInterface;
use noxkiwi\core\Response\CliResponse;
use noxkiwi\core\Response\HttpResponse;
use noxkiwi\core\Traits\DatacontainerTrait;
use noxkiwi\log\Traits\LogTrait;

/**
 * I am the Core's Response representation.
 * The Response object is singleton inherited.
 * It is used to get information out of the Context.
 *
 * @package      noxkiwi\core
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2016 - 2018 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
abstract class Response implements ResponseInterface
{
    use DatacontainerTrait;
    use LogTrait;

    /** @var string Contains the derived output */
    protected string $output;
    /** @var array I contain various frontend resource */
    protected array $resources = [];
    /** @var int Contains the status code */
    protected int $statusCode = WebHelper::HTTP_OKAY;
    /** @var string Contains the status text */
    protected string    $statusText = 'OK';
    private static self $instance;

    /**
     * If the single instance does not exist, create it.
     * Return the single instance then.
     *
     * @return \noxkiwi\core\Response
     */
    public static function getInstance(): static
    {
        if (isset(static::$instance) && static::$instance instanceof self) {
            return static::$instance;
        }
        $className = static::getResponseType();
        $request   = Request::getInstance();
        $response  = new $className();
        $response->set(Mvc::CONTEXT, $request->get(Mvc::CONTEXT));
        $response->set(Mvc::VIEW, $request->get(Mvc::VIEW));
        $response->set(Mvc::ACTION, $request->get(Mvc::ACTION));
        static::$instance = $response;

        return static::$instance;
    }

    /**
     * Returns the Request type that is used for this Request
     *
     * @return       string
     */
    #[Pure] protected static function getResponseType(): string
    {
        if (Request::isCli()) {
            return CliResponse::class;
        }

        return HttpResponse::class;
    }

    /**
     * Returns the status code of the current Response
     *
     * @return       int
     */
    public function getStatuscode(): int
    {
        return $this->statusCode;
    }

    /**
     * Helper to set HTTP status codes
     *
     * @param int    $statusCode
     * @param string $statusText
     *
     * @return       Response
     */
    public function setStatusCode(int $statusCode, string $statusText): Response
    {
        $this->statusCode = $statusCode;
        $this->statusText = $statusText;

        return $this;
    }

    /**
     * Actually sends output to the Response HTTP Stream
     */
    public function pushOutput(): void
    {
        echo $this->getOutput();
    }

    /**
     * Returns the output content of this Response
     *
     * @return       string
     */
    public function getOutput(): string
    {
        return $this->output ?? '';
    }

    /**
     * Sets the output of this Response
     *
     * @param string $output
     */
    public function setOutput(string $output): void
    {
        $this->output = $output;
    }
}
