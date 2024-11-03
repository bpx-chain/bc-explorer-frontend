<?php

function bytes($bytes) {
    $units = array('B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB');
    $mod = 1024;
    $power = ($bytes > 0) ? floor(log($bytes, $mod)) : 0;
    return sprintf('%01.2f %s', $bytes / pow($mod, $power), $units[$power]);
}

function getState($pdo) {
    $q = $pdo -> query('SELECT * FROM state');
    $state = $q -> fetch();
    
    $epoch_progress = intval(
        ($state['peak_height'] - $state['epoch_height'])
        / 4608
        * 100
    );
    if($epoch_progress > 100)
        $epoch_progress = 100;
    
    $difficulty_change = intval(
        ($state['difficulty_curr'] - $state['difficulty_prev'])
        / $state['difficulty_prev']
        * 100
    );
    
    $netspace_change = intval(
        ($state['netspace_curr'] - $state['netspace_prev'])
        / $state['netspace_prev']
        * 100
    );
    
    $rate = 0;
    if($state['sub_slot_time'] >= 330)
        $rate = -2;
    else if($state['sub_slot_time'] >= 310)
        $rate = -1;
    else if($state['sub_slot_time'] <= 270)
        $rate = 2;
    else if($state['sub_slot_time'] <= 290)
        $rate = 1;
    
    if($state) {
    ?>
    <section id="state">
        <div class="row">
            <div class="col-6 col-lg-3 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <div class="align-self-center display-6">
                                <i class="fas fa-arrows-up-to-line text-primary"></i>
                            </div>
                            <div class="text-end">
                                <h3><?php echo $state['peak_height']; ?></h3>
                                <p class="mb-0">Peak Height</p>
                            </div>
                        </div>
                        <div class="text-center">
                            <small>
                                <i class="fas fa-network-wired"></i>
                                <?php echo $state['network_name']; ?>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <div class="align-self-center display-6">
                                <i class="fas fa-scale-balanced text-primary"></i>
                            </div>
                            <div class="text-end">
                                <h3><?php echo $state['difficulty_curr']; ?></h3>
                                <p class="mb-0">Difficulty</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 col-md-5 my-auto text-center">
                                <?php if($difficulty_change < 0) { ?>
                                <small class="text-danger">
                                    <i class="fa-solid fa-arrow-trend-down"></i>
                                    <?php echo $difficulty_change; ?>%
                                </small>
                                <?php } else if($difficulty_change == 0) { ?>
                                <small>
                                    <i class="fa-solid fa-arrow-right-long"></i>
                                    0%
                                </small>
                                <?php } else { ?>
                                <small class="text-success">
                                    <i class="fa-solid fa-arrow-trend-up"></i>
                                    +<?php echo $difficulty_change; ?>%
                                </small>
                                <?php } ?>
                            </div>
                            <div class="col-6 col-md-7 my-auto">
                                <div class="progress" style="height: 12px;">
                                    <div
                                     class="progress-bar bg-secondary"
                                     role="progressbar"
                                     style="width: <?php echo $epoch_progress; ?>%;"
                                     aria-valuenow="<?php echo $epoch_progress; ?>"
                                     aria-valuemin="0"
                                     aria-valuemax="100"
                                    ></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <div class="align-self-center display-6">
                                <i class="fas fa-hard-drive text-primary"></i>
                            </div>
                            <div class="text-end">
                                <h3><?php echo bytes($state['netspace_curr']); ?></h3>
                                <p class="mb-0">Netspace</p>
                            </div>
                        </div>
                        <div class="text-center">
                            <?php if($netspace_change < 0) { ?>
                            <small class="text-danger">
                                <i class="fa-solid fa-arrow-trend-down"></i>
                                <?php echo $netspace_change; ?>%
                            </small>
                            <?php } else if($netspace_change == 0) { ?>
                            <small>
                                <i class="fa-solid fa-arrow-right-long"></i>
                                0%
                            </small>
                            <?php } else { ?>
                            <small class="text-success">
                                <i class="fa-solid fa-arrow-trend-up"></i>
                                +<?php echo $netspace_change; ?>%
                            </small>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <div class="align-self-center display-6">
                                <i class="fas fa-gauge text-primary"></i>
                            </div>
                            <div class="text-end">
                                <h3><?php echo $state['sub_slot_time']; ?> sec</h3>
                                <p class="mb-0">Sub-slot time</p>
                            </div>
                        </div>
                        <div class="pt-2">
                            <div class="progress" style="height: 12px;">
                                <div
                                 class="progress-bar bg-danger"
                                 role="progressbar"
                                 style="width: 18.4%;<?php if($rate != -2) echo ' opacity: 10%;'; ?>"
                                 aria-valuenow="18.4"
                                 aria-valuemin="0"
                                 aria-valuemax="100"
                                ></div>
                                <div
                                 class="progress-bar bg-white"
                                 role="progressbar"
                                 style="width: 2%;"
                                 aria-valuenow="2"
                                 aria-valuemin="0"
                                 aria-valuemax="100"
                                ></div>
                                <div
                                 class="progress-bar bg-warning"
                                 role="progressbar"
                                 style="width: 18.4%;<?php if($rate != -1) echo ' opacity: 10%;'; ?>"
                                 aria-valuenow="18.4"
                                 aria-valuemin="0"
                                 aria-valuemax="100"
                                ></div>
                                <div
                                 class="progress-bar bg-white"
                                 role="progressbar"
                                 style="width: 2%;"
                                 aria-valuenow="2"
                                 aria-valuemin="0"
                                 aria-valuemax="100"
                                ></div>
                                <div
                                 class="progress-bar bg-success"
                                 role="progressbar"
                                 style="width: 18.4%;<?php if($rate != 0) echo ' opacity: 10%;'; ?>"
                                 aria-valuenow="18.4"
                                 aria-valuemin="0"
                                 aria-valuemax="100"
                                ></div>
                                <div
                                 class="progress-bar bg-white"
                                 role="progressbar"
                                 style="width: 2%;"
                                 aria-valuenow="2"
                                 aria-valuemin="0"
                                 aria-valuemax="100"
                                ></div>
                                <div
                                 class="progress-bar bg-warning"
                                 role="progressbar"
                                 style="width: 18.4%;<?php if($rate != 1) echo ' opacity: 10%;'; ?>"
                                 aria-valuenow="18.4"
                                 aria-valuemin="0"
                                 aria-valuemax="100"
                                ></div>
                                <div
                                 class="progress-bar bg-white"
                                 role="progressbar"
                                 style="width: 2%;"
                                 aria-valuenow="2"
                                 aria-valuemin="0"
                                 aria-valuemax="100"
                                ></div>
                                <div
                                 class="progress-bar bg-danger"
                                 role="progressbar"
                                 style="width: 18.4%;<?php if($rate != 2) echo ' opacity: 10%;'; ?>"
                                 aria-valuenow="18.4"
                                 aria-valuemin="0"
                                 aria-valuemax="100"
                                ></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div
        </div>
    </section>         
    <?php
    }
}
?>