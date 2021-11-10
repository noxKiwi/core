<?php declare(strict_types = 1);
namespace noxkiwi\core\Response;

use noxkiwi\core\Response;

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
final class CallbackResponse extends Response
{
    /** @var string[] */
    protected array $jsActions;

    /**
     * @inheritDoc
     */
    protected function __construct()
    {
        parent::__construct();
        $this->jsActions = [];
    }

    /**
     * I will add a notification to the Response
     *
     * @param string $subject
     * @param string $text
     * @param string $image
     * @param string $sound
     */
    public function addNotification(string $subject, string $text, string $image, string $sound): void
    {
        $this->addJs("rsNotify('$subject', '$text', '$image', '$sound'");
    }

    /**
     * I will add a JS body to the Response
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
