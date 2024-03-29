<?php

function getState() {
    return serialize([$_SESSION['hand'], $_SESSION['board'], $_SESSION['player']]);
}

function setState($state) {
    list($a, $b, $c) = unserialize($state);
    $_SESSION['hand'] = $a;
    $_SESSION['board'] = $b;
    $_SESSION['player'] = $c;
}

$servername = "ows-db";
$username = "root";
$password = getenv("Ows1234root");
$database = "hive";

$conn = new mysqli($servername, $username, $password, $database);
