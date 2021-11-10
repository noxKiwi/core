<?php declare(strict_types = 1);
namespace noxkiwi\core\Interfaces;

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
interface FilesystemInterface
{
    /**
     * Returns true if $file exists.
     *
     * @param string $file
     * @param bool   $noCache
     *
     * @return       bool
     */
    public function fileAvailable(string $file, bool $noCache = null): bool;

    /**
     * Returns true if $file could be deleted.
     *
     * @param string $file
     *
     * @return       bool
     */
    public function fileDelete(string $file): bool;

    /**
     * Returns true if $source could be moved to $destination.
     *
     * @param string $source
     * @param string $destination
     *
     * @return       bool
     */
    public function fileMove(string $source, string $destination): bool;

    /**
     * Returns true if $file could be copied to $destination
     *
     * @param string $source
     * @param string $destination
     *
     * @return       bool
     */
    public function fileCopy(string $source, string $destination): bool;

    /**
     * Returns the content of $file
     *
     * @param string $file
     *
     * @return       string
     */
    public function fileRead(string $file): string;

    /**
     * Returns true if $countent could be written to $file
     *
     * @param string      $file
     * @param string|null $content
     *
     * @return       bool
     */
    public function fileWrite(string $file, string $content = null): bool;

    /**
     * I will return true if the given $file is writable.
     *
     * @param string $file
     *
     * @return bool
     */
    public function isWritable(string $file): bool;

    /**
     * Returns true if $directory exists
     *
     * @param string $directory
     *
     * @return       bool
     */
    public function dirAvailable(string $directory): bool;

    /**
     * Returns true if $directory could be created
     *
     * @param string $directory
     *
     * @return       bool
     */
    public function dirCreate(string $directory): bool;

    /**
     * I will delete the given $directory
     *
     * @param string $directory
     *
     * @return       bool
     */
    public function dirDelete(string $directory): bool;

    /**
     * Returns the list of objects in $directory. Returns an empty array if $directory does not exist
     *
     * @param string $directory
     *
     * @return       array
     * @access       public
     */
    public function dirList(string $directory): array;

    /**
     * Returns true if $path is a directory
     *
     * @param string $path
     *
     * @return       bool
     * @access       public
     */
    public function isDirectory(string $path): bool;

    /**
     * Returns an array of info about $file
     *
     * @param string $file
     *
     * @return       array
     * @access       public
     */
    public function getInfo(string $file): array;

    /**
     * Returns true if the given $file actually IS a file
     *
     * @param string $file
     *
     * @return       bool
     */
    public function isFile(string $file): bool;

    /**
     * Returns the error message
     *
     * @return       string
     */
    public function getError(): string;
}
