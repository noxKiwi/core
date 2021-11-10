<?php declare(strict_types = 1);
namespace noxkiwi\core\Interfaces;

use noxkiwi\core\Request;

/**
 * I am the interface for all authentication methods
 *
 * @package      noxkiwi\core
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2016 - 2021 noxkiwi
 * @version      1.1.1
 * @link         https://nox.kiwi/
 */
interface AuthInterface
{
    /**
     * I will use the $username and $password to authenticate a user.
     * <br />Returns an array of valid user data if succeeded
     * <br />Returns an empty array if authentication failed
     *
     * @param string $userName <i>Try authentication for this user...</i>
     * @param string $password <i>... using this password</i>
     *
     * @return       array <i>Array of user information. Is <b>EMPTY</b> on authentication failure</i>
     */
    public function authenticate(string $userName, string $password): array;

    /**
     * I will return the password for the given $username $password combination
     * <br />You may want to create your own hashing algo using this method.
     * <br />This method is public to be accessible for contexts and models (e.g. automatic user creation, password
     * resetting)
     *
     * @param string $userName <i>The username to hash</i>
     * @param string $password <i>The password to hash</i>
     *
     * @return       string <i>The hashed combination of $username and $password</i>
     */
    public function passwordMake(string $userName, string $password): string;

    /**
     * I will return a URL that will ask the client to login.
     *
     * @param Request $request
     *
     * @return string
     */
    public function getLoginUrl(Request $request): string;
}
