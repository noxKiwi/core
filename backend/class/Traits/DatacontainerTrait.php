<?php declare(strict_types = 1);
namespace noxkiwi\core\Traits;

use function explode;
use function in_array;
use function is_array;
use function is_object;
use function str_contains;

/**
 * I am the trait for the implementation if the DataContainerInterface.
 * @package      \noxkiwi\core\Traits
 * @see          \noxkiwi\core\Interfaces\DatacontainerInterface
 *
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2016 - 2021 noxkiwi
 * @version      1.1.0
 * @link         https://nox.kiwi/
 */
trait DatacontainerTrait
{
    /** @var array Contains the data of this instance */
    private array $data = [];

    /**
     * Creates the instance and imports the $data object into this instance
     *
     * @param array|null $data
     */
    public function __construct(array $data = null)
    {
        $this->add($data ?? []);
    }

    /**
     * @inheritDoc
     */
    final public function add(array $data): void
    {
        foreach ($data as $key => $value) {
            if (is_object($key) || is_array($key)) {
                continue;
            }
            $this->set((string)$key, $value);
        }
    }

    /**
     * @inheritDoc
     */
    final public function exists(string $key): bool
    {
        return $this->get($key) !== null;
    }

    /**
     * @inheritDoc
     */
    final public function remove(string $key): void
    {
        if ($key === '') {
            return;
        }
        if (! $this->exists($key)) {
            return;
        }
        $this->set($key, null);
    }

    /**
     * @inheritDoc
     */
    final public function get(string $key = null, $default = null): mixed
    {
        if (in_array($key, [null, ''], true)) {
            return $this->data;
        }
        if (! str_contains($key, '>')) {
            return $this->data[$key] ?? $default;
        }
        $myValue  = $this->data;
        $keyArray = explode('>', $key);
        foreach ($keyArray as $myKey) {
            if (! isset($myValue[$myKey])) {
                return $default;
            }
            $myValue = $myValue[$myKey];
        }

        return $myValue;
    }

    /**
     * @inheritDoc
     */
    final public function set(string $key, mixed $data): void
    {
        if ($key === '') {
            return;
        }
        if (str_contains($key, '>')) {
            $this->data = $this->setArray($key, $data);

            return;
        }
        $this->data[$key] = $data;
    }

    /**
     * I will write down the given $value under the NESTED node identified with the given $key
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return array
     * @link   http://stackoverflow.com/questions/13359681/how-to-set-a-deep-array-in-php
     *
     */
    final protected function setArray(string $key, mixed $value): array
    {
        $current     = &$this->data;
        $keyElements = explode('>', $key);
        foreach ($keyElements as $keyElement) {
            $current = &$current[$keyElement];
        }
        $current = $value;

        return $this->data;
    }

    /**
     * @inheritDoc
     */
    final public function put(array $data): void
    {
        $this->data = $data;
    }
}
