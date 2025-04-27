<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
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

// Use user_id from JWT token
$user_id = $user_data['id'];

$stmt = $workout->read($user_id);
$num = $stmt->rowCount();

if($num > 0) {
    $workouts_arr = array();
    $workouts_arr["records"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $workout_item = array(
            "id" => $id,
            "user_id" => $user_id,
            "distance" => $distance,
            "duration" => $duration,
            "average_speed" => $average_speed,
            "calories_burned" => $calories_burned,
            "start_location" => $start_location,
            "end_location" => $end_location,
            "created_at" => $created_at
        );

        array_push($workouts_arr["records"], $workout_item);
    }

    http_response_code(200);
    echo json_encode($workouts_arr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "No workouts found."));
}
?> 