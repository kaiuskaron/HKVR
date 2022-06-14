<?php
include 'app.php';
include 'header.php';

$news = new RifNews();
if (isset($_GET['edit'])) {
    $item = $news->getArticle($_GET['edit']);
}
?>
<main>
    <?php
    if ($user->isLoggedIn()) { ?>
        <div class="wrap contact">
            <div class="grid grid-pad">
                <?php
                if (isset($item)) { ?>
                    <h2>Muuda uudist</h2>
                <?php } else { ?>
                    <h2>Lisa uudis</h2>
                    <?php
                } ?>
                <form action="index.php" method="post" enctype="multipart/form-data">
                    <div class="field">
                        <label for="header">Uudise pealkiri</label>
                        <input type="text" id="header" name="header" placeholder="lisa pealkiri" required
                            <?php if (isset($item)) { ?>
                               value="<?= $item->header; ?>">
                        <input type="hidden" value="<?= $item->id; ?>" name="edit">
                        <?php } ?>
                    </div>
                    <div class="field">
                        <label for="body">Uudise sisu</label>
                        <textarea id="body" name="body"><?php
                            if (isset($item)) {
                                echo $item->body;
                            }
                            ?></textarea>

                    </div>
                    <div class="col-1-2">
                        <div class="field">
                            <?php
                            if (isset($item) && $item->image) { ?>
                                <div class="single">
                                    <label for="file">Uudise praegune pilt</label>
                                    <input type="hidden" name="image" value="<?= $item->image ?>">
                                    <img src="<?= $item->image ?>" alt="<?= $item->header ?>" class="post-img">
                                    <button class="btn danger" type="button" onclick="removeNewsImage(event)">
                                        <i class="icon-remove2"></i> Eemalda
                                    </button>
                                </div>
                            <?php } ?>
                            <label for="file">Uudise pilt</label>
                            <input type="file"
                                   name="file"
                                   id="file"
                                   placeholder="lisa pilt"
                                   accept="image/png,image/jpeg">
                        </div>
                    </div>
                    <div class="col-1-2" style="padding-right: 0">
                        <div class="field">
                            <label for="expires">Uudise aegumistähtaeg</label>
                            <input type="date" id="expires" name="expires" placeholder="millal aegub"
                                   value="<?= $item->expires ?>">
                        </div>
                    </div>
                    <button type="submit" class="btn submit comment-submit">
                        <?php if (isset($item)) { ?>
                            Salvesta uudis
                        <?php } else { ?>
                            Lisa uudis
                        <?php } ?>
                    </button>
                </form>
            </div>
        </div>
        <?php
    } else { ?>
        <div class="grid">
            <div class="col-1-1">
                <div class="alert alert-success">Jätkamiseks logi palun sisse.<br>Või siis loo uus kasutaja...</div>
            </div>
        </div>
    <?php }
    ?>
    <script>
        /*ClassicEditor.builtinPlugins.map( plugin => {
            console.log(plugin.pluginName);
        });*/
        ClassicEditor
            .create(document.querySelector('#body'),
                {
                    removePlugins: [ 'EasyImage','ImageUpload','MediaEmbed' ],
                })
            .catch(error => {
                console.error(error);
            });
    </script>
</main>
</body>
</html>



