<?php
header('Content-Type: application/json');

try {
    // Get the album_id parameter
    $albumId = $_GET['album_id'] ?? null;

    // Read the JSON file
    $jsonFile = __DIR__ . '/../../src/data/photocards.json';
    $jsonContent = file_get_contents($jsonFile);
    $data = json_decode($jsonContent, true);
    $photocards = $data['photocards'] ?? [];
    
    // Filter by album_id if provided
    if ($albumId) {
        $filteredPhotocards = array_filter($photocards, function ($card) use ($albumId) {
            return $card['album_id'] === $albumId;
        });
        $result = array_values($filteredPhotocards); // Re-index array
    } else {
        $result = $photocards;
    }

    echo json_encode($result);
    exit;
} catch (Exception $e) {
    echo json_encode([
        "error" => "Error en API photocards",
        "details" => $e->getMessage()
    ]);
    error_log("ERROR: " . $e->getMessage());
}
