<?php declare(strict_types = 1);
namespace noxkiwi\core\Helper;

use noxkiwi\core\Exception\CryptographyException;
use noxkiwi\core\Exception\SystemComponentException;
use function base64_decode;
use function base64_encode;
use function extension_loaded;
use function is_string;
use function openssl_decrypt;
use function openssl_encrypt;
use function substr;
use const E_ERROR;
use const E_WARNING;

/**
 * I am the helper for cryptographic operations.
 *
 * @package      noxkiwi\core
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2016 - 2022 noxkiwi
 * @version      1.0.1
 * @link         https://nox.kiwi/
 */
abstract class CryptographyHelper
{
    /**
     * I will encrypt the given $decrypted data.
     *
     * @param string $decrypted
     * @param string $key
     * @param string $iv
     *
     * @throws \noxkiwi\core\Exception\SystemComponentException
     * @return string
     */
    public static function encrypt(string $decrypted, string $key, string $iv): string
    {
        if (! extension_loaded('openssl')) {
            throw new SystemComponentException('openSSL unavailable', E_ERROR);
        }

        return base64_encode(openssl_encrypt($decrypted, 'AES-256-CBC', $key, 0, substr($iv, 0, 16)));
    }

    /**
     * I will decrypt the given string.
     *
     * @param string $encrypted
     * @param string $key
     * @param string $iv
     *
     * @throws \noxkiwi\core\Exception\CryptographyException
     * @throws \noxkiwi\core\Exception\SystemComponentException
     * @return string
     */
    public static function decrypt(string $encrypted, string $key, string $iv): string
    {
        if (! extension_loaded('openssl')) {
            throw new SystemComponentException('openSSL unavailable', E_ERROR);
        }
        $encrypted = base64_decode($encrypted);
        if (! is_string($encrypted)) {
            throw new CryptographyException('EXCEPTION_DECRYPT_BASEDECODEERROR', E_WARNING, $encrypted);
        }
        $decrypted = openssl_decrypt($encrypted, 'AES-256-CBC', $key, 0, substr($iv, 0, 16));
        if ($decrypted === false) {
            $info = ['data' => $encrypted, 'key' => $key, 'iv' => $iv];
            throw new CryptographyException('EXCEPTION_DECRYPT_SSLDECRYPTERROR', E_WARNING, $info);
        }

        return $decrypted;
    }
}
