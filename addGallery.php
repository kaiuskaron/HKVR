<?php
include 'app.php';
include 'header.php';

$gallery = new Gallery();

if (isset($_POST['image-upload'])) {
    $imgUplResult = $gallery->uploadImage();
}
?>
<main>
    <?php
    if ($user->isLoggedIn()) { ?>
        <div class="wrap contact">
            <div class="grid grid-pad">
                <h2>Lisa pilte</h2>
                <form action="addGallery.php" method="post" enctype="multipart/form-data">
                    <input type="file" id="files" name="image[]" accept="image/png, image/jpeg" capture="environment"
                           multiple placeholder="vali üks või mitu pilti">
                    <?php
                    if ($gallery->error) {
                        echo $gallery->error;
                    }
                    ?>
                    <div id="output" class="output"></div>
                    <?php
                    if (isset($imgUplResult)) { ?>
                        <ul class="upl-result">
                            <?php
                            foreach ($imgUplResult as $uplImg) {
                                if (empty($uplImg->error)) {
                                    echo '<li class="alert alert-success"><i class="icon-checkbox-checked"></i><strong>' . $uplImg->name . '</strong></li>';
                                } else {
                                    echo '<li class="alert alert-danger"><i class="icon-blocked"></i><strong>' . $uplImg->name . '</strong> - ' . $uplImg->error . '</li>';
                                }
                            } ?>
                        </ul>
                        <script>
                            window.setTimeout(() => {
                                document.querySelector('.alert').remove()
                            }, 3000);
                        </script>
                        <?php

                    }
                    ?>
                    <button type="submit" name="image-upload" class="btn comment-submit btn-disabled" id="image-upload"
                            disabled>
                        <i class="icon-upload"></i>
                        Laadi pildid üles
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

</main>
</body>
</html>



