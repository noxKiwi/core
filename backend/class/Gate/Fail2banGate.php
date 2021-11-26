<?php declare(strict_types = 1);
namespace noxkiwi\core\Gate;

use noxkiwi\core\Gate;
use noxkiwi\core\Helper\WebHelper;
use function chr;
use function file_put_contents;
use function is_writable;
use const FILE_APPEND;

/**
 * I am the interface for fail2Ban.
 *
 * @package      noxkiwi\core
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2018 noxkiwi
 * @version      1.0.0
 * @link         https://nox.kiwi/
 */
final class Fail2banGate extends Gate
{
    /** @var string I am the filename to the fail2ban jail. */
    private string $file;

    /**
     * I will construct the Fail2banGate.
     *
     * @param array $options
     */
    protected function __construct(array $options)
    {
        parent::__construct();
        $this->setFile((string)($options['file'] ?? ''));
    }

    /**
     * I will solely set the $file of the jail file.
     *
     * @param string $file
     */
    private function setFile(string $file): void
    {
        $this->file = $file;
    }

    /**
     * I will solely return the path to the jail file.
     *
     * @return string
     */
    private function getFile(): string
    {
        return $this->file;
    }

    /**
     * @inheritDoc
     */
    public function isOpen(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function close(?string $reason = null): void
    {
        parent::close($reason);
        if (! is_writable($this->getFile())) {
            return;
        }
        file_put_contents($this->getFile(), chr(10) . WebHelper::getClientIp(), FILE_APPEND);
    }
}
