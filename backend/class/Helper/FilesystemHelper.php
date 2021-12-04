<?php declare(strict_types = 1);
namespace noxkiwi\core\Helper;

use function count;
use function explode;
use function implode;
use function preg_replace;
use function str_replace;

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
abstract class FilesystemHelper
{
    /**
     * I will return the file name of the given file.
     *
     *
     * @param string $path
     *
     * @return string
     */
    public static function getFileName(string $path): string
    {
        $filenamedata = explode('/', $path);

        return $filenamedata[count($filenamedata) - 1];
    }

    /**
     * I will return the file name of the given file.
     *
     *
     * @param string $path
     *
     * @return string
     */
    public static function getFileExtension(string $path): string
    {
        $filenamedata = explode('.', $path);

        return $filenamedata[count($filenamedata) - 1];
    }

    /**
     * I will return the file name of the given file.
     *
     *
     * @param string $path
     *
     * @return string
     */
    public static function getDirectory(string $path): string
    {
        $filenamedata = explode('/', $path);
        if (count($filenamedata) === 1) {
            return '/';
        }
        unset($filenamedata[count($filenamedata) - 1]);

        return implode('/', $filenamedata);
    }

    /**
     * I will clean up the given $filename for further processing.
     *
     * @param string $filename
     *
     * @return string
     */
    final public static function cleanFilename(string $filename): string
    {
        $filename = preg_replace('/[∂άαáàâãªä]/u', 'a', $filename);
        $filename = preg_replace('/[∆лДΛдАÁÀÂÃÄ]/u', 'A', $filename);
        $filename = preg_replace('/[ЂЪЬБъь]/u', 'b', $filename);
        $filename = preg_replace('/[βвВ]/u', 'B', $filename);
        $filename = preg_replace('/[çς©с]/u', 'c', $filename);
        $filename = preg_replace('/[ÇС]/u', 'C', $filename);
        $filename = preg_replace('/[δ]/u', 'd', $filename);
        $filename = preg_replace('/[éèêëέεе℮ёєэЭ]/u', 'e', $filename);
        $filename = preg_replace('/[ÉÈÊË€ξЄЕ∑]/u', 'E', $filename);
        $filename = preg_replace('/[₣]/u', 'F', $filename);
        $filename = preg_replace('/[НнЊњ]/u', 'H', $filename);
        $filename = preg_replace('/[ђћЋ]/u', 'h', $filename);
        $filename = preg_replace('/[ÍÌÎÏ]/u', 'I', $filename);
        $filename = preg_replace('/[íìîïιίϊі]/u', 'i', $filename);
        $filename = preg_replace('/[Јј]/u', 'j', $filename);
        $filename = preg_replace('/[ΚЌК]/u', 'K', $filename);
        $filename = preg_replace('/[ќк]/u', 'k', $filename);
        $filename = preg_replace('/[ℓ∟]/u', 'l', $filename);
        $filename = preg_replace('/[Мм]/u', 'M', $filename);
        $filename = preg_replace('/[ñηήπⁿ]/u', 'n', $filename);
        $filename = preg_replace('/[Ñ∏пПИЙийΝЛ]/u', 'N', $filename);
        $filename = preg_replace('/[óòôõºöοФσόо]/u', 'o', $filename);
        $filename = preg_replace('/[ÓÒÔÕÖθΩОΩ]/u', 'O', $filename);
        $filename = preg_replace('/[ρφрРф]/u', 'p', $filename);
        $filename = preg_replace('/[®яЯ]/u', 'R', $filename);
        $filename = preg_replace('/[ГЃгѓ]/u', 'r', $filename);
        $filename = preg_replace('/[Ѕ]/u', 'S', $filename);
        $filename = preg_replace('/[ѕ]/u', 's', $filename);
        $filename = preg_replace('/[Тт]/u', 'T', $filename);
        $filename = preg_replace('/[τ†‡]/u', 't', $filename);
        $filename = preg_replace('/[úùûüџμΰµυϋύ]/u', 'u', $filename);
        $filename = preg_replace('/[√]/u', 'v', $filename);
        $filename = preg_replace('/[ÚÙÛÜЏЦц]/u', 'U', $filename);
        $filename = preg_replace('/[Ψψωώẅẃẁщш]/u', 'w', $filename);
        $filename = preg_replace('/[ẀẄẂШЩ]/u', 'W', $filename);
        $filename = preg_replace('/[ΧχЖХж]/u', 'x', $filename);
        $filename = preg_replace('/[ỲΫ¥]/u', 'Y', $filename);
        $filename = preg_replace('/[ỳγўЎУуч]/u', 'y', $filename);
        $filename = preg_replace('/[ζ]/u', 'Z', $filename);
        $filename = preg_replace('/[‚]/u', ',', $filename);
        $filename = preg_replace('/[`‛′’‘]/u', '\'', $filename);
        $filename = preg_replace('/[″“”«»„]/u', '\'', $filename);
        $filename = preg_replace('/[—–―−‾⌐─↔→←]/u', '-', $filename);
        $filename = preg_replace('/[ ]/u', ' ', $filename);
        $filename = (string)str_replace(['…', '≠', '≤', '≥'], ['...', '!=', '<=', '>='], $filename);
        $filename = preg_replace('/[‗≈≡]/u', '=', $filename);
        $filename = (string)str_replace(['ыЫ', '℅', '₧', '™', '№', 'Ч', '‰'], ['bl', 'c/o', 'Pts', 'tm', 'No', '4', '%'], $filename);
        $filename = preg_replace('/[∙•]/u', '*', $filename);
        $filename = (string)str_replace(['‹', '›', '‼', '⁄', '∕', '⅞', '⅝', '⅜', '⅛'], ['<', '>', '!!', '/', '/', '7/8', '5/8', '3/8', '1/8'], $filename);
        $filename = preg_replace('/[‰]/u', '%', $filename);
        $filename = preg_replace('/[Љљ]/u', 'Ab', $filename);
        $filename = preg_replace('/[Юю]/u', 'IO', $filename);
        $filename = preg_replace('/[ﬁﬂ]/u', 'fi', $filename);
        $filename = preg_replace('/[зЗ]/u', '3', $filename);
        $filename = (string)str_replace(['£', '₤'], ['(pounds)', '(lira)'], $filename);
        $filename = preg_replace('/[‰]/u', '%', $filename);
        $filename = preg_replace('/[↨↕↓↑│]/u', '|', $filename);
        $filename = preg_replace('/[∞∩∫⌂⌠⌡]/u', '', $filename);

        return (string)str_replace([',', '_', '/', '\\/', ' '], '', $filename);
    }
}
