<?php declare(strict_types = 1);
namespace noxkiwi\core\Gate;

use noxkiwi\core\Filesystem;
use noxkiwi\core\Gate;
use noxkiwi\core\Path;
use noxkiwi\core\Request;
use function defined;

/**
 * I am the interface for the maintenance mode.
 *
 * @package      noxkiwi\core
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2018 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class MaintenanceGate extends Gate
{
    public const MAINTENANCE_FILE     = '.maintenance';
    public const MAINTENANCE_TEMPLATE = Path::PAGE_MAINTENANCE;

    /**
     * @inheritDoc
     * @throws \noxkiwi\singleton\Exception\SingletonException
     */
    public function isOpen(): bool
    {
        return ! Filesystem::getInstance()->fileAvailable(self::getPath());
    }

    /**
     * @inheritDoc
     * @throws \noxkiwi\singleton\Exception\SingletonException
     */
    public function open(): void
    {
        parent::open();
        Filesystem::getInstance()->fileDelete(self::MAINTENANCE_FILE);
    }

    /**
     * @inheritDoc
     * @throws \noxkiwi\singleton\Exception\SingletonException
     */
    public function close(?string $reason = null): void
    {
        parent::close($reason);
        Filesystem::getInstance()->fileWrite(self::getPath(), $reason);
    }

    /**
     * I will solely return the path of the maintenance gate's trigger file.
     *
     * This is, if defined, located in the HOME folder or in the document root
     * of the currently running app.
     *
     * @return string
     */
    public static function getPath(): string
    {
        if (defined('HOME') && ! empty(HOME)) {
            return HOME . self::MAINTENANCE_FILE;
        }
        $documentRoot = Request::getInstance()->get('DOCUMENT_ROOT');
        if (! empty($documentRoot)) {
            return $documentRoot . '/' . self::MAINTENANCE_FILE;
        }

        return self::MAINTENANCE_FILE;
    }
}
