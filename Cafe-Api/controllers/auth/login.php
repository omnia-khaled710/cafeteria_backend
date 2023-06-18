<?php



require_once '../../vendor/autoload.php';

use Firebase\JWT\JWT;

require('../../handle.php');
require('../../cors.php');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');

$db = new Database();


// handle login
if ($_SERVER["REQUEST_METHOD"] === "POST") {



    $data = json_decode(file_get_contents('php://input'));



    $email = $data->email;
    $password = $data->password;




    // Email validation:
    if (empty($email)) {
        $errors[] = "Email is required";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    // Password validation:
    if (empty($password)) {
        $errors[] = "Password is required";
    } else if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters";
    }

    // Check for errors:
    if (!empty($errors)) {
        // Display error messages to user:
        foreach ($errors as $error) {
            http_response_code(404);
            echo json_encode(["errors" => $error]);
            exit();
        }
    }

    // retrieve user from database

    $stmt = $db->getrow('users', "SELECT * FROM users WHERE email = ?", [$email]);

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        http_response_code(401);
        echo json_encode(["error" => "Invalid email"]);
        exit();
    }



    // verify password
    if ($password !== $result["password"]) {
        http_response_code(401);
        echo json_encode(["error" => "Invalid password"]);
        exit();
    }

    // generate JWT token

    $jwt_secret = 'verygoodsecretkey';

    $jwt_payload = [
        'id' => $result['id'],
        'username' => $result['name'],
        'email' => $result['email'],
        'isAdmin' => $result['is_admin']
    ];

    $jwt_token = JWT::encode($jwt_payload, $jwt_secret, 'HS256');

    echo json_encode(["token" => $jwt_token, "user" => $jwt_payload]);

    exit();
}

// handle other requests
http_response_code(404);
echo json_encode(["error" => "Route not found"]);
exit();
