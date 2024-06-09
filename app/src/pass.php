<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';

$_SESSION['player'] = 1 - $_SESSION['player'];

header('Location: index.php');
exit();
