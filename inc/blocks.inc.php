<?php

include_once __DIR__.'/main.inc.php';

function getRecentBlocks($pdo, $pageUnsafe, $ajax = false, $cur = null) {
    $page = 1;
    if(isset($pageUnsafe) && is_numeric($pageUnsafe)) {
        $intPage = intval($pageUnsafe);
        if($intPage > 1)
            $page = $intPage;
    }
    
    $task = [
        ':offset' => ($page - 1) * 50
    ];
    
    $sql = 'SELECT height, hash, timestamp
            FROM blocks
            ORDER BY height DESC
            LIMIT 50 OFFSET :offset';
    
    $q = $pdo -> prepare($sql);
    $q -> execute($task);
    $blocks = $q -> fetchAll();
    
    if(isset($cur) && isset($blocks[0]) && $blocks[0]['height'] == $cur)
        return;
    
    getBlocks('Recent Blocks', $blocks, $page, '', $cur);
    
    if(!$ajax && $page == 1) {
    ?>
    <script type="text/javascript">
        $(document).ready(function() {
            setInterval(function() {
                let cur = $('.block').length ? '?cur=' + $('.block').first().data('height') : '';
                $.get('/ajax/blocks' + cur, function(data) {
                    if(!data)
                        return;
                    $('#blocks').replaceWith(data);
                    renderTimestamps();
                });
            }, 5000);
        });
    </script>
    <?php
    }
}
?>