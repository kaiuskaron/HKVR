<?php
include 'app.php';
include 'header.php';

$news = new RifNews();
?>
<main>
    <?php
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



