<?php declare(strict_types = 1);
namespace noxkiwi\core\Context;

use JetBrains\PhpStorm\NoReturn;
use noxkiwi\core\Context;
use noxkiwi\core\Filesystem;
use noxkiwi\core\Helper\MimeHelper;
use noxkiwi\core\Helper\WebHelper;
use noxkiwi\core\Path;
use function explode;

/**
 * I am the base Resource context class.
 *
 * @package      noxkiwi\core
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2016 - 2018 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
abstract class ResourceContext extends Context
{
    /**
     * I will output the content of the requested file, and then I will exit.
     * @throws \noxkiwi\singleton\Exception\SingletonException
     */
    #[NoReturn] protected function viewFile(): void
    {
        [$type, $file] = explode(
            '/',
            $this->request->get('file', '')
        );
        [$folder, $extension] = MimeHelper::getResourceFromType($type);
        MimeHelper::sendHeaders($type);
        echo Filesystem::getInstance()->fileRead(Path::getInheritedPath("resource/$folder/$file.$extension"));
        exit(WebHelper::HTTP_OKAY);
    }
}
