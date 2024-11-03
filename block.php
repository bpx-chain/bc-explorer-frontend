<?php

include_once __DIR__.'/inc/main.inc.php';

if(!isset($_GET['height']) || !ctype_digit($_GET['height'])) {
    header('Location: /404');
    die();
}

$pdo = pdoConnect();

$task = [
    ':height' => $_GET['height']
];

$sql = 'SELECT *
        FROM blocks
        WHERE height = :height';

$q = $pdo -> prepare($sql);
$q -> execute($task);
$block = $q -> fetch();

if(!$block) {
    header('Location: /404');
    die();
}

$body = json_decode($block['body']);

getHeader('Block '.$block['height'].' | BPX Beacon Chain explorer');
?>
<section class="mb-4">
    <div class="card">
        <div class="card-header py-3">
            <h5 class="mb-0 text-primary">
                <strong>
                    <?php if(isset($block['timestamp'])) { ?>
                    <i class="fa-solid fa-square-full"></i>
                    <?php } else { ?>
                    <i class="fa-regular fa-square-full"></i>
                    <?php } ?>
                    Beacon Block #<?php echo $block['height']; ?>
                </strong>
            </h5>
        </div>
        <div class="card-body small">
            <div class="row pb-2">
                <div class="col-12 col-md-3 col-lg-2">
                    <strong>Height</strong>
                </div>
                <div class="col-12 col-md-9 col-lg-10">
                    <?php echo $block['height']; ?>
                </div>
            </div>
            <div class="row py-2 border-top">
                <div class="col-12 col-md-3 col-lg-2">
                    <strong>Header Hash</strong>
                </div>
                <div class="col-12 col-md-9 col-lg-10">
                    <?php echo $block['hash']; ?>
                </div>
            </div>
            <div class="row py-2 border-top">
                <div class="col-12 col-md-3 col-lg-2">
                    <strong>Timestamp</strong>
                </div>
                <div class="col-12 col-md-9 col-lg-10">
                    <?php
                        echo $block['timestamp']
                            ? '<span class="time" data-time="'.$block['timestamp'].'"></span>'
                            : '<span class="text-secondary">(null)</span>';
                    ?>
                </div>
            </div>
            <div class="row py-2 border-top">
                <div class="col-12 col-md-3 col-lg-2">
                    <strong>Execution Block Hash</strong>
                </div>
                <div class="col-12 col-md-9 col-lg-10">
                    <?php
                        echo $block['timestamp']
                            ? $body -> foliage_transaction_block -> execution_block_hash
                            : '<span class="text-secondary">(null)</span>';
                    ?>
                </div>
            </div>
            <div class="row py-2 border-top">
                <div class="col-12 col-md-3 col-lg-2">
                    <strong>Total Iterations</strong>
                </div>
                <div class="col-12 col-md-9 col-lg-10">
                    <?php echo $body -> reward_chain_block -> total_iters; ?>
                </div>
            </div>
            <div class="row py-2 border-top">
                <div class="col-12 col-md-3 col-lg-2">
                    <strong>Weight</strong>
                </div>
                <div class="col-12 col-md-9 col-lg-10">
                    <?php echo $body -> reward_chain_block -> weight; ?>
                </div>
            </div>
            <div class="row py-2 border-top">
                <div class="col-12 col-md-3 col-lg-2">
                    <strong>Parent Block</strong>
                </div>
                <div class="col-12 col-md-9 col-lg-10">
                    <?php if($block['height'] == 0) echo $body -> foliage -> prev_block_hash; else { ?>
                    <a href="/block/<?php echo $block['height'] - 1; ?>">
                        <?php echo $body -> foliage -> prev_block_hash; ?>
                    </a>
                    <?php } ?>
                </div>
            </div>
            <div class="row py-2 border-top">
                <div class="col-12 col-md-3 col-lg-2">
                    <strong>Farmer Address</strong>
                </div>
                <div class="col-12 col-md-9 col-lg-10">
                    <?php echo $block['coinbase']; ?>
                </div>
            </div>
            <div class="row pt-2 border-top">
                <div class="col-12 col-md-3 col-lg-2">
                    <strong>Raw Block</strong>
                </div>
                <div class="col-12 col-md-9 col-lg-10">
                    <pre id="json-raw-block"></pre>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="mb-4">
    <div class="card">
        <div class="card-header py-3">
            <h6 class="mb-0">
                <strong>Proof of Space</strong>
            </h6>
        </div>
        <div class="card-body small">
            <div class="row pb-2">
                <div class="col-12 col-md-3 col-lg-2">
                    <strong>Challenge</strong>
                </div>
                <div class="col-12 col-md-9 col-lg-10">
                    <?php echo $body -> reward_chain_block -> proof_of_space -> challenge; ?>
                </div>
            </div>
            <div class="row py-2 border-top">
                <div class="col-12 col-md-3 col-lg-2">
                    <strong>Signage Point</strong>
                </div>
                <div class="col-12 col-md-9 col-lg-10">
                    <?php echo $body -> reward_chain_block -> signage_point_index; ?>
                </div>
            </div>
            <div class="row py-2 border-top">
                <div class="col-12 col-md-3 col-lg-2">
                    <strong>Plot Public Key</strong>
                </div>
                <div class="col-12 col-md-9 col-lg-10">
                    <?php echo $body -> reward_chain_block -> proof_of_space -> plot_public_key; ?>
                </div>
            </div>
            <div class="row py-2 border-top">
                <div class="col-12 col-md-3 col-lg-2">
                    <strong>Plot Pool PH</strong>
                </div>
                <div class="col-12 col-md-9 col-lg-10">
                    <?php
                        echo $body -> reward_chain_block -> proof_of_space -> pool_contract_puzzle_hash
                            ? $body -> reward_chain_block -> proof_of_space -> pool_contract_puzzle_hash
                            : '<span class="text-secondary">(null)</span>';
                    ?>
                </div>
            </div>
            <div class="row py-2 border-top">
                <div class="col-12 col-md-3 col-lg-2">
                    <strong>Plot Pool PK</strong>
                </div>
                <div class="col-12 col-md-9 col-lg-10">
                    <?php
                        echo $body -> reward_chain_block -> proof_of_space -> pool_public_key
                            ? $body -> reward_chain_block -> proof_of_space -> pool_public_key
                            : '<span class="text-secondary">(null)</span>';
                    ?>
                </div>
            </div>
            <div class="row pt-2 border-top">
                <div class="col-12 col-md-3 col-lg-2">
                    <strong>Plot size</strong>
                </div>
                <div class="col-12 col-md-9 col-lg-10">
                    K-<?php echo $body -> reward_chain_block -> proof_of_space -> size; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="mb-4">
    <div class="card">
        <div class="card-header py-3">
            <h6 class="mb-0">
                <strong>Execution Payload</strong>
            </h6>
        </div>
        <div class="card-body small">
            <?php if($body -> execution_payload) { ?>
            <div class="row pb-2">
                <div class="col-12 col-md-3 col-lg-2">
                    <strong>Block Number</strong>
                </div>
                <div class="col-12 col-md-9 col-lg-10">
                    <?php echo $body -> execution_payload -> blockNumber; ?>
                </div>
            </div>
            <div class="row py-2 border-top">
                <div class="col-12 col-md-3 col-lg-2">
                    <strong>Block Hash</strong>
                </div>
                <div class="col-12 col-md-9 col-lg-10">
                    <?php echo $body -> execution_payload -> blockHash; ?>
                </div>
            </div>
            <div class="row py-2 border-top">
                <div class="col-12 col-md-3 col-lg-2">
                    <strong>Timestamp</strong>
                </div>
                <div class="col-12 col-md-9 col-lg-10">
                    <span class="time" data-time="<?php echo $body -> execution_payload -> timestamp; ?>"></span>
                </div>
            </div>
            <div class="row py-2 border-top">
                <div class="col-12 col-md-3 col-lg-2">
                    <strong>Fee Recipient</strong>
                </div>
                <div class="col-12 col-md-9 col-lg-10">
                    <?php echo $body -> execution_payload -> feeRecipient; ?>
                </div>
            </div>
            <div class="row py-2 border-top">
                <div class="col-12 col-md-3 col-lg-2">
                    <strong>Parent Block Hash</strong>
                </div>
                <div class="col-12 col-md-9 col-lg-10">
                    <?php echo $body -> execution_payload -> parentHash; ?>
                </div>
            </div>
            <div class="row pt-2 border-top">
                <div class="col-12 col-md-3 col-lg-2">
                    <strong>Raw Payload</strong>
                </div>
                <div class="col-12 col-md-9 col-lg-10">
                    <pre id="json-raw-payload"></pre>
                </div>
            </div>
            <div class="row">
                <div class="alert alert-secondary" role="alert">
                    <i class="fa-solid fa-link"></i>
                    <a href="<?php echo EC_EXPLORER_URL.'/block/'.$body -> execution_payload -> blockNumber; ?>" target="_blank">
                        View block <?php echo $body -> execution_payload -> blockNumber; ?> in Execution Chain block explorer
                    </a>
                </div>
            </div>
            <?php } else { ?>
                <span class="text-secondary">This block does not contain execution payload</span>
            <?php } ?>
        </div>
    </div>
</section>
<section class="mb-4">
    <div class="card">
        <div class="card-header py-3">
            <h6 class="mb-0">
                <strong>Withdrawals to the Execution Chain</strong>
            </h6>
        </div>
        <div class="card-body small">
            <?php if($body -> execution_payload) { ?>
            <div class="table-responsive">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th scope="col">Index</th>
                            <th scope="col">Type</th>
                            <th scope="col">Address</th>
                            <th scope="col">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if(isset($body -> execution_payload -> withdrawals))
                        foreach($body -> execution_payload -> withdrawals as $w) {
                        ?>
                        <tr>
                            <td>
                                <?php echo $w -> index; ?>
                            </td>
                            <td>
                                <strong>
                                <?php
                                    if($w -> validatorIndex == 0) echo 'Prefarm';
                                    else if($w -> validatorIndex == 1) echo 'Block Reward';
                                    else echo 'Unknown';
                                ?>
                                </strong>
                                (<?php echo $w -> validatorIndex; ?>)
                            </td>
                            <td>
                                <?php echo $w -> address; ?>
                            </td>
                            <td>
                                <?php echo bcdiv($w -> amount, '1000000000'); ?> BPX
                            </td>
                        </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <?php } else { ?>
            <span class="text-secondary">This block does not contain withdrawals</span>
            <?php } ?>
        </div>
    </div>
</section>
<script type="text/javascript">
$(document).ready(function() {
    let jsonBlock = <?php echo $block['body']; ?>;
    let jsonPayload = <?php echo json_encode($body -> execution_payload); ?>;
    let options = {
        collapsed: true
    };
    $('#json-raw-block').jsonViewer(jsonBlock, options);
    $('#json-raw-payload').jsonViewer(jsonPayload, options);
});
</script>
<?php
getFooter();
unset($pdo);
?>