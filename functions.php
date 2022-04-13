<?php

class NewsItem
{
    public $header = '';
    public $body = '';
    public $expires = '';
    public $created = '';
    public $deleted = false;
    public $user_id = 0;

    public function __construct(array $array = null) {
        if ($array) {
            $this->header = $array['header'];
            $this->body = $array['body'];
            $this->expires = $array['expires'];
            $this->created = Date('now');
            $this->user_id = 1;
        }
    }

    public function excerpt() {
        if (strlen($this->body) < 100) {
            return $this->body;
        } else {
            $new = wordwrap($this->body, 98);
            $new = explode("\n", $new);
            return $new[0] . '...';
        }
    }
}

class RifNews
{

    public $db;
    public $db_error;

    public function __construct() {
        $this->db_error = '';
    }

    /**
     * @return bool
     */
    public function connectDb(): bool {
        try {
            $this->db = new PDO("mysql:host=localhost;dbname=" . $_ENV['DB_DATABASE'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE); // fix limit binding
            return true;
        } catch (PDOException $e) {
            $this->db_error = $e->getMessage();
            return false;
        }
    }

    /**
     * @param NewsItem $data
     * @return bool
     */
    public function insert(NewsItem $data): bool {
        if (!$this->db_error) {
            if ($data->expires == '') {
                $data->expires = null;
            }
            $sql = "INSERT INTO uudised (header, body, expires, user_id) VALUES (?,?,?,?)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$data->header, $data->body, $data->expires, $data->user_id]);
        }
        return false;
    }

    /**
     * @param string $orderBy
     * @param string $orderDir
     * @param int $take
     * @param int $skip
     * @return mixed
     */
    public function fetch(string $orderBy = 'id', string $orderDir = 'DESC', int $take = 5, int $skip = 0) {
        $sql = 'select * from uudised where not deleted order by :ord ' . $orderDir . ' limit :take offset :skip';
        $sth = $this->db->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
        $sth->execute([
            ':ord'  => $orderBy,
            ':take' => $take,
            ':skip' => $skip
        ]);
        return $sth->fetchAll(PDO::FETCH_CLASS, 'NewsItem');
    }


}
