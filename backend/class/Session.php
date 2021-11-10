<?php declare(strict_types = 1);
namespace noxkiwi\core;

use noxkiwi\core\Interfaces\SessionInterface;
use noxkiwi\singleton\Singleton;

/**
 * I am the base session class. All session objects must be my children.
 *
 * @package      noxkiwi\core
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2016 - 2021 noxkiwi
 * @version      1.0.2
 * @link         https://nox.kiwi/
 */
abstract class Session extends Singleton implements SessionInterface
{
    protected const USE_DRIVER = true;
    public const    SESSIONKEY = 'phpsessid';

    /**
     * I will construct the Session adding the given $data into the Session.
     *
     * Note: To CREATE a re-useable Session object, you'll need to call >create as shown in the example.
     * @examle
     *        $session = Session::getInstance();
     *        $session->create($data)
     *
     * @param array $data
     */
    protected function __construct(array $data = [])
    {
        parent::__construct();
        $this->add($data);
    }

    /**
     * I will return the identifier of this session.
     * @return string
     */
    public static function getIdentifier(): string
    {
        return session_id();
    }
}
