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
    
    if($state) {
    ?>
    <section id="state">
        <div class="row">
            <div class="col-6 col-md-4 col-lg-3 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between px-md-1">
                            <div class="align-self-center">
                                <i class="fas fa-network-wired text-info fa-3x"></i>
                            </div>
                            <div class="text-end">
                                <h3><?php echo $state['network_name']; ?></h3>
                                <p class="mb-0">Network name</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-3 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between px-md-1">
                            <div class="align-self-center">
                                <i class="fas fa-arrows-up-to-line text-warning fa-3x"></i>
                            </div>
                            <div class="text-end">
                                <h3><?php echo $state['peak_height']; ?></h3>
                                <p class="mb-0">Peak Height</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-3 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between px-md-1">
                            <div class="align-self-center">
                                <i class="fas fa-scale-balanced text-success fa-3x"></i>
                            </div>
                            <div class="text-end">
                                <h3><?php echo $state['difficulty']; ?></h3>
                                <p class="mb-0">Difficulty</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-3 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between px-md-1">
                            <div class="align-self-center">
                                <i class="fas fa-hard-drive text-danger fa-3x"></i>
                            </div>
                            <div class="text-end">
                                <h3><?php echo bytes($state['netspace']); ?></h3>
                                <p class="mb-0">Netspace</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>         
    <?php
    }
}
?>