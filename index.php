<?php
include 'app.php';

use JasonGrimes\Paginator;
use Carbon\Carbon;

include 'header.php';
$news = new RifNews();

$gallery = new Gallery();
$images = $gallery->fetchThumbs();

?>

<main>
    <?php if (!empty($images)) { ?>
        <div class="wrap blog-grid grey">
            <div class="grid grid-pad">
                <div class="content">
                    <h2>Pildid</h2>
                    <?php
                    foreach ($images as $thumb) { ?>
                        <div class="col-1-3">
                            <div class="post-wrap">
                                <div class="post-img">
                                    <img src="uploads/thumbs/<?= $thumb->name; ?>" alt="<?= $thumb->alt; ?>" class="">
                                    <figure-caption><?= $thumb->alt; ?></figure-caption>
                                </div>
                                <div class="post">
                                    <a class="btn read-more" href="#"
                                       onclick="openModal('<?= $thumb->name; ?>', <?= $thumb->id; ?>)">Vaata</a>
                                    <div class="meta-wrap">
                                        <div class="post-meta" style="width: 100%">
                                            <div class="box-icon">
                                                <i class="icon-eye"></i>
                                            </div>
                                            <p>vaadatud: <?= $thumb->view_count; ?></p>
                                        </div>
                                        <div class="post-meta" style="width: 100%">
                                            <div class="box-icon">
                                                <i class="icon-star"></i>
                                            </div>
                                            <p>hinne: <span
                                                        id="rating_<?= $thumb->id; ?>"><?= round($thumb->rate, 1); ?></span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="rating">
                                        <?php
                                        if ($user->isLoggedIn()) {
                                            for ($i = 1; $i < 6; $i++) { ?>
                                                <label for="rate_<?= $thumb->id . '_' . $i; ?>">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                         xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                                         width="32px" height="32px" viewBox="0 0 122.88 116.864"
                                                         enable-background="new 0 0 122.88 116.864"
                                                         xml:space="preserve"><g>
                                                            <polygon fill-rule="evenodd" clip-rule="evenodd"
                                                                     fill="#ffffff" stroke="#000000"
                                                                     points="61.44,0 78.351,41.326 122.88,44.638 88.803,73.491 99.412,116.864 61.44,93.371 23.468,116.864 34.078,73.491 0,44.638 44.529,41.326 61.44,0"/>
                                                        </g>
                                                </svg>
                                                    <input type="radio" name="rating" value="<?= $i; ?>"
                                                           id="rate_<?= $thumb->id . '_' . $i; ?>"
                                                           onchange="rate(<?= $thumb->id . ", " . $i; ?>)">
                                                </label>
                                            <?php }
                                        } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php }
                    ?>
                </div>
            </div>
        </div>
    <?php }

    if (!empty($_POST) && isset($_POST['header'])) {
        $newItem = new NewsItem($_POST);
        if (isset($_POST['edit'])) {
            $updateResult = $news->update($newItem);
        } else {
            $insertResult = $news->insert($newItem);
        }
        unset($_POST);
    }

    if ($user->isLoggedIn()) {
        if (isset($insertResult) && $insertResult === 1) { ?>
            <div class="alert alert-success">Uudis lisatud!</div>
        <?php } elseif ((isset($insertResult) && $insertResult === -1)) { ?>
            <div class="alert alert-danger">Viga! Uudist ei lisatud!</div>
        <?php }
        if (isset($updateResult) && $updateResult === 1) { ?>
            <div class="alert alert-success">Uudis mudetud!</div>
        <?php } elseif ((isset($updateResult) && $updateResult === -1)) { ?>
            <div class="alert alert-danger">Viga! Uudist ei muudetud!</div>
        <?php } ?>
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
            <div class="col-1-1 mt-1">
                <div class="alert alert-success">Udiste nägemiseks logi palun sisse.<br>Või siis loo uus kasutaja...
                </div>
            </div>
        </div>
    <?php }
    ?>

</main>
<div id="myModal" class="modal">
    <span class="close cursor" onclick="closeModal()">&times;</span>
    <div class="modal-content">
        <img class="slide" src="" alt="" id="slide">
    </div>
</div>

</body>
</html>
