<?php

class Image
{
    public $title = '';
    public $alt = '';
    public $view_count = 0;
    public $author = '';
    public $created_at = '';
}

class Gallery
{

    private $db;
    private $minWidth = 400;
    private $minHeight = 300;
    private $maxFileSize = 1024 * 1024 * 1.2;
    public $error;


    public function __construct() {
        try {
            $this->db = new PDO("mysql:host=localhost;dbname=" . $_ENV['DB_DATABASE'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            return;
        }
    }

    public function uploadImage() {
        print_r($_POST);
        if (isset($_FILES['image']) && is_array($_FILES['image']['tmp_name'])) {
            foreach ($_FILES['image']['tmp_name'] as $index => $file) {
                if (is_uploaded_file($file)) {
                    if ($_FILES['image']['size'][$index] < $this->maxFileSize) {
                        //if ($this->checkSize($file)) {
                            $this->handleUpload($file, $index);
                        //} else {
                        //    $this->error = 'Fail on liiga väike '.;
                        //}
                    } else {
                        $this->error = 'Fail on liiga suur, max lubatud suurus on ' . round($this->maxFileSize/(1024*1024),0).'Mb';
                    }

                }else {
                    $this->error = 'Pilti ei õnnestunud üles laadida!';
                }
            }
        } else {
            $this->error = 'Palun vali pilt!';
        }
    }

    public function fetchThumbs() {
        $sql = 'select g.title, g.alt, g.view_count, concat(u.firstname," ",u.lastname) as author 
           from gallery g
           join users u on g.user_id = u.id';
        //where not n.deleted and (n.expires > now() or n.expires is null)
        //order by ' . $orderBy . ' ' . $orderDir . ' limit ' . $take . ' offset ' . $skip;
        $sth = $this->db->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
        $sth->execute();
        return $sth->fetchAll(PDO::FETCH_CLASS, 'Image');
    }

    public function getImage() {

    }

    private function handleUpload($file, $index) {
        $imgTest = getimagesize($file);
        if ($imgTest !== false) {

            $extension = image_type_to_extension($imgTest[2]);
            $filename = uniqid('vr_') . $extension;
            echo $filename . "<br>";
            /*if (!move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
                $uploadFile = null;
            }*/
        }
    }

    private function checkSize($info): bool {
        return $info[0] > $this->minWidth && $info[1] > $this->minHeight;
    }

}