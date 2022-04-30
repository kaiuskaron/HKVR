<?php

class User
{

    public $notice = null;
    public $firstname = null;
    public $surname = null;
    public $email = null;
    public $gender = null;
    public $birth_date = null;
    public $firstname_error = null;
    public $surname_error = null;
    public $birth_date_error = null;
    public $gender_error = null;
    public $email_error = null;
    public $password_error = null;
    public $confirm_password_error = null;

    private $_signIn = false;
    private $db = null;
    public $dbError;


    public function __construct() {
        try {
            $this->db = new PDO("mysql:host=localhost;dbname=" . $_ENV['DB_DATABASE'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                if (isset($_POST['first_name_input'])) {
                    $this->addUser();
                }
                if (isset($_POST['username'])) {
                    $this->_signIn = $this->signIn();
                }
            }
        } catch (PDOException $e) {
            $this->dbError = $e->getMessage();
            //echo $this->dbError;
            return;
        }
        if (isset($_GET['logout'])) {
            $this->_signIn = false;
            session_unset();
        }
        if (isset($_SESSION['user_id'])) {
            $this->_signIn = true;
        }
    }

    public function isLoggedIn() {
        return $this->_signIn;
    }

    public function addUser() {
        //kontrollin sisestusi
        //eesnimi
        if (isset($_POST["first_name_input"]) and !empty($_POST["first_name_input"])) {
            $this->firstname = (filter_var($_POST["first_name_input"], FILTER_SANITIZE_STRING));
            if (empty($this->firstname)) {
                $this->firstname_error = "Palun sisesta oma eesnimi!";
            }
        } else {
            $firstname_error = "Palun sisesta oma eesnimi!";
        }

        //perekonnanimi
        if (isset($_POST["surname_input"]) and !empty($_POST["surname_input"])) {
            $this->surname = (filter_var($_POST["surname_input"], FILTER_SANITIZE_STRING));
            if (empty($this->surname)) {
                $this->surname_error = "Palun sisesta oma perekonnanimi!";
            }
        } else {
            $this->surname_error = "Palun sisesta oma perekonnanimi!";
        }

        //sugu
        if (isset($_POST["gender_input"]) and !empty($_POST["gender_input"])) {
            $this->gender = filter_var($_POST["gender_input"], FILTER_VALIDATE_INT);
            if ($this->gender != 1 and $this->gender != 2) {
                $this->gender_error = "Palun märgi oma sugu!";
            }
        } else {
            $this->gender_error = "Palun märgi oma sugu!";
        }

        //email
        if (isset($_POST["email_input"]) and !empty($_POST["email_input"])) {
            $this->email = (filter_var($_POST["email_input"], FILTER_VALIDATE_EMAIL));
            if (empty($this->email)) {
                $this->email_error = "Palun sisesta oma e-posti aadress!";
            }
        } else {
            $this->email_error = "Palun sisesta oma e-posti aadress!";
        }

        //b-date
        if (isset($_POST["birth_day_input"]) and !empty($_POST["birth_day_input"])) {
            $birth_day = filter_var($_POST["birth_day_input"], FILTER_VALIDATE_INT);
            if ($birth_day < 1 or $birth_day > 31) {
                $birth_day_error = "Palun vali sأ¼nni pأ¤ev!";
            }
        } else {
            $birth_day_error = "Palun vali sأ¼nni pأ¤ev!";
        }
        //kuu
        if (isset($_POST["birth_month_input"]) and !empty($_POST["birth_month_input"])) {
            $birth_month = filter_var($_POST["birth_month_input"], FILTER_VALIDATE_INT);
            if ($birth_month < 1 or $birth_month > 12) {
                $birth_month_error = "Palun vali sأ¼nni kuu!";
            }
        } else {
            $birth_month_error = "Palun vali sأ¼nni kuu!";
        }
        //aasta
        if (isset($_POST["birth_year_input"]) and !empty($_POST["birth_year_input"])) {
            $birth_year = filter_var($_POST["birth_year_input"], FILTER_VALIDATE_INT);
            if ($birth_year < date("Y") - 110 or $birth_year > date("Y") - 13) {
                $birth_year_error = "Palun vali sأ¼nni aasta!";
            }
        } else {
            $birth_year_error = "Palun vali sأ¼nni aasta!";
        }
        $this->birth_date = $birth_year . '-' . $birth_month . '-' . $birth_day;

        //parool
        if (isset($_POST["password_input"]) and !empty($_POST["password_input"])) {
            if (strlen($_POST["password_input"]) < 8) {
                $this->password_error = "Sisestatud salasõna on liiga lühike!";
            }
        } else {
            $this->password_error = "Palun sisesta salasأµna!";
        }
        //parooli kordus
        if (isset($_POST["confirm_password_input"]) and !empty($_POST["confirm_password_input"])) {
            if ($_POST["confirm_password_input"] != $_POST["password_input"]) {
                $this->confirm_password_error = "Sisestatud salasõnad on erinevad!";
            }
        } else {
            $this->confirm_password_error = "Palun sisesta salasõna kaks korda!";
        }

        if (empty($this->firstname_error) and empty($this->surname_error) and empty($this->birth_date_error) and empty($this->gender_error) and empty($this->email_error) and empty($this->password_error) and empty($this->confirm_password_error)) {
            $sql = "INSERT INTO users (firstname, lastname, birthdate, gender, email, password) VALUES (?,?,?,?,?,?)";
            $stmt = $this->db->prepare($sql);
            $pwd_hash = password_hash($_POST['password_input'], PASSWORD_BCRYPT, ['cost' => 12]);
            return $stmt->execute([$this->firstname, $this->surname, $this->birth_date, $this->gender, $this->email, $pwd_hash]) ? 1 : -1;
        }
    }

    public function signIn() {
        $this->_signIn = false;
        $sql = "SELECT id, password FROM users where email=:email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $_POST['username']]);
        //print_r($_POST);
        $row = $stmt->fetch();
        //
        //if (password_verify($_POST['password'], $row['password'])) {
            $this->_signIn = true;
            $_SESSION['user_id'] = $row['id'];
            //print_r($row);
        //}
    }
}