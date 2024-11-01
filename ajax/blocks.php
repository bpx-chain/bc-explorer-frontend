<?php

include_once __DIR__.'/../inc/main.inc.php';
include_once __DIR__.'/../inc/blocks.inc.php';

$pdo = pdoConnect();
getRecentBlocks($pdo, 1, true, @$_GET['cur']);
unset($pdo);
?>