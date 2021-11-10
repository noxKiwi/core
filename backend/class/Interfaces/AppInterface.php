<?php declare(strict_types = 1);
namespace noxkiwi\core\Interfaces;

/**
 * I am the interface for all Applicaitons.
 *
 * @package      noxkiwi\core\Interfaces
 * @author       Jan Nox <jan.nox@pm.me>
 * @license      https://nox.kiwi/license
 * @copyright    2016 - 2021 noxkiwi
 * @version      1.0.1
 * @link         https://nox.kiwi/
 */
interface AppInterface
{
    /**
     * I will run the application.
     * This consists of preparing Request and Response.
     * The Request determines the Context to run.
     * The Context will then decide the different methods to execute:
     *  - Action
     *      protected function actionDeleteEntry() : void {}
     *  - View
     *      protected function viewList() : void {}
     */
    public function run(): void;
}
