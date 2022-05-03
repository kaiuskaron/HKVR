<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="utf-8">
    <title>RIF21 | uudised</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Parim veebileht, mis siiani nähtud">
    <link rel="stylesheet" href="assets/css/reset.css">
    <link rel="stylesheet" href="assets/css/simplegrid.css">
    <link rel="stylesheet" href="assets/css/icomoon.css">
    <link rel="stylesheet" href="assets/css/lightcase.css">
    <link rel="stylesheet" href="style.css">
    <!-- Google Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700,900' rel='stylesheet'
          type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
</head>
<body>
<!-- Header -->
<header id="top-header" class="header-home">
    <div class="grid">
        <div class="col-1-1">
            <div class="content">
                <div class="logo-wrap">
                    <a href="/~kaius.karon/vr/index.php" class="logo">Rif21-Uudised</a>
                </div>
                <nav class="navigation">
                    <input type="checkbox" id="nav-button">
                    <label for="nav-button" onclick></label>
                    <ul class="nav-container">
                        <!--
                        <li><a href="index.php" class="current">Home</a></li>
                        <li><a href="#">Services</a></li>
                        <li><a href="#">Work</a></li>
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">Pricing</a></li>!-->
                        <li><a href="user.php">Lisa kasutaja </a></li>

                        <li>
                            <?php
                            if ($user->isLoggedIn()) {
                                echo '<a href="' . $_SERVER['PHP_SELF'] . '?logout=1">Logi välja</a>';
                            } else { ?>
                                <form action="<?= $_SERVER['PHP_SELF']; ?>" method="post" autocomplete="off"
                                      style="margin-left: 1rem">
                                    <input type="text" name="username" placeholder="kasutaja" class="login"
                                           autocomplete="new-password">
                                    <input type="password" name="password" placeholder="parool" class="login"
                                           autocomplete="off">
                                    <button type="submit" name="login" class="btn comment-submit login-btn" value="1">
                                        Logi sisse
                                    </button>
                                </form>
                            <?php
                            }
                            ?>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</header>
<?php
if ($user->error) { ?>
<div class="grid">
    <div class="col-1-1">
        <div class="alert alert-danger">
            <h4 class="copyright"><?= $user->error; ?></h4>
        </div>
    </div>
</div>
<!-- End Header -->
<?php
}
