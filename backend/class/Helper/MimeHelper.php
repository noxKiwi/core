<?php declare(strict_types = 1);
namespace noxkiwi\core\Helper;

/**
 * I am the MIME type helper class
 *
 * @package      noxkiwi\core
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2016 - 2018 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
abstract class MimeHelper
{
    // FILE EXTENSIONS
    public const EXTENSION_JPG  = 'jpg';
    public const EXTENSION_JPEG = 'jpeg';
    public const EXTENSION_PDF  = 'pdf';
    public const EXTENSION_PNG  = 'png';
    public const EXTENSION_GIF  = 'gif';
    public const EXTENSION_JSON = 'json';
    public const EXTENSION_CSS  = 'css';
    public const EXTENSION_JS   = 'js';
    // MIME TYPES
    public const TYPE_JSON           = 'text/json';
    public const TYPE_PDF            = 'application/pdf';
    public const TYPE_FIF            = 'image/fif';
    public const TYPE_IEF            = 'image/ief';
    public const TYPE_GIF            = 'image/gif';
    public const TYPE_PNG            = 'image/png';
    public const TYPE_JPG            = 'image/jpg';
    public const TYPE_JPG_2000       = 'image/jpg2000';
    public const TYPE_JPEG_2000      = 'image/jpeg2000';
    public const TYPE_JPEG           = 'image/jpeg';
    public const TYPE_TIFF           = 'image/tiff';
    public const TYPE_VASA           = 'image/vasa';
    public const TYPE_X_ICON         = 'image/x-icon';
    public const TYPE_JS             = 'text/javascript';
    public const TYPE_CSS            = 'text/css';
    public const TYPE_FORCE_DOWNLOAD = 'application/force-download';
    /**
     * I am the list of file extensions and their expected MIME Types
     * @var array
     */
    public static array $resources = [
        'js'  => ['js', 'js', self::TYPE_JS],
        'css' => ['css', 'css', self::TYPE_CSS],
        'jpg' => ['image', 'jpg', self::TYPE_JPG],
        'gif' => ['image', 'gif', self::TYPE_GIF],
        'png' => ['image', 'png', self::TYPE_PNG]
    ];

    /**
     * I will send the Content-Type header.
     *
     * @param string $type
     */
    public static function sendHeaders(string $type): void
    {
        if (headers_sent()) {
            return;
        }
        $contentType = static::getResourceFromType($type)[2];
        header('Content-Type: ' . $contentType);
    }

    /**
     * I will return the resources of the given $type.
     *
     * @param string $type
     *
     * @return array|null
     */
    public static function getResourceFromType(string $type): ?array
    {
        return static::$resources[strtolower($type)] ?? null;
    }

    /**
     * I will utilize the lowercase variant of $extension to determine a matching mime type.
     *
     * @param string $extension
     * @param string $default
     *
     * @return string
     */
    final public static function getFromExtension(string $extension, string $default): string
    {
        return match (strtolower($extension)) {
            self::EXTENSION_PDF                       => self::TYPE_PDF,
            self::EXTENSION_GIF                       => self::TYPE_GIF,
            self::EXTENSION_PNG                       => self::TYPE_PNG,
            self::EXTENSION_JPEG, self::EXTENSION_JPG => self::TYPE_JPG,
            self::EXTENSION_JSON                      => self::TYPE_JSON,
            self::EXTENSION_CSS                       => self::TYPE_CSS,
            self::EXTENSION_JS                        => self::TYPE_JS,
            default                                   => $default,
        };
    }

    /**
     * I will return the Mime type according to the given $filePath.
     *
     * @param string $filePath
     *
     * @return string
     */
    final public static function getFromFile(string $filePath): string
    {
        $exploded = explode('.', $filePath);

        return self::getFromExtension(end($exploded), self::TYPE_FORCE_DOWNLOAD);
    }
}
