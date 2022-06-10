<?php

const UPLOAD_DIR = '/home/kaius.karon/public_html/vr/uploads/';

class Image
{
    public $id = '';
    public $name = '';
    public $db_name = '';
    public $tmp_name = '';
    public $alt = '';
    public $privacy = '';
    public $author = '';
    public $error = null;
    public $rate = 0;
}

class Gallery
{


    private $db;
    private $minWidth = 400;
    private $minHeight = 300;
    private $maxFileSize = 1024 * 1024 * 1.2;
    public $error;
    public $images = [];


    public function __construct() {
        try {
            $this->db = new PDO("mysql:host=localhost;dbname=" . $_ENV['DB_DATABASE'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            return;
        }
    }

    public function uploadImage(): array {
        $this->error = '';
        if (isset($_FILES['image']) && is_array($_FILES['image']['tmp_name'])) {
            foreach ($_FILES['image']['tmp_name'] as $index => $file) {
                $uplFile = new Image();
                if (is_uploaded_file($file)) {
                    $uplFile->name = $_FILES['image']['name'][$index];
                    if ($_FILES['image']['size'][$index] < $this->maxFileSize) {
                        $uplFile->alt = $_POST['title'][$index];
                        $uplFile->privacy = $_POST['priva' . $index];
                        $imgTest = getimagesize($file);
                        if ($imgTest !== false) {
                            if ($this->checkSize($imgTest)) {
                                $uplFile->tmp_name = $_FILES['image']['tmp_name'][$index];
                                $this->handleUpload($imgTest, $uplFile);
                                if (empty($uplFile->error)) {
                                    $this->saveDb($uplFile);
                                }
                            } else {
                                $uplFile->error = 'Fail on liiga väike ';
                            }
                        }
                    } else {
                        $uplFile->error = 'Fail on liiga suur, max lubatud suurus on ' . round($this->maxFileSize / (1024 * 1024), 0) . 'Mb';
                    }
                } else {
                    $uplFile->error = 'Faili - ei õnnestunud üles laadida!';
                }
                $this->images[] = $uplFile;
            }
        } else {
            $this->error = 'Palun vali pildid!';
        }
        return $this->images;
    }

    public function fetchThumbs() {
        $sql = 'select g.id, g.name, g.alt, g.view_count, concat(u.firstname," ",u.lastname) as author, avg(pr.rating) as rate
           from gallery g
           join users u on g.user_id = u.id 
           left join photo_rating pr on g.id = pr.photoId
           where privacy>=?';

        $privacy = 2;
        if (isset($_SESSION['user_id'])) {
            $privacy = 1;
            $sql .= ' or g.user_id = '.$_SESSION['user_id'];
        }
        $sql .= ' group by g.id';

        //where not n.deleted and (n.expires > now() or n.expires is null)
        //order by ' . $orderBy . ' ' . $orderDir . ' limit ' . $take . ' offset ' . $skip;
        $sth = $this->db->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);

        $sth->execute([$privacy]);
        return $sth->fetchAll(PDO::FETCH_CLASS, 'Image');
    }

    public function getImage() {

    }

    private function handleUpload($imgTest, $uplFile) {
        $extension = image_type_to_extension($imgTest[2]);
        $uplFile->db_name = uniqid('vr_') . $extension;
        if (!move_uploaded_file($uplFile->tmp_name, UPLOAD_DIR . $uplFile->db_name)) {
            $uplFile->error = 'Üleslaadimine ebaõnnestus';
        } else { // resize
            $this->resize($uplFile->db_name, 'thumbs/', 200, 200, $imgTest, true);
        }
    }

    private function checkSize($info): bool {
        return $info[0] > $this->minWidth && $info[1] > $this->minHeight;
    }

    private function resize($orig, $dest, $maxWidth, $maxHeight, $imgInfo, $keepAspectRatio) {
        $original_width = $imgInfo[0];
        $original_height = $imgInfo[1];
        if (!$keepAspectRatio) {
            $new_width = $maxWidth;
            $new_height = $maxHeight;
        } else {
            $ratio_orig = $original_width / $original_height;
            if ($maxWidth / $maxHeight > $ratio_orig) {
                $new_height = $maxHeight;
                $new_width = $maxHeight * $ratio_orig;
            } else {
                $new_width = $maxWidth;
                $new_height = $maxHeight / $ratio_orig;
            }
        }
        if ($imgInfo[2] === IMAGETYPE_GIF) {
            $imgt = "ImageGIF";
            $imgcreatefrom = "ImageCreateFromGIF";
        } elseif ($imgInfo[2] === IMAGETYPE_JPEG) {
            $imgt = "ImageJPEG";
            $imgcreatefrom = "ImageCreateFromJPEG";
        } elseif ($imgInfo[2] === IMAGETYPE_PNG) {
            $imgt = "ImagePNG";
            $imgcreatefrom = "ImageCreateFromPNG";
        }
        if ($imgt && $imgcreatefrom) {
            $old_image = $imgcreatefrom(UPLOAD_DIR . $orig);
            $new_image = imagecreatetruecolor($new_width, $new_height);
            imagecopyresampled($new_image, $old_image, 0, 0, 0, 0, $new_width, $new_height, $original_width, $original_height);
            $imgt($new_image, UPLOAD_DIR . $dest . $orig);
        }
    }

    private function saveDb(Image $image) {
        $sql = "insert into gallery (name, alt, privacy, user_id, view_count, title) values (?,?,?,?,?,?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$image->db_name, $image->alt, $image->privacy, 3, 0, $image->name]);
    }

    private function watermark($watermark) {
//praegu selline usalduslik, eeldame, et on pilt
        $watermark_file_type = strtolower(pathinfo($watermark, PATHINFO_EXTENSION));
        $watermark_image = $this->create_image_from_file($watermark, $watermark_file_type);
        $watermark_w = imagesx($watermark_image);
        $watermark_h = imagesy($watermark_image);
        $watermark_x = imagesx($this->new_temp_image) - $watermark_w - 10;
        $watermark_y = imagesy($this->new_temp_image) - $watermark_h - 10;
        imagecopy($this->new_temp_image, $watermark_image, $watermark_x, $watermark_y, 0, 0, $watermark_w, $watermark_h);
        imagedestroy($watermark_image);
    }

}