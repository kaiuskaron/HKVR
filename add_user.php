<?php

require_once("fnc_general.php");
require_once("fnc_user.php");

$notice = null;
$firstname = null;
$surname = null;
$email = null;
$gender = null;
$birth_month = null;
$birth_year = null;
$birth_day = null;
$birth_date = null;
$month_names_et = ["jaanuar", "veebruar", "mأ¤rts", "aprill", "mai", "juuni","juuli", "august", "september", "oktoober", "november", "detsember"];

//muutujad vأµimalike veateadetega
$firstname_error = null;
$surname_error = null;
$birth_month_error = null;
$birth_year_error = null;
$birth_day_error = null;
$birth_date_error = null;
$gender_error = null;
$email_error = null;
$password_error = null;
$confirm_password_error = null;

//kontrollime sisestust
if($_SERVER["REQUEST_METHOD"] === "POST"){
    if(isset($_POST["user_data_submit"])){
        //kontrollin sisestusi
        //eesnimi
        if(isset($_POST["first_name_input"]) and !empty($_POST["first_name_input"])){
            $firstname = test_input(filter_var($_POST["first_name_input"], FILTER_SANITIZE_STRING));
            if(empty($firstname)){
                $firstname_error = "Palun sisesta oma eesnimi!";
            }
        } else {
            $firstname_error = "Palun sisesta oma eesnimi!";
        }

        //perekonnanimi
        if(isset($_POST["surname_input"]) and !empty($_POST["surname_input"])){
            $surname = test_input(filter_var($_POST["surname_input"], FILTER_SANITIZE_STRING));
            if(empty($surname)){
                $surname_error = "Palun sisesta oma perekonnanimi!";
            }
        } else {
            $surname_error = "Palun sisesta oma perekonnanimi!";
        }

        //sugu
        if(isset($_POST["gender_input"]) and !empty($_POST["gender_input"])){
            $gender = filter_var($_POST["gender_input"], FILTER_VALIDATE_INT);
            if($gender != 1 and $gender != 2){
                $gender_error = "Palun mأ¤rgi oma sugu!";
            }
        } else {
            $gender_error = "Palun mأ¤rgi oma sugu!";
        }


    }//if isset lأµppeb
}//if request_method lأµppeb
?>

<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="utf-8">
    <title>Veebiprogrammeerimine</title>
</head>
<body>
<h1>Veebiprogrammeerimine</h1>
<p>See leht on valminud أµppetأ¶أ¶ raames ja ei sisalda mingisugust tأµsiseltvأµetavat sisu!</p>
<p>أ•ppetأ¶أ¶ toimus <a href="https://www.tlu.ee/dt">Tallinna أœlikooli Digitehnoloogiate instituudis</a>.</p>
<hr>
<h2>Loo endale kasutajakonto</h2>

<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label for="first_name_input">Eesnimi:</label><br>
    <input name="first_name_input" id="first_name_input" type="text" value="<?php echo $firstname; ?>"><span><?php echo $firstname_error; ?></span><br>
    <label for="surname_input">Perekonnanimi:</label><br>
    <input name="surname_input" id="surname_input" type="text" value="<?php echo $surname; ?>"><span><?php echo $surname_error; ?></span>
    <br>

    <input type="radio" name="gender_input" id="gender_input_1" value="2" <?php if($gender == "2"){echo " checked";} ?>><label for="gender_input_1">Naine</label>
    <input type="radio" name="gender_input" id="gender_input_2" value="1" <?php if($gender == "1"){echo " checked";} ?>><label for="gender_input_2">Mees</label><br>
    <span><?php echo $gender_error; ?></span>
    <br>



    <label for="email_input">E-mail (kasutajatunnus):</label><br>
    <input type="email" name="email_input" id="email_input" value="<?php echo $email; ?>"><span><?php echo $email_error; ?></span><br>
    <label for="password_input">Salasأµna (min 8 tأ¤hemأ¤rki):</label><br>
    <input name="password_input" id="password_input" type="password"><span><?php echo $password_error; ?></span><br>
    <label for="confirm_password_input">Korrake salasأµna:</label><br>
    <input name="confirm_password_input" id="confirm_password_input" type="password"><span><?php echo $confirm_password_error; ?></span><br>
    <input name="user_data_submit" type="submit" value="Loo kasutaja"><span><?php echo $notice; ?></span>
</form>
<hr>
<p>Tagasi <a href="page.php">avalehele</a></p>

</body>
</html>