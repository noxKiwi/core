<?php declare(strict_types = 1);
namespace noxkiwi\core;

/**
 * I am the Fileinfo class
 *
 * @package      noxkiwi\core
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2018 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
abstract class File
{
    /** @var string I am the name of the file this instance represents. */
    public string $name;
    /** @var string|null I am the file type of the file this instance represents. */
    public ?string $type;
    /** @var string|null I am the permission string of this current file. */
    public ?string $permissions;
    /** @var int|null I am the number of the file in the directory listing. */
    public ?int $number;
    /** @var string|null I am the username of the owner. */
    public ?string $user;
    /** @var string|null I am the group name of the owner. */
    public ?string $group;
    /** @var int|null I am the file size in bytes. */
    public ?int $size;
    /** @var string|null I am the last modification date of the file. */
    public ?string $lastChange;
    /** @var string|null I am the type (file|directory) of the current file. */
    public ?string $extension;

    /**
     * File constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->permissions = $data['permissions'] ?? null;
        $this->number      = $data['number'] ?? null;
        $this->user        = $data['user'] ?? null;
        $this->group       = $data['group'] ?? null;
        $this->size        = $data['size'] ?? null;
        $this->lastChange  = $data['lastchange'] ?? null;
        $this->type        = $data['type'] ?? null;
    }
}
