
<?php


include ('config.php');

date_default_timezone_set('Asia/Manila');

class global_class extends db_connect
{
    public function __construct()
    {
        $this->connect();
    }


    public function SignUp($full_name, $email, $password){
        // Check if the email already exists
        $checkQuery = "SELECT user_id FROM `user` WHERE `user_email` = ?";
        $checkStmt = $this->conn->prepare($checkQuery);
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            return [
                'success' => false,
                'message' => 'Email already registered.'
            ];
        }

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert the new user
        $query = "INSERT INTO `user`(`user_fullname`, `user_email`, `user_password`) 
                VALUES (?, ?, ?)";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sss", $full_name,$email, $hashedPassword);

        if ($stmt->execute()) {
            return [
                'success' => true,
                'message' => 'Registration successful.'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Registration failed. Please try again.'
            ];
        }
    }






private function generateUniqueRoomCode($length = 6) {
    do {
        // Generate random alphanumeric code
        $code = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, $length);

        // Check if code already exists
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM `room` WHERE `room_code` = ?");
        $stmt->bind_param("s", $code);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
    } while ($count > 0); // repeat if code exists

    return $code;
}

public function createRoom($roomName, $roomDescription, $roomImageFileName, $user_id) {
    $room_code = $this->generateUniqueRoomCode(); // Generate unique code

    // Use ? placeholders for prepared statements
    $query = "INSERT INTO `room` (`room_creator_user_id`, `room_banner`, `room_name`, `room_description`, `room_code`) 
              VALUES (?, ?, ?, ?, ?)";

    $stmt = $this->conn->prepare($query);
    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("issss", $user_id, $roomImageFileName, $roomName, $roomDescription, $room_code);

    $result = $stmt->execute();

    if (!$result) {
        $stmt->close();
        return false;
    }

    $inserted_id = $this->conn->insert_id; 
    $stmt->close();

    return $inserted_id; 
}




public function getAllRooms() {
    $query = "SELECT * FROM `room`";
    $stmt = $this->conn->prepare($query);
    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $rooms = [];
    while ($row = $result->fetch_assoc()) {
        $rooms[] = $row;
    }

    $stmt->close();
    return $rooms;
}








    
    
    public function Login($email, $password)
{
    $query = $this->conn->prepare("SELECT * FROM `user` WHERE `user_email` = ?");
    $query->bind_param("s", $email);

    if ($query->execute()) {
        $result = $query->get_result();
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['user_password'])) {
                // 🔍 Check if inactive
                if ($user['user_status'] == 0) {
                    $query->close();
                    return [
                        'success' => false,
                        'message' => 'Your account is not active.'
                    ];
                }

                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['user_id'] = $user['user_id'];

                $query->close();
                return [
                    'success' => true,
                    'message' => 'Login successful.',
                    'data' => [
                        'user_id' => $user['user_id']
                    ]
                ];
            } else {
                $query->close();
                return ['success' => false, 'message' => 'Incorrect password.'];
            }
        } else {
            $query->close();
            return ['success' => false, 'message' => 'User not found.'];
        }
    } else {
        $query->close();
        return ['success' => false, 'message' => 'Database error during execution.'];
    }
}





    



}