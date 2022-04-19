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
                        <li><a href="#" class="current">Home</a></li>
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

    use JasonGrimes\Paginator;
    use Carbon\Carbon;

    include 'functions.php';
    const NEWS_PER_PAGE = 4;

    $news = new RifNews();
    // todo error handling

    if (!empty($_POST)) {
        $newItem = new NewsItem($_POST);
        $insertResult = $news->insert($newItem);
        unset($_POST);
    }
    ?>

    <div class="wrap contact">
        <div class="grid grid-pad">
            <h2>Lisa uudis</h2>
            <?php
            if (isset($insertResult) && $insertResult === 1) { ?>
                <div class="alert alert-success">Uudis lisatud!</div>
            <?php } elseif ((isset($insertResult) && $insertResult === -1)) { ?>
                <div class="alert alert-danger">Viga! Uudist ei lisatud!</div>
            <?php } ?>
            <form action="index.php" method="post" enctype="multipart/form-data">
                <div class="field">
                    <label for="header">Uudise pealkiri</label>
                    <input type="text" id="header" name="header" placeholder="lisa pealkiri" required>
                </div>
                <div class="field">
                    <label for="body">Uudise sisu</label>
                    <textarea id="body" name="body" required></textarea>
                </div>
                <div class="col-1-2">
                <div class="field">
                    <label for="file">Uudise pilt</label>
                    <input type="file" name="file" id="file" placeholder="lisa pilt">
                </div>
                </div>
                <div class="col-1-2" style="padding-right: 0">
                <div class="field">
                    <label for="expires">Uudise aegumistähtaeg</label>
                    <input type="date" id="expires" name="expires" placeholder="millal aegub">
                </div>
                </div>
                <button type="submit" class="btn submit comment-submit">Lisa uudis</button>
            </form>
        </div>
    </div>

    <div class="wrap blog-grid grey">
        <div class="grid grid-pad">
            <div class="content">
                <h2>Uudised</h2>
                <div class="col-1-1">
                    <div class="filter">
                        <form action="index.php" method="get">
                            <label for="sort">Järjesta: </label>
                            <select name="sort" id="sort" class="form-select" onchange="this.form.submit()">
                                <option value="0" disabled <?= $news->sort ? '' : 'selected' ?>></option>
                                <option value="1"<?= $news->sort === 1 ? 'selected' : '' ?>>Uuemad ees</option>
                                <option value="2"<?= $news->sort === 2 ? 'selected' : '' ?>>Vanemad ees</option>
                                <option value="3"<?= $news->sort === 3 ? 'selected' : '' ?>>Pealkiri</option>
                            </select>
                        </form>
                    </div>
                </div>
                <?php
                $all = $news->fetch(NEWS_PER_PAGE);
                if ($all) {
                    foreach ($all as $index => $item) { ?>
                        <div class="col-1-2">
                            <article class="post-wrap">
                                <div class="post-img">
                                    <?php if ($item->image) { ?>
                                    <img src="<?= $item->image ?>"
                                         alt="<?= $item->header ?>">
                                    <?php } ?>
                                </div>
                                <div class="post">
                                    <h2 class="entry-title">
                                        <?= $item->header; ?>
                                    </h2>
                                    <p><?= $item->excerpt(); ?></p>
                                    <div class="post-meta">
                                        <div class="box-icon">
                                            <i class="icon-clock"></i>
                                        </div>
                                        <p>Lisatud:</p>
                                        <p><?= Carbon::parse($item->created)->locale('et')->diffForHumans();?></p>
                                    </div>
                                    <a class="btn read-more" href="article.php?id=<?= $item->id ?>">Loe edasi</a></div>
                            </article>
                        </div>
                    <?php }
                    $paginator = new Paginator(
                        $news->ttlNewsCount,
                        NEWS_PER_PAGE,
                        $news->currentPage,
                        'index.php?sort=' . $news->sort . '&page=(:num)'
                    );
                    $paginator->setPreviousText('Eelmine');
                    $paginator->setNextText('Järgmine');

                    echo '<div class="col-1-1">' . $paginator . '</div>';
                }
                ?>
            </div>
        </div>
    </div>

</main>
</body>
</html>
