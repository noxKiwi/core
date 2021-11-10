<?php declare(strict_types = 1);
namespace noxkiwi\core\Helper;

use Exception;
use JetBrains\PhpStorm\NoReturn;
use noxkiwi\core\Constants\Mvc;
use noxkiwi\core\Response;
use function is_array;
use function is_string;
use function strlen;

/**
 * I am the link generator class.
 * I will help you builiding building MVC links, as well as secured MVC links.
 *
 * @package      noxkiwi\core
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2016 - 2020 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
abstract class LinkHelper
{
    /** @var bool Encrypt links to obfuscate the underlying mvc? */
    public static bool $encryptLinks = false;
    /** @var string The password */
    public static string $secret = 'k26p555ug9d72j028f2dprknf';

    /**
     * I will decode the given $params string into a queryparam string.
     *
     * @param string $encrypted
     *
     * @return string
     */
    public static function decryptLink(string $encrypted): string
    {
        try {
            $encrypted = substr($encrypted, 1, strlen($encrypted));

            return (string)str_replace('?', '', CryptographyHelper::decrypt($encrypted, static::$secret, static::$secret));
        } catch (Exception) {
            return '';
        }
    }

    /**
     * I will return the url that is either for the MVC pattern or a real one.
     *
     * @param array|string $url
     *
     * @return string
     */
    public static function get(array|string $url): string
    {
        if (is_array($url)) {
            return static::makeUrl($url);
        }
        if (is_string($url)) {
            return $url;
        }

        return '#';
    }

    /**
     * I will return a URL that overrides or enhances the current response with the given $params data.
     * I will call makeParameters to ensure that no illegal arguments will be part of the URL.
     *
     * @param array|null $parameters
     *
     * @return string
     */
    public static function makeUrl(array $parameters = null): string
    {
        $parameters ??= [];
        $response   = Response::getInstance();
        $original   = [
            Mvc::CONTEXT => $response->get(Mvc::CONTEXT),
            Mvc::VIEW    => $response->get(Mvc::VIEW),
            Mvc::ACTION  => $response->get(Mvc::ACTION),
        ];
        $parameters = ArrayHelper::arrayMergeRecursive($original, $parameters);

        return static::makeParameters($parameters);
    }

    /**
     * I will return the given $params as a URI parameter string.
     *
     * @param array $params
     *
     * @return string
     */
    public static function makeParameters(array $params): string
    {
        return static::encryptLink('?' . http_build_query($params));
    }

    /**
     * I will encode the given $params string with base64 if not on development.
     *
     * @param string $decrypted
     *
     * @return string
     */
    private static function encryptLink(string $decrypted): string
    {
        if (! static::$encryptLinks) {
            return $decrypted;
        }

        return CryptographyHelper::encrypt($decrypted, static::$secret, static::$secret);
    }

    /**
     * I will send the user to the given page.
     *
     * The $statusCode defaults to:
     * @see \noxkiwi\core\Helper\WebHelper::HTTP_MOVED_PERMANENTLY
     *
     * @param array|string $redirect
     * @param int          $statusCode
     */
    #[NoReturn] public static function forward(array|string $redirect, int $statusCode = WebHelper::HTTP_MOVED_PERMANENTLY): void
    {
        if (is_array($redirect)) {
            $redirect = LinkHelper::makeUrl($redirect);
        }
        if (! is_string($redirect)) {
            return;
        }
        header("Location: $redirect");
        header("HTTP/1.0 $statusCode " . WebHelper::$responseCodes[$statusCode]);
        exit(WebHelper::HTTP_PERMANENT_REDIRECT);
    }
}
