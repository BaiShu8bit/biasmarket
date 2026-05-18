<?php
require_once(__DIR__ . '/../../BBDD/connection.php');

header('Content-Type: application/json');

try {

    $db = ConnectionDB::getInstance();

    $buscar = $_POST['buscar'] ?? '';

    if (!$buscar) {
        echo json_encode([]);
        exit;
    }

    $sql = "
        SELECT photocardId, name, watermarked_image_url
        FROM photocards
        WHERE name LIKE :buscar
        LIMIT 10
    ";

    $stmt = $db->prepare($sql);
    $stmt->bindValue(':buscar', '%' . $buscar . '%');
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($result);
    exit;

} catch (Exception $e) {

    echo json_encode([
        "error" => $e->getMessage()
    ]);
}