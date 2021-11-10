<?php declare(strict_types = 1);
namespace noxkiwi\core;

use noxkiwi\core\Helper\JsonHelper;

header('Content-Type: application/json');
echo JsonHelper::encode(
    Response::getInstance()->get()
);
