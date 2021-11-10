<?php declare(strict_types = 1);
namespace noxkiwi\core;

/** @var \Exception $data */
$data  ??= [];
$isCli = Request::isCli();
$level = E_ERROR;
if ($data instanceof Exception) {
    $level = $data->getLevel();
}
switch ($level) {
    case E_WARNING:
        $class   = 'warning';
        $errName = '❌';
        break;
    case E_NOTICE:
        $class   = 'info';
        $errName = '⚠️';
        break;
    default:
        $class   = 'danger';
        $errName = '💀';
        break;
}

use JetBrains\PhpStorm\Pure;
use noxkiwi\cache\Cache;
use noxkiwi\cache\Observer\CacheObserver;
use noxkiwi\core\Helper\WebHelper;
use noxkiwi\database\Database;
use noxkiwi\log\Log\CliLog;
use function print_r;
use const E_ERROR;
use const E_NOTICE;
use const E_WARNING;

try {
    $requestText  = print_r(
        Request::getInstance()->get(),
        true
    );
    $responseText = print_r(
        Response::getInstance()->get(),
        true
    );
    $environment  = Environment::getInstance();
    $appText      = print_r(
        Application::getInstance()->get(),
        true
    );
    $cHit         = CacheObserver::$countHit;
    $cSet         = CacheObserver::$countSet;
    $cGet         = CacheObserver::$countGet;
    $cMiss        = CacheObserver::$countMiss;
    if ($isCli) {
        $a = CliLog::getInstance();
        $a->alert($data::class);
        $a->alert($data->getMessage());
        $a->error(print_r($data, true));
        exit(WebHelper::HTTP_SERVER_ERROR);
    }
    $rt = '';
    if ($data instanceof Exception) {
        $rt = print_r($data->getInfo(), true);
    }
    $errText   = print_r(ErrorStack::getErrorStacks(), true);
    $className = $data::class;
    $ipaddress = WebHelper::getClientIp();
    $admins    = (array)$environment->get('errorhandler>admins', []);
    /**
     * I will create a tab on the error page.
     *
     * @param string $id
     * @param string $content
     *
     * @return \noxkiwi\core\Tab
     */
    #[Pure] function makeTab(string $id, string $content): Tab
    {
        $tab          = new Tab();
        $tab->domId   = $id;
        $tab->content = $content;
        $tab->title   = $id;

        return $tab;
    }

    /**
     * Class Tab
     *
     * @package   noxkiwi\core
     *
     * @author    Jan Nox <jan.nox@pm.me>
     *
     * @copyright 2021 nox.kiwi
     * @version   1.0.0
     */
    class Tab
    {
        public string $domId;
        public string $title;
        public string $content;
    }

    /**
     * Class TabArea
     *
     * @package   noxkiwi\core
     *
     * @author    Jan Nox <jan.nox@pm.me>
     *
     * @copyright 2021 nox.kiwi
     * @version   1.0.0
     */
    class TabArea
    {
        /** @var \noxkiwi\core\Tab[] */
        public array $tabs = [];

        /**
         * I will add a new Tab to the TabArea.
         *
         * @param \noxkiwi\core\Tab $tab
         */
        public function addTab(Tab $tab): void
        {
            $this->tabs[] = $tab;
        }

        /**
         * I will output the tabs.
         *
         * @return string
         */
        public function output(): string
        {
            $tabHeader  = '';
            $tabContent = '';
            $active     = 'show active';
            foreach ($this->tabs ?? [] as $tab) {
                $tabHeader  .= <<<HTML
<li class="nav-item" role="presentation">
    <button class="nav-link $active" id="home-tab" data-bs-toggle="tab" data-bs-target="#$tab->domId" type="button" role="tab" aria-controls="$tab->domId" aria-selected="true">$tab->title</button>
</li>
HTML;
                $tabContent .= <<<HTML
<div class="tab-pane fade $active" id="$tab->domId" role="tabpanel" aria-labelledby="$tab->domId-tab">
    $tab->content
</div>
HTML;
                $active     = '';
            }

            return <<<HTML
<div class="container">
    <ul class="nav nav-tabs" id="myTab" role="tablist">
      $tabHeader
    </ul>
    <div class="tab-content" id="myTabContent">
      $tabContent
    </div>
</div>
HTML;
        }
    }

    $tabArea = new TabArea();
    if (in_array($ipaddress, $admins, true) || $environment->get('errorhandler>stacktrace>show', false) === true) {
        $stackTrace = ErrorHandler::getStack($data);
        $id         = 'main';
        $fc         = ErrorHandler::getSurround($data->getFile(), $data->getLine());
        $tabContent = <<<HTML
<div class="accordion" id="accordionExample">
    <h2 class="accordion-header" id="head$id">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#coll$id" aria-expanded="false" aria-controls="collapseOne">
            {$data->getFile()}::{$data->getLine()}
        </button>
    </h2>
    <div id="coll$id" class="accordion-collapse collapse" aria-labelledby="head$id" data-bs-parent="#accordionExample">
        <div class="accordion-body">
            <table>
                <tbody>
                    <tr>
                        <td><strong>File</strong></td>
                        <td>{$data->getFile()}</td>
                    </tr>
                    <tr>
                        <td><strong>Line</strong></td>
                        <td>{$data->getLine()}</td>
                    </tr>
                    <tr>
                        <td><strong>Surrounding</strong></td>
                        <td><pre>$fc</pre></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    $stackTrace
</div>
HTML;
        $tabArea->addTab(makeTab('Stacktrace', $tabContent));
    }
    if (in_array($ipaddress, $admins, true) || $environment->get('errorhandler>request>show', false) === true) {
        $tabContent = <<<HTML
<pre>$requestText</pre>
HTML;
        $tabArea->addTab(makeTab('Request', $tabContent));
    }
    if (in_array($ipaddress, $admins, true) || $environment->get('errorhandler>response>show', false) === true) {
        $tabContent = <<<HTML
<pre>$responseText</pre>
HTML;
        $tabArea->addTab(makeTab('Response', $tabContent));
    }
    if (in_array($ipaddress, $admins, true) || $environment->get('errorhandler>app>show', false) === true) {
        $tabContent = <<<HTML
<pre>$appText</pre>
HTML;
        $tabArea->addTab(makeTab('Application', $tabContent));
    }
    if (in_array($ipaddress, $admins, true) || $environment->get('errorhandler>cache>show', false) === true) {
        $cacheInfo        = print_r(
            Cache::getInstance()->getAllKeys(),
            true
        );
        $cacText          = <<<HTML
    <table>
        <tbody>
            <tr>
                <td><strong>Hit</strong></td>
                <td>$cHit</td>
            </tr>
            <tr>
                <td><strong>Set</strong></td>
                <td>$cSet</td>
            </tr>
            <tr>
                <td><strong>Get</strong></td>
                <td>$cGet</td>
            </tr>
            <tr>
                <td><strong>Miss</strong></td>
                <td>$cMiss</td>
            </tr>
        </tbody>
    </table>
    <pre>$cacheInfo</pre>
    HTML;
        $tabContent       = <<<HTML
<pre>$cacText</pre>
HTML;
        $tabArea->tabs [] = makeTab('Cache', $tabContent);
    }
    if (in_array($ipaddress, $admins, true) || $environment->get('errorhandler>errorstack>show', false) === true) {
        $tabContent       = <<<HTML
<pre>$errText</pre>
HTML;
        $tabArea->tabs [] = makeTab('Errors', $tabContent);
    }
    if (in_array($ipaddress, $admins, true) || $environment->get('errorhandler>queries>show', false) === true) {
        $qryText          = print_r(Database::$queries, true);
        $tabContent       = <<<HTML
<pre>$qryText</pre>
HTML;
        $tabArea->tabs [] = makeTab('Queries', $tabContent);
    }
    if (in_array($ipaddress, $admins, true) || $environment->get('errorhandler>environment>show', false) === true) {
        $envText    = print_r($environment->get(), true);
        $tabContent = <<<HTML
<pre>$envText</pre>
HTML;
        $tabArea->addTab(makeTab('Environment', $tabContent));
    }
    $tab     = $tabArea->output();
    $content = <<<HTML
<div class="container">
    <div class="alert alert-$class" role="alert">
        <h3><b>$errName $className</b></h3>
        <h4><i>{$data->getMessage()}</i></h4>
        <pre>$rt</pre>
    </div>
</div>
$tab
HTML;
    ?>
    <html>
    <head>
        <!-- JQ -->
        <script type="text/javascript" src="/asset/lib/jquery/jquery.min.js"></script>

        <!-- BOOTSTRAP -->
        <link rel="stylesheet" type="text/css" media="(prefers-color-scheme: dark)" href="/asset/lib/bootstrap/css/bootstrap-night.css">
        <link rel="stylesheet" type="text/css" media="(prefers-color-scheme: no-preference), (prefers-color-scheme: light)" href="/asset/lib/bootstrap/css/bootstrap.min.css"/>
        <script type="text/javascript" src="/asset/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
    </head>
    <body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <a class="navbar-brand" href="/">nox.kiwi ErrorHandler</a>
    </nav>
    <section>
        <?= $content ?>
    </section>
    <style>
        td {font-family : "Courier New", monospace;}


    </style>


    </body>
    </html>

    <?php
} catch (\Exception) {
}
