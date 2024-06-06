<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';

use App\classes\HiveGame;

$hiveGame = new HiveGame();
$hiveGame->restart();

header('Location: index.php');
exit();