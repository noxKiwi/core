<?php declare(strict_types = 1);
namespace noxkiwi\core\Response;

use noxkiwi\core\Exception\InvalidArgumentException;
use noxkiwi\core\Filesystem;
use noxkiwi\core\Path;
use noxkiwi\core\Response;
use function in_array;
use const E_NOTICE;

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
class HttpResponse extends Response
{
    public const HEADER_ERROR = 'HTTP/1.1 500 Internal Server Error';
    public const HEADER_OK    = 'HTTP/1.1 200 OK';

    /**
     * I store the requirement of additional frontend resource in the Response container
     *
     * @param string $type
     * @param string $path
     *
     * @throws \noxkiwi\core\Exception\InvalidArgumentException
     * @throws \noxkiwi\singleton\Exception\SingletonException
     * @return       bool
     */
    public function requireResource(string $type, string $path): bool
    {
        if (! in_array($type, ['js', 'css'])) {
            throw new InvalidArgumentException('EXCEPTION_REQUIRERESOURCE_INVALIDRESOURCETYPE', E_NOTICE, $type);
        }
        if (strpos('://', $path) && ! Filesystem::getInstance()->fileAvailable(Path::$webRoot . $path)) {
            throw new InvalidArgumentException('EXCEPTION_REQUIRERESOURCE_RESOURCENOTFOUND', E_NOTICE, $path);
        }
        $this->resources[$type][] = $path;

        return true;
    }

    /**
     * Returns an array of resource that have been requested by the backend
     *
     * @param string $type
     *
     * @return       array
     */
    public function getResources(string $type): array
    {
        return $this->resources[$type] ?? [];
    }

    /**
     * Will show a desktop notification on the browser if the Client allowed it.
     *
     * @see          ./www/public/library/templates/shared/javascript/alpha_engine.js :: doCallback($url, callback());
     *
     * @param string $subject
     * @param string $text
     * @param string $image
     * @param string $sound
     *
     * @throws \noxkiwi\singleton\Exception\SingletonException
     */
    public function addNotification(string $subject, string $text, string $image, string $sound): void
    {
        $file = Path::$webRoot . $image;
        if (! Filesystem::getInstance()->fileAvailable($file)) {
            $this->logDebug("Cannot send notification, the image $file is not available!");

            return;
        }
        $file = Path::$webRoot . $sound;
        if (! Filesystem::getInstance()->fileAvailable($file)) {
            $this->logDebug("Cannot send notification, the sound $file is not available!");

            return;
        }
        $this->addJs("rsNotify('$subject', '$text', '$image', '$sound');");
    }

    /**
     * Add a JS resource to the Response template
     *
     * @param string $js
     */
    public function addJs(string $js): void
    {
        $jsdo = $this->get('jsdo');
        if ($jsdo === null) {
            $jsdo = [];
        }
        $jsdo[] = $js;
        $this->set('jsdo', $jsdo);
    }
}
