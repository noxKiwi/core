<?php declare(strict_types = 1);
namespace noxkiwi\core;

use JetBrains\PhpStorm\Pure;
use noxkiwi\singleton\Exception\SingletonException;

/**
 * I am the collection of folders and files.
 *
 * @package      noxkiwi\core
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2019 - 2021 noxkiwi
 * @version      1.0.2
 * @link         https://nox.kiwi/
 */
abstract class Path
{
    // CONFIG
    public const CONFIG_DIR         = 'config/';
    public const CONFIG_APPLICATION = self::CONFIG_DIR . 'app.json';
    public const CONFIG_CONTEXT_DIR = self::CONFIG_DIR . 'context/';
    public const CONFIG_URL_REWRITE = self::CONFIG_DIR . 'urlrewrite.json';
    // FRONTEND
    public const FRONTEND_DIR  = 'frontend/';
    public const VIEW_DIR      = self::FRONTEND_DIR . 'view/';
    public const TEMPLATE_DIR  = self::FRONTEND_DIR . 'template/';
    public const PAGE_DIR      = self::FRONTEND_DIR . 'page/';
    public const TEMPLATE_FILE = 'template.php';
    // PAGES
    public const PAGE_403         = self::PAGE_DIR . 'notallowed.php';
    public const PAGE_404         = self::PAGE_DIR . 'notfound.php';
    public const PAGE_500         = self::PAGE_DIR . 'error.php';
    public const PAGE_MAINTENANCE = self::PAGE_DIR . 'maintenance.php';
    // RESOURCES
    public const RESOURCES_DIR = 'resources/';
    // RESOURCES
    public const LOG_DIR = '/var/www/_log/';
    /** @var string Absolute path to the web root of the application. */
    public static string $webRoot = '';
    // RESOURCES
    /** @var string Absolute path to the vendor folder. */
    public static string $vendorDir = '';
    // LOG
    /** @var string I am the hostname that shall be used for all resources. */
    public static string $resourceHost = '';
    /** @var string[] I am a cache for inherited paths to store during runtime */
    private static array $inheritedPaths = [];

    /**
     * Get path of file (in APP dir OR in core dir) if it exists there - throws error if neither
     *
     * @param string $file
     *
     * @return string
     */
    final public static function getInheritedPath(string $file): string
    {
        try {
            $fileSystem = Filesystem::getInstance();
        } catch (SingletonException $exception) {
            ErrorHandler::handleException($exception);

            return '';
        }
        if (isset(static::$inheritedPaths[$file])) {
            return static::$inheritedPaths[$file];
        }
        $fileName = self::getHomeDir() . $file;
        if ($fileSystem->fileAvailable($fileName)) {
            static::$inheritedPaths[$file] = $fileName;

            return static::$inheritedPaths[$file];
        }
        $fileName = static::$vendorDir . "noxkiwi/core/$file";
        if ($fileSystem->fileAvailable($fileName)) {
            static::$inheritedPaths[$file] = $fileName;

            return static::$inheritedPaths[$file];
        }

        return '';
    }

    /**
     * Returns the directory where the App must be stored in
     * <br />This method relies on the static vendorDir
     *
     * @param string|null $vendor
     * @param string|null $app
     *
     * @return       string
     */
    #[Pure] final public static function getHomeDir(string $vendor = null, string $app = null): string
    {
        $vendor ??= App::getVendor();
        $app    ??= App::getApp();

        return static::$vendorDir . "$vendor/$app/";
    }
}
