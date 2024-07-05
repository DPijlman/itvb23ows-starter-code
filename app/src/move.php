<?php
require_once __DIR__ . '/vendor/autoload.php';

use HiveGame\MoveHandler\MoveHandler;

MoveHandler::move($_POST['from'], $_POST['to']);
?>
