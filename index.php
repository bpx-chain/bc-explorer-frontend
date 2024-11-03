<?php

include_once __DIR__.'/inc/main.inc.php';
include_once __DIR__.'/inc/state.inc.php';
include_once __DIR__.'/inc/blocks.inc.php';

$pdo = pdoConnect();

getHeader('BPX Beacon Chain explorer');
getState($pdo);
getRecentBlocks($pdo, @$_GET['page']);
?>
<script type="text/javascript">
    $(document).ready(function() {
        setInterval(function() {
            $.get('/ajax/state', function(data) {
                $('#state').replaceWith(data);
            });
        }, 5000);
    });
</script>
<?php
getFooter();

unset($pdo);
?>