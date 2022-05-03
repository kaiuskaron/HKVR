<?php

use Carbon\Carbon;

include 'app.php';
include 'header.php';
?>
<main>
    <div class="wrap contact">
        <div class="grid grid-pad">
            <form action="user.php" method="post" enctype="multipart/form-data">
                <div class="col-1-2">
                    <div class="field">
                        <label for="first_name_input">Eesnimi</label>
                        <input type="text" id="first_name_input" name="first_name_input" placeholder="eesnimi" required
                               value="<?php echo $user->firstname; ?>"><span><?php echo $user->firstname_error; ?></span>
                    </div>
                </div>

                <div class="col-1-2">
                    <div class="field">
                        <label for="surname_input">Perenimi</label>
                        <input type="text" id="surname_input" name="surname_input" placeholder="perenimi" required
                               value="<?php echo $user->surname; ?>"><span><?php echo $user->surname_error; ?></span>
                    </div>
                </div>

                <div class="col-1-2">
                    <div class="field" style="margin-bottom: 25px">
                        <label for="gender_input_1"> Naine</label>
                        <input type="radio" name="gender_input" id="gender_input_1"
                               value="2" <?php if ($user->gender == "2") {
                            echo " checked";
                        } ?>>

                        <label for="gender_input_2"> Mees</label>
                        <input type="radio" name="gender_input" id="gender_input_2"
                               value="1" <?php if ($user->gender == "1") {
                            echo " checked";
                        } ?>>
                        <span><?php echo $user->gender_error; ?></span>
                    </div>
                </div>

                <div class="col-1-2">
                    <div class="field">
                        <label for="birth_date_input">Sünniaeg</label>
                        <select name="birth_year_input" id="birth_year_input" style="width: auto">
                            <option value="" selected disabled>aasta</option>
                            <?php
                            for ($i = date("Y") - 10; $i > date("Y") - 100; $i--) {
                                echo '<option value="' . $i . '">' . $i . '</option>';
                            }
                            ?>
                        </select>
                        <select name="birth_month_input" id="birth_month_input" style="width: auto">
                            <option value="" selected disabled>kuu</option>
                            <?php

                            for ($i = 1; $i < 13; $i++) {
                                $k = Carbon::create()->day(1)->month($i)->locale('et')->monthName;
                                echo '<option value="' . $i . '">' . $k . '</option>';
                            }
                            ?>
                        </select>
                        <select name="birth_day_input" id="birth_day_input" style="width: auto">
                            <option value="" selected disabled>päev</option>
                            <?php
                            for ($i = 1; $i < 32; $i++) {
                                echo '<option value="' . $i . '">' . $i . '</option>';
                            }
                            ?>
                        </select>
                        <span><?php echo $user->birth_date_error; ?></span>
                    </div>
                </div>

                <div class="col-1-2">
                    <div class="field">
                        <label for="email_input">E-mail</label>
                        <input type=email id="email_input" name="email_input" placeholder="kasutajatunnus"
                               value="<?php echo $user->email; ?>">
                        <span><?php echo $user->email_error; ?></span>
                    </div>
                </div>

                <div class="col-1-2">
                    <div class="field">
                        <label for="password_input">Salasõna</label>
                        <input type="password" id="password_input" name="password_input"
                               placeholder="parool - min 8 tähemärki"
                               required><span><?php echo $user->password_error; ?></span>
                    </div>
                </div>

                <div class="col-1-2">
                    <div class="field">
                        <label for="confirm_password_input">Salasõna</label>
                        <input type="password" id="confirm_password_input" name="confirm_password_input"
                               placeholder="korrake parooli"
                               required>
                        <span><?php echo $user->confirm_password_error; ?></span>
                    </div>
                </div>
                <button type="submit" class="btn submit comment-submit">Loo kasutaja</button>
            </form>
        </div>
    </div>
</main>
</body>
</html>

