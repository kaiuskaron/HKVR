<?php
include 'app.php';
$data = json_decode(file_get_contents('php://input'), true);
if ($data && $data['id']) {
    $db = new PDO("mysql:host=localhost;dbname=" . $_ENV['DB_DATABASE'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);
    $sql = "update uudised set deleted=true where id=?";
    $stmt = $db->prepare($sql);
    if ($stmt) {
        echo $stmt->execute([$data['id']]);
    }
}
