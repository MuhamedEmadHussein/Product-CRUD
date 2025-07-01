<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *"); // Allow external requests
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Allow-Headers: Content-Type");

$existingNames = ["John", "Jane", "Jim", "Jill"];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode(["names" => $existingNames]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['suggesstion'])) {
        $name = $data['suggesstion'];
        $suggestions = array_filter($existingNames, function ($existingName) use ($name) {
            return stripos($existingName, $name) !== false;
        });

        echo json_encode(["suggestions" => array_values($suggestions) ?: ["No suggestion"]]);
    } else {
        echo json_encode(["error" => "No name provided"]);
    }
    exit;
}
?>
