    <?php

    require('../../cors.php');

    require('../../handle.php');





    $db = new Database();

    // handle registration
    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $data = json_decode(file_get_contents('php://input'));


        //grab data from body
        $username = $data->username;
        $email = $data->email;
        $password = $data->password;

        // Name validation:
        if (empty($username)) {
            $errors[] = "username is required";
        } else if (strlen($username) < 3 || strlen($username) > 50) {
            $errors[] = "Name must be between 2 and 50 characters";
        }

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

        $passwordHashed = password_hash($password, PASSWORD_BCRYPT);



        // check if username or email already exist
        $stmt = $db->getrow('users', "SELECT * FROM users WHERE email = ? ", [$email]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            http_response_code(400);
            echo json_encode(["error" => "Username or email already exists"]);
            exit();
        }

        // insert new user into database

        $new_user = $db->insertRow('users', 'INSERT INTO users (name, email, password) VALUES (? , ? , ?)', [$username, $email, $passwordHashed]);


        http_response_code(201);
        echo json_encode(["message" => "User created successfully",]);
        exit();
    }

    // handle other requests
    http_response_code(404);
    echo json_encode(["error" => "Route not found"]);
    exit();
