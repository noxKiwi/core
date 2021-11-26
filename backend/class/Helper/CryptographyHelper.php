<?php declare(strict_types = 1);
namespace noxkiwi\core\Helper;

use noxkiwi\core\Exception\CryptographyException;
use function base64_decode;
use function base64_encode;
use function is_string;
use function openssl_decrypt;
use function openssl_encrypt;
use function substr;
use const E_WARNING;

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
abstract class CryptographyHelper
{
    /**
     * I will encrypt the given $decrypted data.
     *
     * @param string $decrypted
     * @param string $key
     * @param string $iv
     *
     * @return string
     */
    public static function encrypt(string $decrypted, string $key, string $iv): string
    {
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
     * @return string
     */
    public static function decrypt(string $encrypted, string $key, string $iv): string
    {
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
