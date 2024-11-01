<?php

include_once __DIR__.'/inc/main.inc.php';

$pdo = pdoConnect();

$page = 1;
if(isset($_GET['page']) && is_numeric($_GET['page'])) {
    $intPage = intval($_GET['page']);
    if($intPage > 1)
        $page = $intPage;
}

$blocks = [];

if(isset($_GET['q'])) {
    $q = strtolower($_GET['q']);
    $task = null;
    $sql = null;
    
    // Height
    if(preg_match('/^[0-9]+$/', $q)) {
        $task = [
            ':height' => intval($q)
        ];
        
        $sql = 'SELECT height, hash, timestamp
                FROM blocks
                WHERE height = :height';
    }
    
    else {
        if($q[0] != '0' && $q[1] != 'x')
            $q = '0x'.$q;
        
        // Hash
        if(preg_match('/^0x[0-9a-f]{64}$/', $q)) {
            $task = [
                ':hash' => $q,
                ':execution_block_hash' => $q
            ];
            
            $sql = 'SELECT height, hash, timestamp
                    FROM blocks
                    WHERE hash = :hash
                    OR execution_block_hash = :execution_block_hash';
        }
        
        // Address
        else if(preg_match('/^0x[0-9a-f]{40}$/', $q)) {
            $task = [
                ':coinbase' => $q,
                ':fee_recipient' => $q,
                ':wd_addresses' => '%'.$q.'%'
            ];
            
            $sql = 'SELECT height, hash, timestamp
                    FROM blocks
                    WHERE coinbase = :coinbase
                    OR fee_recipient = :fee_recipient
                    OR wd_addresses LIKE :wd_addresses';
        }
    }
    
    if($task) {
        $task[':offset'] = ($page - 1) * 50;
        $sql .= ' ORDER BY height DESC
                  LIMIT 50 OFFSET :offset';
        $q = $pdo -> prepare($sql);
        $q -> execute($task);
        $blocks = $q -> fetchAll();
    }
}

getHeader('Search | BPX Beacon Chain explorer');
getBlocks('Search Results', $blocks, $page, '&q='.$_GET['q']);
getFooter();

unset($pdo);
?>