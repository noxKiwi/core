<?php declare(strict_types = 1);
namespace noxkiwi\core\Helper;

use Exception;
use JsonException;
use noxkiwi\core\Exception\FilesystemException;
use noxkiwi\core\Exception\InvalidJsonException;
use noxkiwi\core\Filesystem;
use function file_get_contents;
use function json_decode;
use function json_encode;
use function json_last_error;
use const E_WARNING;
use const JSON_ERROR_CTRL_CHAR;
use const JSON_ERROR_DEPTH;
use const JSON_ERROR_NONE;
use const JSON_ERROR_STATE_MISMATCH;
use const JSON_ERROR_SYNTAX;
use const JSON_ERROR_UTF8;
use const JSON_PRETTY_PRINT;
use const JSON_THROW_ON_ERROR;

/**
 * I am the Helper class for handling JSON.
 * Decoding, encoding, saving, reading and all that stuff is handeled here.
 *
 * @package      noxkiwi\core
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2016 - 2021 noxkiwi
 * @version      1.0.1
 * @link         https://nox.kiwi/
 */
abstract class JsonHelper
{
    /** @var array I am the list of cached Json files. */
    private static array $cachedFiles;

    /**
     * I will locate the given $file and return the JSON content as an array
     *
     * @param string $file
     *
     * @throws \noxkiwi\core\Exception\InvalidJsonException
     * @return mixed
     */
    public static function decodeFileToArray(string $file): mixed
    {
        $simpleName = $file;
        if (empty($file)) {
            return null;
        }
        if (isset (static::$cachedFiles[$file])) {
            return static::$cachedFiles[$file];
        }
        try {
            if (! Filesystem::getInstance()->fileAvailable($file)) {
                return null;
            }
            static::$cachedFiles[$simpleName] = static::decodeStringToArray(
                Filesystem::getInstance()->fileRead($file)
            );
        } catch (Exception) {
            throw new InvalidJsonException("$file is not valid JSON.", E_WARNING);
        }

        return static::$cachedFiles[$simpleName];
    }

    /**
     * I will convert the given $json string into an array
     *
     * @param string $json
     *
     * @throws \noxkiwi\core\Exception\InvalidJsonException
     * @return mixed
     */
    public static function decodeStringToArray(string $json): mixed
    {
        try {
            $jsonResult = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            throw new InvalidJsonException('EXCEPTION_DECODESTRINGTOARRAY_INVALIDJSON', E_WARNING, $exception);
        }
        if ($jsonResult === null) {
            $info = ['json' => $json, 'error' => static::getJsonError()];
            throw new InvalidJsonException('EXCEPTION_DECODESTRINGTOARRAY_INVALIDJSON', E_WARNING, $info);
        }

        return $jsonResult;
    }

    /**
     * I will return a statement on what was wrong with the last JSON_DECODE call.
     * @return string
     */
    public static function getJsonError(): string
    {
        return match (json_last_error()) {
            JSON_ERROR_NONE => ' - No errors',
            JSON_ERROR_DEPTH => ' - Maximum stack depth exceeded',
            JSON_ERROR_STATE_MISMATCH => ' - Underflow or the modes mismatch',
            JSON_ERROR_CTRL_CHAR => ' - Unexpected control character found',
            JSON_ERROR_SYNTAX => ' - Syntax error, malformed JSON',
            JSON_ERROR_UTF8 => ' - Malformed UTF-8 characters, possibly incorrectly encoded',
            default => ' - Unknown error',
        };
    }

    /**
     * I will locate the given $file and return the JSON as an object
     *
     * @param string $file
     *
     * @throws \noxkiwi\core\Exception
     * @throws \noxkiwi\core\Exception\FilesystemException
     * @throws \noxkiwi\core\Exception\InvalidJsonException
     * @return mixed
     */
    public static function decodeFileToObject(string $file): mixed
    {
        if (! Filesystem::getInstance()->fileAvailable($file)) {
            throw new FilesystemException('EXCEPTION_DECODESTRINGTOARRAY_INVALIDJSON', E_WARNING, ['file' => $file]);
        }

        return static::decodeStringToObject(file_get_contents($file));
    }

    /**
     * I will return the given $json string as an object
     *
     * @param string $json
     *
     * @throws \noxkiwi\core\Exception\InvalidJsonException
     * @return mixed
     */
    public static function decodeStringToObject(string $json): mixed
    {
        try {
            $json = json_decode($json, true, 512, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
        } catch (JsonException $exception) {
            $info = ['json' => $json, 'error' => static::getJsonError(), 'inner' => $exception];
            throw new InvalidJsonException('INVALID_JSON_CAUGHT', E_WARNING, $info);
        }
        if ($json === null) {
            $info = ['json' => $json, 'error' => static::getJsonError()];
            throw new InvalidJsonException('INVALID_JSON', E_WARNING, $info);
        }

        return $json;
    }

    /**
     * I will encode the given $data to a valid JSON string and return it.
     *
     * @param mixed $data
     *
     * @return string
     */
    public static function encode(mixed $data): string
    {
        try {
            return (string)json_encode($data, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            return '';
        }
    }
}
