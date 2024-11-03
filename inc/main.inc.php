<?php

include_once __DIR__.'/../config.inc.php';

function pdoConnect() {
    $pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
    $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo -> setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $pdo -> setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
}

function getHeader($title) {
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta http-equiv="x-ua-compatible" content="ie=edge" />
        <title><?php echo $title; ?></title>
        
        <!-- Font Awesome -->
        <link
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
            rel="stylesheet"
        />
        <!-- Google Fonts -->
        <link
            href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap"
            rel="stylesheet"
        />
        <!-- MDB -->
        <link
            href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.2.0/mdb.min.css"
            rel="stylesheet"
        />
        <!-- jQuery -->
        <script
            src="https://code.jquery.com/jquery-3.6.4.min.js"
            integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8="
            crossorigin="anonymous">
        </script>
        <!-- JSON Viewer -->
        <script src="https://cdn.jsdelivr.net/npm/jquery.json-viewer@1.5.0/json-viewer/jquery.json-viewer.min.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jquery.json-viewer@1.5.0/json-viewer/jquery.json-viewer.min.css">
        <script type="text/javascript">
            function renderTimestamps() {
                $('.time').each(function() {
                    $(this).html(
                        new Date( $(this).data('time') * 1000 ).toLocaleString()
                    );
                });
            }
        </script>
        <style type="text/css">
            @keyframes blink {
                0%,100% {
                    background-color: white;
                }
                25% {
                    background-color: #E3EBF7;
                }
            }
            
            .block-new {
                animation-name: blink;
                animation-duration: 3s;
                animation-iteration-count: 1;
                animation-timing-function: ease-in-out;
            }
            
            body {
                background-color: #fbfafc;
            }
        </style>
    </head>

    <body>
        <header>
            <nav id="main-navbar" class="navbar navbar-expand-lg navbar-light bg-white fixed-top">
                <div class="container-fluid flex-nowrap">
                    <a class="navbar-brand" href="/">
                        <img src="/img/logo.svg" height="32" alt="" loading="lazy" />
                        <h4 class="ms-2 my-auto d-none d-md-block">Beacon Chain explorer</h4>
                        <h4 class="ms-2 my-auto d-md-none">BC</h4>
                    </a>

                    <form method="GET" action="/search" class="input-group w-100 my-auto">
                        <input autocomplete="off" type="search" class="form-control rounded"
                            placeholder='Search: height / hash / address' name="q" />
                        <span class="input-group-text border-0"><i class="fas fa-search"></i></span>
                    </form>
                </div>
            </nav>
        </header>

        <main style="margin-top: 62px">
            <div class="container pt-4">
<?php
}

function getFooter() {
?>
            </div>
        </main>
    </body>
    <script type="text/javascript">
        $(document).ready(function() {
            renderTimestamps();
        });
    </script>
</html>
<?php
}

function getBlocks($title, $blocks, $page, $appendGet = '', $cur = null) {
?>
<section class="mb-4" id="blocks">
    <div class="card">
        <div class="card-header text-center py-3">
            <h5 class="mb-0 text-center">
                <strong><?php echo $title; ?></strong>
            </h5>
        </div>
        <div class="card-body">
            <nav>
                <ul class="pagination">
                    <li class="page-item me-auto<?php if($page == 1) echo ' disabled'; ?>">
                        <a class="page-link" href="?page=<?php echo ($page - 1).$appendGet; ?>">
                            Previous
                        </a>
                    </li>
                    <?php if($page != 1) { ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo ($page - 1).$appendGet; ?>">
                            <?php echo $page - 1; ?>
                        </a>
                    </li>
                    <?php } ?>
                    <li class="page-item active" aria-current="page">
                        <span class="page-link">
                            <?php echo $page; ?>
                        </span>
                    </li>
                    <?php if(count($blocks) == 50) { ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo ($page + 1).$appendGet; ?>">
                            <?php echo $page + 1; ?>
                        </a>
                    </li>
                    <?php } ?>
                    <li class="page-item ms-auto<?php if(count($blocks) != 50) echo ' disabled'; ?>">
                        <a class="page-link" href="?page=<?php echo ($page + 1).$appendGet; ?>">
                            Next
                        </a>
                    </li>
                </ul>
            </nav>
            <div class="table-responsive">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th scope="col">Height</th>
                            <th scope="col">Block hash</th>
                            <th scope="col">Timestamp</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach($blocks as $b) {
                            $trClass = (isset($cur) && is_numeric($cur) && intval($b['height']) > $cur)
                                ? 'block-new' : '';
                        ?>
                        <tr class="block <?php echo $trClass; ?>" data-height="<?php echo $b['height']; ?>">
                            <td>
                                <a href="/block/<?php echo $b['height']; ?>">
                                    <?php echo $b['height']; ?>
                                </a>
                            </td>
                            <td>
                                <a href="/block/<?php echo $b['height']; ?>">
                                    <?php if(isset($b['timestamp'])) { ?>
                                    <i class="fa-solid fa-square-full"></i>
                                    <?php } else { ?>
                                    <i class="fa-regular fa-square-full"></i>
                                    <?php } ?>
                                    <span><?php echo $b['hash']; ?></span>
                                </a>
                            </td>
                            <td>
                                <?php if(isset($b['timestamp'])) { ?>
                                <span class="time" data-time="<?php echo $b['timestamp']; ?>"></span>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
<?php
}
?>