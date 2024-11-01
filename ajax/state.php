<?php

include_once __DIR__.'/../inc/main.inc.php';
include_once __DIR__.'/../inc/state.inc.php';

$pdo = pdoConnect();
getState($pdo);
unset($pdo);
?>