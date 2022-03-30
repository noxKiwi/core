<?php declare(strict_types = 1);
namespace noxkiwi\core;

use noxkiwi\core\Exception\ConfigurationException;
use noxkiwi\core\Helper\JsonHelper;
use noxkiwi\core\Interfaces\DatacontainerInterface;
use noxkiwi\core\Traits\DatacontainerTrait;
use noxkiwi\core\Traits\LanguageImprovementTrait;
use noxkiwi\singleton\Singleton;
use function define;
use function defined;
use function func_get_args;
use function in_array;
use function is_array;
use const CORE_ENVIRONMENT;
use const E_ERROR;
use const NK_ENVIRONMENT;

/**
 * I am the Environment base.
 *
 * @package      noxkiwi\core
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2019 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class Environment extends Singleton implements DatacontainerInterface
{
    use DatacontainerTrait;
    use LanguageImprovementTrait;

    public const PRODUCTION  = 'production';
    public const UAT         = 'uat';
    public const BETA        = 'beta';
    public const ALPHA       = 'alpha';
    public const DEVELOPMENT = 'development';
    public const STAGES      = [
        self::PRODUCTION,
        self::UAT,
        self::BETA,
        self::ALPHA,
        self::DEVELOPMENT
    ];
    public static bool $loaded;

    /**
     * Environment constructor.
     *
     *
     * @throws \noxkiwi\core\Exception
     * @throws \noxkiwi\core\Exception\ConfigurationException
     * @throws \noxkiwi\core\Exception\InvalidJsonException
     */
    protected function initialize(): void
    {
        parent::initialize();
        $data = JsonHelper::decodeFileToArray(self::getPath());
        if (empty($data)) {
            throw new ConfigurationException('ENVIRONMENT_INVALID', E_ERROR, ['path'=>self::getPath()]);
        }
        $this->add($data);
        self::$loaded = true;
    }

    /**
     * I will return the path to the environment config file.
     * @throws \noxkiwi\core\Exception\ConfigurationException
     * @return string
     */
    private static function getPath(): string
    {
        if (! defined('NK_ENVIRONMENT')) {
            throw new ConfigurationException('NO ENVIRONMENT CONSTANT "MK_ENVIRONMENT" DEFINED', E_ERROR);
        }

        return NK_ENVIRONMENT;
    }

    /**
     * Returns the current environment identifier
     *
     * @return       string
     */
    public static function getCurrent(): string
    {
        if (defined('CORE_ENVIRONMENT')) {
            return CORE_ENVIRONMENT;
        }
        define('CORE_ENVIRONMENT', self::PRODUCTION);

        return CORE_ENVIRONMENT;
    }

    /**
     * I will return the application's configuration tree identified by the given $type and $key
     *
     * @example      get(Mvc::CONTEXT, 'home') // APPJSON[Mvc::CONTEXT]['home']
     *
     * @param string $type
     * @param string $identifier
     *
     * @return mixed
     * @throws \noxkiwi\core\Exception\ConfigurationException
     */
    public function getDriverConfig(string $type, string $identifier): mixed
    {
        $value = $this->get("$type>$identifier");
        if (! empty($value)) {
            return $value;
        }
        if (! $this->exists($type)) {
            throw new ConfigurationException("Environment: Type &quot;$type&quot; is not configured.", E_ERROR, func_get_args());
        }
        throw new ConfigurationException("Environment: Entry &quot;$identifier&quot; in Type &quot;$type&quot; is not configured.", E_ERROR, func_get_args());
    }

    /**
     * I will return whether the given $environment really is currenty being used or not.
     *
     * You can either put a single Environment constant here, or an array of Environment constants.
     *
     * @example Environment::runs(Environment::PRODUCITON);
     * @example Environment::runs([Environment::UAT, Environment::PRODUCTION]);
     *
     * @param array|string $environment
     *
     * @return bool
     */
    public static function runs(array|string $environment): bool
    {
        if (is_array($environment)) {
            foreach ($environment as $item) {
                if (self::runs($item)) {
                    return true;
                }
            }

            return false;
        }
        if (! in_array($environment, self::STAGES, true)) {
            return false;
        }

        return self::getCurrent() === $environment;
    }
}
