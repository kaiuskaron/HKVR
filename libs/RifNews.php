<?php

class RifNews
{
    private $db;
    public $dbError;
    public $currentPage;
    public $ttlNewsCount;
    public $sort;

    public function __construct() {
        $this->dbError = '';
        $this->connectDb();
        if (isset($_GET['page'])) {
            $this->currentPage = intval($_GET['page']);
        } else {
            $this->currentPage = 1;
        }
        if (isset($_GET['sort'])) {
            $this->sort = intval($_GET['sort']);
        } else {
            $this->sort = 0;
        }

    }

    /**
     * @return void
     */
    private function connectDb(): void {
        try {
            $this->db = new PDO("mysql:host=localhost;dbname=" . $_ENV['DB_DATABASE'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->ttlNewsCount = $this->_ttlNewsCount();
            return;
        } catch (PDOException $e) {
            $this->dbError = $e->getMessage();
            return;
        }
    }

    /**
     * @param NewsItem $data
     * @return bool|null
     */
    public function insert(NewsItem $data): ?bool {
        $uploadDir = 'uploads/';
        $uploadFile = null;
        if (isset($_FILES) && is_uploaded_file($_FILES['file']['tmp_name'])) {
            $uploadFile = $uploadDir . basename($_FILES['file']['name']);
            if (!move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
                $uploadFile = null;
            }
        }
        if (!$this->dbError) {
            if ($data->expires == '') {
                $data->expires = null;
            }
            $sql = "INSERT INTO uudised (header, body, expires, user_id, image) VALUES (?,?,?,?,?)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$data->header, $data->body, $data->expires, $data->user_id, $uploadFile]) ? 1 : -1;
        }
        return null;
    }

    /**
     * @param int $take
     * @return mixed
     */
    public function fetch(int $take = 5) {
        if ($this->currentPage < 2) {
            $skip = 0;
        } else {
            $skip = ($this->currentPage - 1) * $take;
        }
        switch ($this->sort) {
            case 1:
                $orderBy = 'created';
                $orderDir = 'desc';
                break;
            case 2:
                $orderBy = 'created';
                $orderDir = 'asc';
                break;
            case 3:
                $orderBy = 'header';
                $orderDir = 'asc';
                break;
            default:
                $orderBy = 'id';
                $orderDir = 'desc';
                break;
        }
        $sql = 'select * from uudised where not deleted and (expires > now() or expires is null) order by ' . $orderBy . ' ' . $orderDir . ' limit ' . $take . ' offset ' . $skip;
        $sth = $this->db->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
        $sth->execute();
        return $sth->fetchAll(PDO::FETCH_CLASS, 'NewsItem');
    }

    /**
     * @return mixed
     */
    private function _ttlNewsCount() {
        $sql = 'select count(id) from uudised where not deleted';
        $sth = $this->db->prepare($sql);
        $sth->execute();
        return $sth->fetchColumn();
    }

    /**
     * @return mixed
     */
    public function getArticle($id) {
        $sql = 'select * from uudised where not deleted and id = :id';
        $sth = $this->db->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
        $sth->execute(['id' => $id]);
        $sth->setFetchMode(PDO::FETCH_CLASS, 'NewsItem');
        return $sth->fetch();
    }
}