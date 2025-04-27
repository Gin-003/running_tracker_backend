<?php
// JWT Authentication Middleware
function verifyJWT() {
    // Get headers
    $headers = apache_request_headers();
    
    // Check if Authorization header exists
    if(isset($headers['Authorization'])) {
        $token = str_replace('Bearer ', '', $headers['Authorization']);
        
        // Decode token
        $decoded_token = json_decode(base64_decode($token), true);
        
        // Check if token is valid
        if($decoded_token) {
            // Check if token is expired
            if($decoded_token['exp'] > time()) {
                // Token is valid and not expired
                return $decoded_token['data'];
            } else {
                http_response_code(401);
                echo json_encode(array("message" => "Token has expired."));
                exit();
            }
        } else {
            http_response_code(401);
            echo json_encode(array("message" => "Invalid token."));
            exit();
        }
    } else {
        http_response_code(401);
        echo json_encode(array("message" => "No token provided."));
        exit();
    }
}
?> 