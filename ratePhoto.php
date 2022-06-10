<?php
require 'app.php';

$data = json_decode(file_get_contents('php://input'), true);
if ($data && $data['imgId'] && $data['rate']) {
    $db = new PDO("mysql:host=localhost;dbname=" . $_ENV['DB_DATABASE'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);
    $sql = "insert into photo_rating (photoId, userId, rating) VALUES (?,?,?)";
    $stmt = $db->prepare($sql);
    if ($stmt) {
        $stmt->execute([$data['imgId'], $_SESSION['user_id'], $data['rate']]);
        $sql = "select avg(rating) as rating from photo_rating where photoId=?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$data['imgId']]);
        echo json_encode($stmt->fetch(PDO::FETCH_OBJ));
    }
}
