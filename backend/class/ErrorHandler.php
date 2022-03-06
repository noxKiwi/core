<?php declare(strict_types = 1);
namespace noxkiwi\core;

use noxkiwi\core\Exception\PhpException;
use noxkiwi\core\Gate\MaintenanceGate;
use noxkiwi\core\Helper\FrontendHelper;
use noxkiwi\core\Helper\WebHelper;
use noxkiwi\core\Response\CliResponse;
use noxkiwi\core\Response\HttpResponse;
use function basename;
use function chr;
use function count;
use function defined;
use function error_reporting;
use function explode;
use function file_get_contents;
use function file_put_contents;
use function header;
use function headers_sent;
use function htmlspecialchars;
use function max;
use function min;
use function print_r;
use function uniqid;
use function var_dump;
use const E_ERROR;

/**
 * I am the ErrorHandler for all errors in the core.
 *
 * @package      noxkiwi\core
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2016 - 2021 noxkiwi
 * @version      1.0.1
 * @link         https://nox.kiwi/
 */
abstract class ErrorHandler
{
    private const SURROUND_LINES = 10;

    /**
     * I will handle the given $error as info element on a new Exception that will be handled right after.
     *
     * @param \Error $error
     * @param int    $errorLevel
     */
    final public static function handleError(\Error $error, int $errorLevel = E_ERROR): void
    {
        try {
            throw new PhpException('ERROR_OCCURED', $errorLevel, $error->getMessage());
        } catch (Exception $exception) {
            static::handleException($exception, $errorLevel);
        }
    }

    /**
     * I will handle the given $exception and perform some actions with it.
     * In every case, the output of the handled Exception will be generated as a file.
     * In case the $errorLevel is E_ERROR and you are running on PRODUCTION environment,
     * the Maintenance gate will close with the Exception's reason. So be aware that
     * you WILL have to handle all exceptions that are not CRITICAL or DESTRUCTIVE for
     * your product as expected or give them a lower error level.
     *
     * @param \Exception $exception
     * @param int        $errorLevel
     */
    public static function handleException(\Exception $exception, int $errorLevel = E_ERROR): void
    {
        if (error_reporting() === 0) {
            return;
        }
        try {
            static::output($exception);
            if (! $exception instanceof Exception) {
                return;
            }
            if ($errorLevel === E_ERROR && Environment::runs(Environment::PRODUCTION)) {
                MaintenanceGate::getInstance()->close($exception->getMessage());
            }
        } catch (\Exception $loggerException) {
            var_dump($exception);
            var_dump($loggerException);
        }
        die(WebHelper::HTTP_SERVER_ERROR);
    }

    /**
     * I will output the given $exception data if the environment is correct.
     * In any case, I will create a log of the handled Exception
     *
     * @see \noxkiwi\core\Path::LOG_DIR
     *
     * @param \Exception $exception
     */
    private static function output(\Exception $exception): void
    {
        try {
            if (error_reporting() === 0) {
                return;
            }
            $file      = basename($exception->getFile());
            $file      = Path::LOG_DIR . "exception_{$file}_{$exception->getLine()}.html";
            $errorPage = FrontendHelper::parseFile(Path::getInheritedPath('frontend/page/errorinfo.php'), $exception);
            file_put_contents($file, $errorPage);
            if (defined('NK_ERROR_OUTPUT') && NK_ERROR_OUTPUT !== true) {
                return;
            }
            if (! headers_sent()) {
                header(HttpResponse::HEADER_ERROR);
            }
            if (Environment::runs(Environment::PRODUCTION)) {
                echo FrontendHelper::parseFile(Path::getInheritedPath('frontend/page/error.php'));
                exit(CliResponse::EXITCODE_ERROR);
            }
            echo $errorPage;
            exit(CliResponse::EXITCODE_ERROR);
        } catch (\Exception $error) {
            var_dump($error);
            die();
        }
    }

    /**
     * I will build the output of the given $exception's trace.
     * This includes code, so if the code is "secret" for any reason,
     * disable the stacktrace output through your Environment.
     *
     * @param \Exception $exception
     *
     * @return string
     */
    final public static function getStack(\Exception $exception): string
    {
        $stackTrace = '';
        foreach ($exception->getTrace() as $trace) {
            $surround       = static::getSurround($trace['file'] ?? '', $trace['line'] ?? 0);
            $uniqid         = uniqid('stackTrace', false);
            $arguments      = print_r($trace['args'] ?? null, true);
            $trace['class'] ??= 'none';
            $stackTrace     .= <<<HTML
<div class="accordion-item">
    <h2 class="accordion-header" id="heading$uniqid">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse$uniqid" aria-expanded="false" aria-controls="collapse$uniqid">
            {$trace['file']}::{$trace['line']}
        </button>
    </h2>
    <div id="collapse$uniqid" class="accordion-collapse collapse" aria-labelledby="heading$uniqid" data-bs-parent="#accordionExample">
        <div class="accordion-body">
            <table>
                <tbody>
                    <tr>
                        <td><strong>File</strong></td>
                        <td>{$trace['file']}</td>
                    </tr>
                    <tr>
                        <td><strong>Line</strong></td>
                        <td>{$trace['line']}</td>
                    </tr>
                    <tr>
                        <td><strong>Class</strong></td>
                        <td>{$trace['class']}</td>
                    </tr>
                    <tr>
                        <td><strong>Function</strong></td>
                        <td>{$trace['function']}</td>
                    </tr>
                    <tr>
                        <td><strong>Args</strong></td>
                        <td><pre>$arguments</pre></td>
                    </tr>
                    <tr>
                        <td valign="top"><strong>Surrounding</strong></td>
                        <td><pre style="overflow-x:auto;width:100%">$surround</pre></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
HTML;
        }

        return $stackTrace;
    }

    /**
     * I will solely return a fixed amount of lines from the given $file.
     * I will take care that [n] lines are shown above and below the given $errorLine.
     *
     * @see \noxkiwi\core\ErrorHandler::SURROUND_LINES
     *
     * @param string $file
     * @param int    $errorLine
     *
     * @return string
     */
    final public static function getSurround(string $file, int $errorLine): string
    {
        if (empty($file) || empty($errorLine)) {
            return '';
        }
        $ret         = '';
        $sourceLines = explode(chr(10), file_get_contents($file));
        $startLine   = max(0, $errorLine - static::SURROUND_LINES);
        $endLine     = min(count($sourceLines), $errorLine + static::SURROUND_LINES);
        for ($lineNumber = $startLine; $lineNumber <= $endLine; $lineNumber++) {
            $sLine = $lineNumber + 1;
            $a     = '⚫   ';
            if ($sLine === $errorLine) {
                $a = '🔴   ';
            }
            $ret .= $sLine . $a . ($sourceLines[$lineNumber] ?? '') . chr(10);
        }

        return htmlspecialchars($ret);
    }
}
