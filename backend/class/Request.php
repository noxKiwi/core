<?php declare(strict_types = 1);
namespace noxkiwi\core;

use JetBrains\PhpStorm\Pure;
use noxkiwi\core\Helper\WebHelper;
use noxkiwi\core\Interfaces\RequestInterface;
use noxkiwi\core\Request\CliRequest;
use noxkiwi\core\Request\HttpRequest;
use noxkiwi\core\Traits\DatacontainerTrait;
use noxkiwi\core\Traits\LanguageImprovementTrait;
use function date;
use function json_encode;
use function md5;
use const PHP_SAPI;

/**
 * I am the Core's Request representation.
 * The Request object is singleton inherited.
 * It is used to get information into the Context.
 *
 * @package      noxkiwi\core
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2016 - 2021 noxkiwi
 * @version      1.0.2
 * @link         https://nox.kiwi/
 */
abstract class Request implements RequestInterface
{
    use DatacontainerTrait;
    use LanguageImprovementTrait;

    public const REQUEST_HTTP = WebHelper::PROTOCOL_HTTPS;
    public const REQUEST_CLI  = 'cli';
    /** @var \noxkiwi\core\Request I am the instance. */
    private static Request $inst;

    /**
     * If the single instance does not exist, create it.
     * Return the single instance then.
     *
     * @noinspection PhpMissingParentCallCommonInspection
     *
     * @return \noxkiwi\core\Request
     */
    public static function getInstance(): static
    {
        if (! isset(static::$inst)) {
            $className    = static::getRequestType();
            static::$inst = new $className();
            static::$inst->build();
        }

        return static::$inst;
    }

    /**
     * Returns the Request type that is used for this Request
     *
     * @return       string
     */
    #[Pure] private static function getRequestType(): string
    {
        if (static::isCli()) {
            return CliRequest::class;
        }

        return HttpRequest::class;
    }

    /**
     * returns true if the current php process is being run from a command line interface.
     *
     * @return       bool
     */
    public static function isCli(): bool
    {
        return PHP_SAPI === 'cli';
    }

    /**
     * @inheritDoc
     */
    public function build(): Request
    {
        return $this;
    }

    /**
     * I will return whether the current request is a POST request.
     * @return bool
     */
    final public static function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === WebHelper::METHOD_POST;
    }

    /**
     * I will return the identifier of this distinct request.
     * @return string
     */
    final public function getIdentifier(): string
    {
        if (empty($this->identifier)) {
            $id               = md5(json_encode($this->get()) . date('Y-m-d H:i:s:u'));
            $this->identifier = $id;
        }

        return $this->identifier;
    }
}
