<?php
require_once __DIR__ . '/vendor/autoload.php';

use HiveGame\MoveHandler\RestartHandler;

RestartHandler::restartGame();

header('Location: index.php');
exit();
