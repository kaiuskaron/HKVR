<?php
include 'app.php';

use Carbon\Carbon;

include 'header.php';
$news = new RifNews();
if (isset($_GET['id'])) {
    $item = $news->getArticle($_GET['id']);
}
?>
<main>
    <div class="wrap blog-grid grey">
        <div class="grid grid-pad">
            <div class="col-1-1">
                <?php if (isset($item)) { ?>
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
                        </article>
                        <?php if ($user->isLoggedIn()) { ?>
                            <button class="btn submit danger comment-submit"
                               style="padding:8px 26px" onclick="deleteNews(<?= $item->id;?>)">
                                <i class="icon-trash-alt"></i> Kustuta
                            </button>
                            <a href="addNews.php?edit=<?= $item->id; ?>" class="btn submit comment-submit mr-1"
                               style="padding:8px 26px">
                                <i class="icon-pencil"></i> Muuda
                            </a>
                        <?php } ?>
                        <!-- todo loe j??rgmine, hinda, jms !-->
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

</main>
</body>
</html>
