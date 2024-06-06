<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';

use App\classes\hiveGame;

$hiveGame = new HiveGame();
$hiveGame->undo();
