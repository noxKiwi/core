<?php declare(strict_types = 1);
namespace noxkiwi\core\Helper;

use JetBrains\PhpStorm\NoReturn;
use JetBrains\PhpStorm\Pure;
use noxkiwi\core\Constants\Mvc;
use noxkiwi\core\Environment;
use noxkiwi\core\Filesystem;
use noxkiwi\core\Path;
use noxkiwi\core\Request;
use function header;
use function in_array;
use function ob_get_clean;
use function ob_start;
use function str_contains;
use function strtolower;

/**
 * I am
 *
 * @package      noxkiwi\core
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2016 - 2018 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
abstract class FrontendHelper
{
    /** @var string[] $resources */
    public static array $resources = [];

    /**
     * I will return the correct icon code for the desired icon.
     *
     * @param string|null $icon
     * @param null        $classes
     *
     * @return string
     */
    public static function icon(string $icon = null, $classes = null): string
    {
        $icon    ??= 'asterisk';
        $classes ??= '';

        return '<i class="' . Request::getInstance()->get('templateconfig>iconprefix', 'fa fa-') . $icon . ' ' . $classes . '"></i>';
    }

    /**
     * @param string      $type
     * @param string      $file
     * @param string|null $location
     */
    public static function addResource(string $type, string $file, string $location = null): void
    {
        $location ??= 'footer';
        if (! in_array($type, ['js', 'css', 'png', 'jpg'])) {
            return;
        }
        static::$resources[$location][$type][] = LinkHelper::makeUrl([
                                                                         Mvc::CONTEXT => 'resource',
                                                                         Mvc::VIEW    => 'file',
                                                                         'file'       => static::getPath($type, $file)
                                                                     ]);
    }

    /**
     * I will take care that on PRODUCTION environment only .minified JS will be loaded.
     *
     * @param string $type
     * @param string $file
     *
     * @return string
     */
    private static function getPath(string $type, string $file): string
    {
        if (in_array($type, ['css', 'js'], true) && Environment::runs(Environment::PRODUCTION) && ! str_contains($file, '.min')) {
            $file .= '.min';
        }

        return $type . '/' . $file;
    }

    /**
     * @param string|null $location
     *
     * @return string
     */
    #[Pure] public static function getResourceList(string $location = null): string
    {
        $location ??= 'footer';
        if (empty(static::$resources[$location])) {
            return '';
        }
        $return       = '';
        $jsResources  = static::$resources[$location]['js'] ?? [];
        $cssResources = static::$resources[$location]['css'] ?? [];
        foreach ($jsResources as $jsResource) {
            $return .= static::getJsResource($jsResource);
        }
        foreach ($cssResources as $cssResource) {
            $return .= static::getCssResource($cssResource);
        }

        return $return;
    }

    /**
     * I will add a script tag to import from the given $jsResource path.
     *
     * @param string $jsResource
     *
     * @return string
     */
    public static function getJsResource(string $jsResource): string
    {
        return <<<HTML
<script type="application/javascript" src="$jsResource"></script>
HTML;
    }

    /**
     * I will add a link tag to import from the given $cssResource path.
     *
     * @param string $cssResource
     *
     * @return string
     */
    public static function getCssResource(string $cssResource): string
    {
        return <<<HTML
<link rel="stylesheet" href="$cssResource" />
HTML;
    }

    /**
     * I will take care that the application is stopped.
     *
     * @param string $filePath
     * @param string $httpHeader
     * @param int    $statusCode
     *
     */
    #[NoReturn] public static function outputExit(string $filePath, string $httpHeader, int $statusCode): void
    {
        echo static::parseFile(Path::getInheritedPath($filePath));
        header($httpHeader);
        exit($statusCode);
    }

    /**
     * Includes the requested $file into a separate output buffer and returns the content, after parsing $data to it
     *
     * @param string     $file
     * @param mixed|null $data
     *
     * @return       string
     * @noinspection PhpUnusedParameterInspection
     */
    final public static function parseFile(string $file, mixed $data = null): string
    {
        if ($file === '') {
            return '';
        }
        ob_start();
        include $file;

        return ob_get_clean();
    }

    /**
     * Same as parseFile but including logic for inherited path resolving.
     *
     * @param string $file
     * @param mixed  $data
     *
     * @return       string
     */
    final public static function parse(string $file, mixed $data = null): string
    {
        return self::parseFile(Path::getInheritedPath($file), $data);
    }

    /**
     * I will output the requested file's content.
     *
     * @throws \noxkiwi\singleton\Exception\SingletonException
     */
    protected function viewFile(): void
    {
        $request = Request::getInstance();
        $type    = $request->get('type', '');
        $file    = $request->get('resource', '');
        if ($type === '' || $file === '') {
            return;
        }
        [$type, $extension] = static::getTypeData($type);
        $relativePath = "'resource/$type/$file.$extension";
        $absolutePath = Path::getInheritedPath($relativePath);
        echo Filesystem::getInstance()->fileRead($absolutePath);
        exit();
    }

    /**
     * I will return information about the given $type.
     *
     * @param string $type
     *
     * @return array
     */
    private static function getTypeData(string $type): array
    {
        return match (strtolower($type)) {
            'js'    => ['js', 'js', MimeHelper::TYPE_JS],
            'png'   => ['image', 'png', MimeHelper::TYPE_PNG],
            'jpg'   => ['image', 'jpg', MimeHelper::TYPE_JPG],
            'gif'   => ['image', 'gif', MimeHelper::TYPE_GIF],
            'css'   => ['image', 'css', MimeHelper::TYPE_CSS],
            default => ['', '', ''],
        };
    }
}
