<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="utf-8">
    <title>Kaius Karon VR</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Parim veebileht, mis siiani nähtud">
    <link rel="stylesheet" href="assets/css/reset.css">
    <link rel="stylesheet" href="assets/css/simplegrid.css">
    <link rel="stylesheet" href="assets/css/icomoon.css">
    <link rel="stylesheet" href="assets/css/lightcase.css">
    <link rel="stylesheet" href="style.css">

    <!-- Google Fonts -->
    <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700,900' rel='stylesheet'
          type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
</head>
<body>
<header>

</header>
<main>
    <?php
    require __DIR__ . '/vendor/autoload.php';
    include 'functions.php';

    $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__, 2));
    $dotenv->load();

    $news = new RifNews();
    $stat = $news->connectDb();

    /*if (!$stat) {
        echo "Böö";
    } else {
        echo "Jee";
    }*/

    if (!empty($_POST)) {
        $newItem = new NewsItem($_POST);
        $news->insert($newItem);
        unset($_POST);
    }
    ?>
    <div class="wrap contact">
        <div class="grid grid-pad">
            <h2>Lisa uudis</h2>
            <form action="index.php" method="post">
                <div class="field">
                    <label for="header">Uudise pealkiri</label>
                    <input type="text" id="header" name="header" class="form-control" placeholder="lisa pealkiri"
                           required>
                </div>
                <div class="field">
                    <label for="body">Uudise sisu</label>
                    <textarea id="body" name="body" class="form-control" required></textarea>
                </div>
                <div class="field">
                    <label for="expires">Uudise aegumistähtaeg</label>
                    <input type="date" id="expires" name="expires" placeholder="millal aegub">
                </div>
                <button type="submit" class="btn submit comment-submit">Lisa uudis</button>
            </form>
        </div>

        <hr>
        <div class="wrap blog-grid grey">
            <div class="grid grid-pad">
                <div class="content">
                    <h2>Uudised</h2>
                    <?php
                    $all = $news->fetch();
                    if ($all) {
                        foreach ($all as $item) { ?>
                            <div class="col-1-2">
                                <article class="post-wrap">
                                    <!--div class="post-img">
                                        <img src="assets/img/beer.jpg" alt="website template image">
                                    </div!-->
                                    <div class="post">
                                        <h2 class="entry-title">
                                            <?= $item->header; ?>
                                        </h2>
                                        <div class="post-meta">
                                            <?= $item->created; ?><span class="mid-sep">·</span><?= $item->user_id; ?>
                                        </div>
                                        <p><?= $item->excerpt(); ?></p>
                                        <a class="btn read-more" href="#">Loe edasi</a></div>
                                </article>
                            </div>
                        <?php }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</main>
</body>
</html>
