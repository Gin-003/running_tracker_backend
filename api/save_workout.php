<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../models/Workout.php';
include_once '../middleware/auth.php';

// Verify JWT token
$user_data = verifyJWT();

$database = new Database();
$db = $database->getConnection();

$workout = new Workout($db);

$data = json_decode(file_get_contents("php://input"));

if(
    !empty($data->distance) &&
    !empty($data->duration) &&
    !empty($data->average_speed) &&
    !empty($data->calories_burned) &&
    !empty($data->start_location) &&
    !empty($data->end_location)
) {
    // Set user_id from JWT token
    $workout->user_id = $user_data['id'];
    $workout->distance = $data->distance;
    $workout->duration = $data->duration;
    $workout->average_speed = $data->average_speed;
    $workout->calories_burned = $data->calories_burned;
    $workout->start_location = $data->start_location;
    $workout->end_location = $data->end_location;

    if($workout->create()) {
        http_response_code(201);
        echo json_encode(array("message" => "Workout was created."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to create workout."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to create workout. Data is incomplete."));
}
?> 