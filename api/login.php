<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../models/User.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

$data = json_decode(file_get_contents("php://input"));

// Validate input
if(
    !empty($data->email) &&
    !empty($data->password)
) {
    // Set email property
    $user->email = $data->email;
    
    // Check if email exists
    if($user->emailExists()) {
        // Verify password
        if(password_verify($data->password, $user->password)) {
            // Generate JWT token
            $token = generateJWT($user->id, $user->username);
            
            http_response_code(200);
            echo json_encode(array(
                "message" => "Login successful.",
                "user_id" => $user->id,
                "username" => $user->username,
                "token" => $token
            ));
        } else {
            http_response_code(401);
            echo json_encode(array("message" => "Invalid password."));
        }
    } else {
        http_response_code(404);
        echo json_encode(array("message" => "User not found."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to login. Data is incomplete."));
}

// Function to generate JWT token
function generateJWT($user_id, $username) {
    $secret_key = "your_secret_key"; // Change this to a secure secret key
    $issuer_claim = "running_tracker"; // this can be the domain name
    $audience_claim = "running_tracker_users";
    $issuedat_claim = time(); // issued at
    $notbefore_claim = $issuedat_claim; // not before
    $expire_claim = $issuedat_claim + 3600; // expire time in seconds (1 hour)
    
    $token = array(
        "iss" => $issuer_claim,
        "aud" => $audience_claim,
        "iat" => $issuedat_claim,
        "nbf" => $notbefore_claim,
        "exp" => $expire_claim,
        "data" => array(
            "id" => $user_id,
            "username" => $username
    ));
    
    // Encode the token
    $jwt = base64_encode(json_encode($token));
    
    return $jwt;
}
?> 