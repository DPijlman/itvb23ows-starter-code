<?php
require_once __DIR__ . '/vendor/autoload.php';

use HiveGame\MoveHandler\PlayHandler;
use HiveGame\Util\SessionManager;

$piece = $_POST['piece'];
$to = $_POST['to'];

if (!PlayHandler::play($piece, $to)) {
    header('Location: index.php');
    exit();
}
header('Location: index.php');
