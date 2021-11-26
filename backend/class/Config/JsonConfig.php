<?php declare(strict_types = 1);
namespace noxkiwi\core\Config;

use noxkiwi\core\Config;
use noxkiwi\core\ErrorHandler;
use noxkiwi\core\Exception\ConfigurationException;
use noxkiwi\core\Exception\InvalidArgumentException;
use noxkiwi\core\Exception\InvalidJsonException;
use noxkiwi\core\Filesystem;
use noxkiwi\core\Helper\JsonHelper;
use noxkiwi\core\Path;
use function array_replace_recursive;
use function compact;
use function is_array;
use const E_ERROR;

/**
 * I am
 *
 * @package      noxkiwi\core
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2016 - 2021 noxkiwi
 * @version      1.0.1
 * @link         https://nox.kiwi/
 */
final class JsonConfig extends Config
{
    /** @var string I am the file name */
    private string $file;

    /**
     * Creates a config instance and loads the given JSON configuration file as content
     *
     * @param string $file
     * @param bool   $inherit Combine all parent App's configuration with the local one.
     *
     * @throws \noxkiwi\core\Exception\ConfigurationException
     * @throws \noxkiwi\core\Exception\InvalidArgumentException The file parameter is an empty string.
     * @throws \noxkiwi\singleton\Exception\SingletonException  Something really awful happened.
     */
    public function __construct(string $file, bool $inherit = false)
    {
        if (empty($file)) {
            throw new InvalidArgumentException('FILE_IS_EMPTY', E_ERROR);
        }
        $config          = [];
        if (! $inherit) {
            $this->file = (string)$this->getFullPath($file);
            $config     = $this->decodeFile($this->file);
            if (! is_array($config)) {
                throw new ConfigurationException('CONTENT_IS_NOT_AN_ARRAY', E_ERROR, compact('file', 'config'));
            }
            $this->put($config);
        }
        $fullPath = Path::getHomeDir() . $file;
        if (! Filesystem::getInstance()->fileAvailable($fullPath)) {
            return;
        }
        $thisConf = $this->decodeFile($fullPath);
        $config   = array_replace_recursive($config, $thisConf);
        parent::__construct($config);
    }

    /**
     * I will give you the lowest level full path that exists in the appstack.
     *
     * @param string $file
     *
     * @throws \noxkiwi\singleton\Exception\SingletonException
     * @return string|null
     */
    private function getFullPath(string $file): ?string
    {
        if (Filesystem::getInstance()->fileAvailable($file)) {
            return $file;
        }
        $fullPath = Path::getHomeDir() . $file;
        if (Filesystem::getInstance()->fileAvailable($file)) {
            return $fullPath;
        }

        return Path::getInheritedPath($file);
    }

    /**
     * I will decode the given file and return the array of configuration it holds.
     *
     * @param string $fullPath
     *
     * @return       array|null
     */
    protected function decodeFile(string $fullPath): ?array
    {
        try {
            return JsonHelper::decodeFileToArray($fullPath);
        } catch (InvalidJsonException $exception) {
            ErrorHandler::handleException($exception);

            return [];
        }
    }
}
