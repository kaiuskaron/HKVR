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
                    <a href="#" class="logo">Rif21-Uudised</a>
                </div>
                <nav class="navigation">
                    <input type="checkbox" id="nav-button">
                    <label for="nav-button" onclick></label>
                    <ul class="nav-container">
                        <li><a href="index.php" class="current">Home</a></li>
                        <li><a href="#">Services</a></li>
                        <li><a href="#">Work</a></li>
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">Pricing</a></li>
                        <li><a href="#">Team</a></li>
                        <li><a href="#">Contact</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</header>
<!-- End Header -->
<main>
    <?php
    require __DIR__ . '/vendor/autoload.php';

    use Carbon\Carbon;

    include 'functions.php';
    $news = new RifNews();
    $item = $news->getArticle($_GET['id']);
    ?>
    <div class="wrap blog-grid grey">
        <div class="grid grid-pad">
            <div class="col-1-1">
                <?php if ($item) { ?>
                    <div class="content">
                        <h2><?= $item->header; ?></h2>
                        <article class="post-wrap">
                            <div class="post-img">
                                <?php if ($item->image) { ?>
                                    <img src="<?= $item->image ?>"
                                         alt="<?= $item->header ?>">
                                <?php } ?>
                            </div>
                            <div class="post">
                                <p><?= $item->body; ?></p>
                                <div class="post-meta">
                                    <div class="box-icon">
                                        <i class="icon-clock"></i>
                                    </div>
                                    <p>Lisatud:</p>
                                    <p><?= Carbon::parse($item->created)->locale('et')->diffForHumans(); ?></p>
                                </div>
                        </article>
                        <!-- todo loe järgmine, hinda, jms !-->
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

</main>
</body>
</html>
