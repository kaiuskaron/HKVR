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
    <link rel="stylesheet" href="assets/css/helper.css">
    <link rel="stylesheet" href="style.css">
    <!-- Google Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700,900' rel='stylesheet'
          type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
    <script src="https://cdn.ckeditor.com/ckeditor5/34.1.0/classic/ckeditor.js"></script>
    <script src="script.js" defer></script>
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
                        <li><a href="index.php">Esileht </a></li>
                        <li><a href="addNews.php">Lisa uudis </a></li>
                        <li><a href="addGallery.php">Lisa pilte </a></li>
                        <li><a href="user.php">Lisa kasutaja </a></li>
                        <li>
                            <?php
                            if ($user->isLoggedIn()) {
                                echo '<a href="' . $_SERVER['PHP_SELF'] . '?logout=1">Logi välja</a>';
                            } else { ?>
                                <form action="<?= $_SERVER['PHP_SELF']; ?>" method="post" autocomplete="off"
                                      style="margin-left: 1rem">
                                    <input type="text" name="username" placeholder="kasutaja" class="login"
                                           autocomplete="new-password"
                                           value="<?php if (isset($_POST['username'])) echo $_POST['username']; ?>">
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
            <!--p class="copyright">Unustasid parooli?</p>
            <p class="copyright">Kliki <a href="#">siia</a></p!-->
        </div>
    </div>
</div>
<!-- End Header -->
<?php
}
