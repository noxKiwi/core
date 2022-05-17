<?php declare(strict_types = 1);

namespace noxkiwi\core;

use DateTime;
use noxkiwi\core\Gate\MaintenanceGate;
use function file_exists;
use function file_get_contents;
use function is_readable;

//
$begin  = 'some time ago';
$reason = 'reasons';

$path = MaintenanceGate::getPath();
if (file_exists($path) && is_readable($path)) {
    $dateTime = new DateTime();
    $dateTime->setTimestamp(filemtime($path));
    $begin  = $dateTime->format('c');
    $reason = file_get_contents($path);
}

?><!DOCTYPE html>
<html lang="en">
<head>
    <title>Maintenance Mode</title>

    <!-- JQ -->
    <script type="text/javascript" src="/asset/lib/jquery/jquery.min.js"></script>

    <!-- BOOTSTRAP -->
    <link rel="stylesheet" type="text/css" media="(prefers-color-scheme: dark)" href="/asset/lib/bootstrap/css/bootstrap-night.css">
    <link rel="stylesheet" type="text/css" media="(prefers-color-scheme: no-preference), (prefers-color-scheme: light)" href="/asset/lib/bootstrap/css/bootstrap.min.css"/>
    <script type="text/javascript" src="/asset/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<div class="text-center">
    <h1 class="fa fa-cogs size-80 theme-color hidden-xs"></h1>
    <h2><strong>💀</strong></h2>
    <p>This application went into <strong>Maintenance Mode</strong> at <?= $begin ?> because of <i><?= $reason ?></i></p>
    <img src="/asset/lib/maintenance/death.jpg" loop="false" width="33%">
    <br/>
    <small><a href="https://www.vikrammadan.com/">&quot;<i>Kicking The Bucket</i>&quot; by Vikram Madan</a></small>
</div>
</body>
</html>
