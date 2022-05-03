<?php
include 'app.php';

use JasonGrimes\Paginator;
use Carbon\Carbon;

include 'header.php';
$news = new RifNews();
?>
<main>
    <?php

    if (!empty($_POST) && isset($_POST['header'])) {
        $newItem = new NewsItem($_POST);
        $insertResult = $news->insert($newItem);
        unset($_POST);
    }

    if ($user->isLoggedIn()) { ?>
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
                                        <div class="meta-wrap">
                                            <div class="post-meta">
                                                <div class="box-icon">
                                                    <i class="icon-clock"></i>
                                                </div>
                                                <p>
                                                    Lisatud: <?= Carbon::parse($item->created)->locale('et')->diffForHumans(); ?></p>
                                            </div>
                                            <div class="post-meta">
                                                <div class="box-icon">
                                                    <i class="icon-user"></i>
                                                </div>
                                                <p>Autor: <?= $item->author; ?></p>
                                            </div>
                                        </div>
                                        <a class="btn read-more" href="article.php?id=<?= $item->id ?>">Loe edasi</a>
                                    </div>
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
    <?php } else { ?>
        <div class="grid">
            <div class="col-1-1">
                <div class="alert alert-success">Jätkamiseks logi palun sisse.<br>Või siis loo uus kasutaja...</div>
            </div>
        </div>
    <?php }
    ?>
</main>
</body>
</html>
