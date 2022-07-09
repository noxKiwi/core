<?php declare(strict_types = 1);
namespace noxkiwi\core\Helper;

use JetBrains\PhpStorm\Pure;
use function explode;
use function getenv;
use function ip2long;

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
abstract class WebHelper
{
    public const METHOD_POST                         = 'POST';
    public const METHOD_GET                          = 'GET';
    public const METHOD_PUT                          = 'PUT';
    public const METHOD_DELETE                       = 'DELETE';
    public const METHODS                             = [
        self::METHOD_POST,
        self::METHOD_GET,
        self::METHOD_PUT,
        self::METHOD_DELETE
    ];
    public const PROTOCOL_HTTP                       = 'http';
    public const PROTOCOL_HTTPS                      = 'https';
    public const PROTOCOLS                           = [
        self::PROTOCOL_HTTP,
        self::PROTOCOL_HTTPS
    ];
    public const HTTP_OKAY                           = 200;
    public const HTTP_MULTIPLE_CHOICES               = 300;
    public const HTTP_MOVED_PERMANENTLY              = 301;
    public const HTTP_FOUND                          = 302;
    public const HTTP_SEE_OTHER                      = 303;
    public const HTTP_NOT_MODIFIED                   = 304;
    public const HTTP_USE_PROXY                      = 305;
    public const HTTP_SWITCH_PROXY                   = 306;
    public const HTTP_TEMPORARY_REDIRECT             = 307;
    public const HTTP_PERMANENT_REDIRECT             = 308;
    public const HTTP_BAD_REQUEST                    = 400;
    public const HTTP_UNAUTHORIZED                   = 401;
    public const HTTP_PAYMENT_REQUIRED               = 402;
    public const HTTP_FORBIDDEN                      = 403;
    public const HTTP_NOT_FOUND                      = 404;
    public const HTTP_METHOD_NOT_ALLOWED             = 405;
    public const HTTP_NOT_ACCEPTABLE                 = 406;
    public const HTTP_PROXY_AUTHENTICATION_REQUIRED  = 407;
    public const HTTP_REQUEST_TIMEOUT                = 408;
    public const HTTP_CONFLICT                       = 409;
    public const HTTP_GONE                           = 410;
    public const HTTP_LENGTH_REQUIRED                = 411;
    public const HTTP_PRECONDITION_FAILED            = 412;
    public const HTTP_PAYLOAD_TOO_LARGE              = 413;
    public const HTTP_URI_TOO_LONG                   = 414;
    public const HTTP_UNSUPPORTED_MEDIA_TYPE         = 415;
    public const HTTP_RANGE_NOT_SATISFIABLE          = 416;
    public const HTTP_EXPECTATION_FAILED             = 417;
    public const HTTP_MISDIRECTED_REQUEST            = 421;
    public const HTTP_UNPROCESSABLE_ENTITY           = 422;
    public const HTTP_LOCKED                         = 423;
    public const HTTP_FAILED_DEPENDENCY              = 424;
    public const HTTP_TOO_EARLY                      = 425;
    public const HTTP_UPGRADE_REQUIRED               = 426;
    public const HTTP_PRECONDITION_REQUIRED          = 428;
    public const HTTP_TOO_MANY_REQUESTS              = 429;
    public const HTTP_REQUESTHEADER_FIELDS_TOO_LARGE = 431;
    public const HTTP_UNAVAILABLE_FOR_LEGAL_REASONS  = 451;
    public const HTTP_TEAPOT                         = 418;
    public const HTTP_POLICY_NOT_FULFILLED           = 420;
    public const HTTP_NO_RESPONSE                    = 444;
    public const HTTP_RETIRED                        = 449;
    public const HTTP_CLIENT_CLOSED                  = 499;
    public const HTTP_SERVER_ERROR                   = 500;
    public const HTTP_NOT_IMPLEMENTED                = 501;
    public const HTTP_BAD_GATEWAY                    = 502;
    public const HTTP_SERVICE_UNAVAILABLE            = 503;
    public const HTTP_GATEWAY_TIMEOUT                = 504;
    public const HTTP_VERSION_NOT_SUPPORTED          = 505;
    public const HTTP_VARIANT_ALSO_NEGOTIATES        = 506;
    public const HTTP_INSUFFICIENT_STORAGE           = 507;
    public const HTTP_LOOP_DETECTED                  = 508;
    public const HTTP_BANDWIDTH_LIMIT_EXCEEDED       = 509;
    public const HTTP_NOT_EXTENDED                   = 510;
    public const HTTP_NETWORK_AUTH_REQUIRED          = 511;
    /**
     * I am a list of HTTP status codes and their names.
     *
     * @var array
     */
    public static array $responseCodes = [
        100                          => 'CONTINUE',
        101                          => 'SWITCHING_PROTOCOLS',
        102                          => 'PROCESSING',
        self::HTTP_OKAY              => 'OK',
        201                          => 'CREATED',
        202                          => 'ACCEPTED',
        203                          => 'NON_AUTHORITATIVE_INFORMATION',
        204                          => 'NO_CONTENT',
        205                          => 'RESET_CONTENT',
        206                          => 'PARTIAL_CONTENT',
        207                          => 'MULTI_STATUS',
        300                          => 'MULTIPLE_CHOICES',
        self::HTTP_MOVED_PERMANENTLY => 'MOVED_PERMANENTLY',
        302                          => 'FOUND',
        303                          => 'SEE_OTHER',
        304                          => 'NOT_MODIFIED',
        305                          => 'USE_PROXY',
        306                          => 'SWITCH_PROXY',
        307                          => 'TEMPORARY_REDIRECT',
        400                          => 'BAD_REQUEST',
        401                          => 'UNAUTHORIZED',
        402                          => 'PAYMENT_REQUIRED',
        self::HTTP_FORBIDDEN         => 'FORBIDDEN',
        404                          => 'NOT_FOUND',
        405                          => 'METHOD_NOT_ALLOWED',
        406                          => 'NOT_ACCEPTABLE',
        407                          => 'PROXY_AUTHENTICATION_REQUIRED',
        408                          => 'REQUEST_TIMEOUT',
        self::HTTP_CONFLICT          => 'CONFLICT',
        410                          => 'GONE',
        411                          => 'LENGTH_REQUIRED',
        412                          => 'PRECONDITION_FAILED',
        413                          => 'REQUEST_ENTITY_TOO_LARGE',
        414                          => 'REQUEST_URI_TOO_LONG',
        415                          => 'UNSUPPORTED_MEDIA_TYPE',
        416                          => 'REQUESTED_RANGE_NOT_SATISFIABLE',
        417                          => 'EXPECTATION_FAILED',
        418                          => 'IM_A_TEAPOT',
        422                          => 'UNPROCESSABLE_ENTITY',
        423                          => 'LOCKED',
        424                          => 'FAILED_DEPENDENCY',
        425                          => 'UNORDERED_COLLECTION',
        426                          => 'UPGRADE_REQUIRED',
        449                          => 'RETRY_WITH',
        450                          => 'BLOCKED_BY_WINDOWS_PARENTAL_CONTROLS',
        451                          => 'BLOCKED_FOR_LEGAL_REASONS',
        self::HTTP_SERVER_ERROR      => 'INTERNAL_SERVER_ERROR',
        501                          => 'NOT_IMPLEMENTED',
        502                          => 'BAD_GATEWAY',
        503                          => 'SERVICE_UNAVAILABLE',
        504                          => 'GATEWAY_TIMEOUT',
        505                          => 'HTTP_VERSION_NOT_SUPPORTED',
        506                          => 'VARIAN_ALSO_NEGOTIATES',
        507                          => 'INSUFFICIENT_STORAGE',
        509                          => 'BANDWIDTH_LIMIT_EXCEEDED',
        510                          => 'NOT_EXTENDED'
    ];

    /**
     * I will return whether the given $ipAddress is part of the given $cidr subnet.
     *
     * @param string      $cidr
     * @param string|null $ipAddress
     *
     * @return bool
     */
    #[Pure] public static function isCidr(string $cidr, ?string $ipAddress = null): bool
    {
        if ($ipAddress === null) {
            $ipAddress = static::getClientIp();
        }
        [$net, $mask] = explode('/', $cidr);
        $ipNet  = ip2long($net);
        $ipMask = ~((1 << 32 - (int)$mask) - 1);
        $ipIp   = ip2long($ipAddress);

        return ($ipIp & $ipMask) == ($ipNet & $ipMask);
    }

    /**
     * I will return the client's IP address.
     *
     * @return string IP address string
     */
    public static function getClientIp(): string
    {
        if (getenv('HTTP_CLIENT_IP')) {
            return getenv('HTTP_CLIENT_IP');
        }
        if (getenv('HTTP_X_FORWARDED_FOR')) {
            return getenv('HTTP_X_FORWARDED_FOR');
        }
        if (getenv('HTTP_X_FORWARDED')) {
            return getenv('HTTP_X_FORWARDED');
        }
        if (getenv('HTTP_FORWARDED_FOR')) {
            return getenv('HTTP_FORWARDED_FOR');
        }
        if (getenv('HTTP_FORWARDED')) {
            return getenv('HTTP_FORWARDED');
        }
        if (getenv('REMOTE_ADDR')) {
            return getenv('REMOTE_ADDR');
        }

        return 'UNKNOWN';
    }
}
