<?php
include 'app.php';

use Carbon\Carbon;
include 'header.php';
$item = $news->getArticle($_GET['id']);
?>
<main>
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
