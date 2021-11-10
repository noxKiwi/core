<?php declare(strict_types = 1);
namespace noxkiwi\core\Interfaces;

/**
 * I am the DataContainer's interface.
 * DataContainer's are extendable objects that have been developed for easier access on deep structured data.
 *
 * Have a look at the examples to easily see what difference this object should make.
 *
 * @package      noxkiwi\core
 * @example      DataContainer Approach
 *         $container = new DataContainer(json_decode($curlResponse, true));
 *         return $container->get('A>B>C>UserName', 'John.Doe');
 *
 * @examle       Classical approach using arrays:
 * $data = json_decode($curlResponse, true);
 * if(empty ($data['A'])) {
 *     return $default;
 * }
 * if(empty ($data['A']['B'])) {
 *     return $default;
 * }
 * if(empty ($data['A']['B']['C'])) {
 *     return $default;
 * }
 * if(empty ($data['A']['B']['C']['UserName'])) {
 *     return $default;
 * }
 * return $data['A']['B']['C']['UserName'];
 *
 *
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2016 - 2021 noxkiwi
 * @version      1.1.2
 * @link         https://nox.kiwi/
 */
interface DatacontainerInterface
{
    /**
     * Adds the given KEY => VALUE array to the current set of data
     *
     * @param array $data
     */
    public function add(array $data): void;

    /**
     * I will return the value of the given key. Either pass a direct name, or use a tree>to>navigate through the data set
     * <br />->get('my>config>key')
     *
     * @param string|null $key
     * @param mixed|null  $default
     *
     * @return mixed
     */
    public function get(string $key = null, mixed $default = null): mixed;

    /**
     * Stores the given $data value under the given $key in this instance's data property.
     *
     * @param string $key
     * @param mixed  $data
     */
    public function set(string $key, mixed $data): void;

    /**
     * Removes the given $key from this instance's data set.
     *
     * @param string $key
     */
    public function remove(string $key): void;

    /**
     * Returns true if there is a value with name $key in this instance's data set.
     *
     * @param string $key
     *
     * @return       bool
     */
    public function exists(string $key): bool;

    /**
     * I will simply override the entire data property with the given $data array.
     *
     * @param array $data
     */
    public function put(array $data): void;
}
