<?php declare(strict_types = 1);
namespace noxkiwi\core;

use noxkiwi\core\Interfaces\FilesystemInterface;
use noxkiwi\log\Traits\LogTrait;
use noxkiwi\singleton\Singleton;
use function array_diff;
use function array_pop;
use function compact;
use function copy;
use function dirname;
use function explode;
use function file_exists;
use function file_get_contents;
use function file_put_contents;
use function is_dir;
use function is_file;
use function is_readable;
use function is_writable;
use function is_writeable;
use function mkdir;
use function rename;
use function rmdir;
use function scandir;
use function unlink;
use const SCANDIR_SORT_NONE;

/**
 * I am the Filesystem handler class.
 *
 * @package      noxkiwi\core
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2016 - 2018 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class Filesystem extends Singleton implements FilesystemInterface
{
    use LogTrait;

    public const TYPE_DIRECTORY = 'directory';
    public const TYPE_FILE      = 'file';
    /** @var \noxkiwi\core\Errorstack Contains an instance of the errorstack class */
    protected ErrorStack $errorstack;
    /** @var string I contain the error message. */
    protected string $errormessage;
    /** @var array I am a temporary storage for results on the Filesystem actions */
    private array $fileCache;

    /**
     * Creates the errorstack instance
     */
    protected function __construct()
    {
        parent::__construct();
        $this->fileCache  = [];
        $this->errorstack = ErrorStack::getErrorStack('FILESYSTEM');
    }

    /**
     * @inheritDoc
     */
    public function getError(): string
    {
        return $this->errormessage;
    }

    /**
     * @inheritDoc
     */
    public function fileMove(string $source, string $destination): bool
    {
        if (! $this->isMovable($source, $destination)) {
            return false;
        }
        if (! $this->dirAvailable(dirname($destination))) {
            $this->errorstack->addError('DESTINATION_PATH_NOT_FOUND', compact('source', 'destination'));

            return false;
        }
        if (! is_writable(dirname($destination))) {
            $this->errorstack->addError('DESTINATION_PATH_NOT_WRITABLE', compact('source', 'destination'));

            return false;
        }
        $this->logWarning('Moving file ' . $source . ' to ' . $destination);
        rename($source, $destination);

        return $this->fileAvailable($destination, true);
    }

    /**
     * I will prepare a copy/move action by validating the source and destination.
     *
     * @param string $source
     * @param string $destination
     *
     * @return bool
     */
    private function isMovable(string $source, string $destination): bool
    {
        if (! $this->fileAvailable($source)) {
            $this->errorstack->addError('SOURCE_NOT_FOUND', compact('source', 'destination'));

            return false;
        }
        if ($this->isDirectory($source)) {
            $this->errorstack->addError('SOURCE_IS_A_DIRECTORY', compact('source', 'destination'));

            return false;
        }
        if ($this->fileAvailable($destination)) {
            $this->errorstack->addError('DESTINATION_ALREADY_EXISTS', compact('source', 'destination'));

            return false;
        }
        if (! $this->makePath($destination)) {
            $this->errorstack->addError('DESTINATION_PATH_NOT_CREATED', compact('source', 'destination'));

            return false;
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function fileAvailable(string $file, bool $noCache = null): bool
    {
        $noCache ??= false;
        if (! $noCache && isset($this->fileCache[$file])) {
            return $this->fileCache[$file];
        }
        $this->fileCache[$file] = file_exists($file);

        return $this->fileCache[$file];
    }

    /**
     * @inheritDoc
     */
    public function isDirectory(string $path): bool
    {
        if (! $this->fileAvailable($path, true)) {
            return false;
        }

        return is_dir($path);
    }

    /**
     * I will create several directories until the complete path is available.
     *
     * @param string $directory
     *
     * @return       bool
     */
    protected function makePath(string $directory): bool
    {
        $folders = explode('/', $directory);
        array_pop($folders);
        $myDir = '/';
        foreach ($folders as $folder) {
            $myDir .= '/' . $folder;
            if ($this->dirAvailable($myDir)) {
                continue;
            }
            if (! $this->dirCreate($myDir)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function dirAvailable(string $directory): bool
    {
        if (! $this->fileAvailable($directory)) {
            return false;
        }

        return $this->isDirectory($directory);
    }

    /**
     * @inheritDoc
     */
    public function dirCreate(string $directory): bool
    {
        if ($this->fileAvailable($directory)) {
            return false;
        }
        if (! mkdir($directory) && ! is_dir($directory)) {
            return false;
        }
        $this->logWarning('creating directory ' . $directory);

        return $this->fileAvailable($directory, true);
    }

    /**
     * @inheritDoc
     */
    public function fileCopy(string $source, string $destination): bool
    {
        if (! $this->isMovable($source, $destination)) {
            return false;
        }
        $this->logWarning('copying file ' . $source . ' to ' . $destination);
        copy($source, $destination);

        return $this->fileAvailable($destination, true);
    }

    /**
     * @inheritDoc
     */
    public function fileRead(string $file): string
    {
        if (! $this->fileAvailable($file)) {
            return '';
        }
        if ($this->isDirectory($file)) {
            $this->errorstack->addError('DESTINATION_IS_A_DIRECTORY', compact('file'));

            return '';
        }
        if (! Environment::runs(Environment::DEVELOPMENT)) {
            $content = file_get_contents($file);

            return (string)$content;
        }

        return (string)file_get_contents($file);
    }

    /**
     * @inheritDoc
     */
    public function fileWrite(string $file, string $content = null): bool
    {
        if (! $this->makePath($file)) {
            $this->errorstack->addError('DESTINATION_PATH_NOT_CREATED', compact('file'));

            return false;
        }
        if ($this->fileAvailable($file)) {
            $this->errorstack->addError('FILE_ALREADY_EXISTS', compact('file'));

            return false;
        }
        if ($this->isWritable($file)) {
            $this->errorstack->addError('FILE_ALREADY_EXISTS', compact('file'));

            return false;
        }
        file_put_contents($file, $content);

        return $this->fileAvailable($file, true);
    }

    /**
     * @inheritDoc
     */
    public function isWritable(string $file): bool
    {
        return is_writable($file);
    }

    /**
     * @throws \noxkiwi\core\Exception
     * @inheritDoc
     */
    public function dirDelete(string $directory): bool
    {
        $files = array_diff($this->dirList($directory), ['.', '..']);
        foreach ($files as $file) {
            $path = $directory . '/' . $file;
            if ($this->isDirectory($path)) {
                $this->dirDelete($path);
            } else {
                $this->fileDelete($path);
            }
        }
        $this->logWarning('Removing directory ' . $directory);
        rmdir($directory);

        return ! $this->dirAvailable($directory);
    }

    /**
     * @inheritDoc
     */
    public function dirList(string $directory): array
    {
        if (! $this->isDirectory($directory)) {
            return [];
        }
        $list   = scandir($directory, SCANDIR_SORT_NONE);
        $myList = [];
        foreach ($list as $object) {
            if ($object === '.' || $object === '..') {
                continue;
            }
            if (! is_readable($directory . '/' . $object) || ! is_writeable($directory . '/' . $object)) {
                continue;
            }
            $myList[] = $object;
        }

        return $myList;
    }

    /**
     * @inheritDoc
     */
    public function fileDelete(string $file): bool
    {
        if (! $this->fileAvailable($file)) {
            return false;
        }
        if ($this->isDirectory($file)) {
            return false;
        }
        $this->logWarning('Deleting file ' . $file);
        unlink($file);

        return ! $this->fileAvailable($file, true);
    }

    /**
     * @inheritDoc
     */
    public function getInfo(string $file): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function isFile(string $file): bool
    {
        if (! $this->fileAvailable($file)) {
            return false;
        }

        return is_file($file);
    }
}
