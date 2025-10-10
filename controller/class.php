
<?php


include ('config.php');

date_default_timezone_set('Asia/Manila');

class global_class extends db_connect
{
    public function __construct()
    {
        $this->connect();
    }

    public function saveFiles(int $user_id, int $classwork_id, array $uploadedFiles): array 
    {
        // Fetch existing files
        $stmt = $this->conn->prepare(
            "SELECT sw_files FROM submitted_classwork WHERE sw_classwork_id=? AND sw_user_id=?"
        );
        $stmt->bind_param("ii", $classwork_id, $user_id);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        // If record exists, get existing files; otherwise empty array
        $existing = ($res && !empty($res['sw_files'])) ? json_decode($res['sw_files'], true) : [];

        $merged = array_merge($existing, $uploadedFiles);
        $mergedJson = json_encode($merged);

        if ($res) { // Update existing record
            $stmt = $this->conn->prepare(
                "UPDATE submitted_classwork SET sw_files=? WHERE sw_classwork_id=? AND sw_user_id=?"
            );
            $stmt->bind_param("sii", $mergedJson, $classwork_id, $user_id);
            $stmt->execute();
            $stmt->close();
        } else { // Insert new record
            $stmt = $this->conn->prepare(
                "INSERT INTO submitted_classwork (sw_classwork_id, sw_user_id, sw_files, sw_status) VALUES (?,?,?,0)"
            );
            $stmt->bind_param("iis", $classwork_id, $user_id, $mergedJson);
            $stmt->execute();
            $stmt->close();
        }

        return $merged;
    }


    /**
     * Update submission status (0 = Not Turned In, 1 = Turned In)
     */
    public function updateSwStatus($status, $user_id, $classwork_id) {
        $stmt = $this->conn->prepare("UPDATE submitted_classwork SET sw_status=? WHERE sw_classwork_id=? AND sw_user_id=?");
        $stmt->bind_param("iii", $status, $classwork_id, $user_id);
        $stmt->execute();

        $affectedRows = $stmt->affected_rows;
        $stmt->close();

        return $affectedRows > 0;
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
    $room_code = $this->generateUniqueRoomCode(); 

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



















public function CreateClasswork($title, $instructions, $fileName, $user_id, $room_id)
{
    // ✅ SQL query with placeholders, now includes room_id
    $query = "
        INSERT INTO `classwork` 
        (`classwork_title`, `classwork_instruction`, `classwork_file`, `classwork_by_user_id`, `classwork_room_id`) 
        VALUES (?, ?, ?, ?, ?)
    ";

    // ✅ Prepare statement
    $stmt = $this->conn->prepare($query);
    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    // ✅ Bind parameters (s = string, i = integer)
    $stmt->bind_param("sssii", $title, $instructions, $fileName, $user_id, $room_id);

    // ✅ Execute
    $result = $stmt->execute();

    if (!$result) {
        $stmt->close();
        return false;
    }

    // ✅ Get the inserted ID
    $inserted_id = $this->conn->insert_id;
    $stmt->close();

    return $inserted_id;
}




public function getAllRooms($user_id) {
    $query = "
        SELECT * 
        FROM `room`
        WHERE room_id NOT IN (
            SELECT room_id FROM room_members WHERE user_id = ?
        )
        ORDER BY room_id DESC
    ";

    $stmt = $this->conn->prepare($query);
    if (!$stmt) {
        die('Prepare failed: ' . $this->conn->error);
    }

    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $rooms = [];
    while ($row = $result->fetch_assoc()) {
        $rooms[] = $row;
    }

    $stmt->close();
    return $rooms;
}









public function get_rooms_members($room_id) {
    $query = "
        SELECT 
            rm.id AS membership_id,
            rm.room_id,
            rm.user_id,
            rm.date_joined,
            u.user_fullname,
            u.user_email
        FROM room_members AS rm
        INNER JOIN user AS u ON rm.user_id = u.user_id
        WHERE rm.room_id = ?
        ORDER BY rm.id ASC
    ";

    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("i", $room_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $members = [];
    while ($row = $result->fetch_assoc()) {
        $members[] = $row;
    }

    return $members;
}









public function getClassworkDetails($id) {
    $sql = "
        SELECT 
            c.classwork_id,
            c.classwork_title,
            c.classwork_instruction,
            c.classwork_file,
            u.user_fullname AS posted_by,
            DATE_FORMAT(c.created_at, '%M %e, %Y %h:%i %p') AS posted_time,
            r.room_name,
            sc.*
        FROM classwork c
        LEFT JOIN user u ON c.classwork_by_user_id = u.user_id
        LEFT JOIN room r ON c.classwork_room_id = r.room_id
        LEFT JOIN submitted_classwork sc ON sc.sw_classwork_id = c.classwork_id
        WHERE c.classwork_id = ?
        LIMIT 1
    ";

    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return false;
    }
}












public function get_all_created_works($room_id, $user_id) {
    $sql = "SELECT * FROM classwork 
            WHERE classwork_room_id = ? 
              AND classwork_by_user_id = ?
              AND classwork_status = 1
            ORDER BY created_at DESC";

    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("ii", $room_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Return all rows as an array
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        return [];
    }
}





public function getAllPendingClasswork($user_id, $room_id) {
    $query = "
        SELECT cw.*
        FROM classwork cw
        LEFT JOIN submitted_classwork sw 
            ON cw.classwork_id = sw.sw_classwork_id 
            AND sw.sw_user_id = ?
        WHERE cw.classwork_room_id = ?
            AND (sw.sw_id IS NULL OR sw.sw_status=0)
            AND cw.classwork_status = 1
    ";

    $stmt = $this->conn->prepare($query);
    if (!$stmt) {
        die('Prepare failed: ' . $this->conn->error);
    }

    $stmt->bind_param('ii', $user_id, $room_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $classworks = [];
    while ($row = $result->fetch_assoc()) {
        $classworks[] = $row;
    }

    $stmt->close();
    return $classworks;
}






public function getAllSubmittedClasswork_Joiner($user_id, $room_id) {
    $query = "
        SELECT cw.*
        FROM classwork cw
        LEFT JOIN submitted_classwork sw 
            ON cw.classwork_id = sw.sw_classwork_id 
            AND sw.sw_user_id = ?
        WHERE cw.classwork_room_id = ?
            AND (sw.sw_status=1)
            AND cw.classwork_status = 1
    ";

    $stmt = $this->conn->prepare($query);
    if (!$stmt) {
        die('Prepare failed: ' . $this->conn->error);
    }

    $stmt->bind_param('ii', $user_id, $room_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $classworks = [];
    while ($row = $result->fetch_assoc()) {
        $classworks[] = $row;
    }

    $stmt->close();
    return $classworks;
}






    public function getJoinedRooms($user_id) {
        $query = "
            SELECT r.room_name, r.room_code
            FROM room_members rm
            JOIN room r ON rm.room_id = r.room_id
            WHERE rm.user_id = ?
        ";

        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            return [
                'success' => false,
                'message' => 'Prepare failed: ' . $this->conn->error
            ];
        }

        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $rooms = [];
        while ($row = $result->fetch_assoc()) {
            $rooms[] = $row;
        }

        $stmt->close();

        return [
            'success' => true,
            'data' => $rooms
        ];
    }






public function getCreatedRooms($user_id) {
    $query = "
        SELECT room_id, room_name, room_code, room_banner, room_description, room_date_created
        FROM room
        WHERE room_creator_user_id = ?
        ORDER BY room_date_created DESC
    ";

    $stmt = $this->conn->prepare($query);
    if (!$stmt) {
        return [
            'success' => false,
            'message' => 'Prepare failed: ' . $this->conn->error
        ];
    }

    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $rooms = [];
    while ($row = $result->fetch_assoc()) {
        $rooms[] = $row;
    }

    $stmt->close();

    return [
        'success' => true,
        'data' => $rooms
    ];
}









public function getWorkResponses($classwork_id) {
    $query = "SELECT * from submitted_classwork
    LEFT JOIN classwork
    ON classwork.classwork_id = submitted_classwork.sw_classwork_id 
    LEFT JOIN user
    ON user.user_id = submitted_classwork.sw_user_id
    where submitted_classwork.sw_classwork_id = ?
    ";

    $stmt = $this->conn->prepare($query);
   

    $stmt->bind_param("i", $classwork_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $rooms = [];
    while ($row = $result->fetch_assoc()) {
        $rooms[] = $row;
    }

    $stmt->close();

    return [
        'success' => true,
        'data' => $rooms
    ];
}










public function getRoomDetails($code)
{
    // Get room details
    $query = "
        SELECT 
            r.room_id,
            r.room_code,
            r.room_name,
            r.room_description,
            r.room_banner,
            r.room_date_created,
            u.user_id AS creator_id,
            u.user_fullname AS creator_name,
            u.user_email AS creator_email,
            COUNT(DISTINCT rm.user_id) AS total_members
        FROM room r
        INNER JOIN user u ON r.room_creator_user_id = u.user_id
        LEFT JOIN room_members rm ON r.room_id = rm.room_id
        WHERE r.room_code = ?
        GROUP BY 
            r.room_id, 
            r.room_code, 
            r.room_name, 
            r.room_description, 
            r.room_banner, 
            r.room_date_created, 
            u.user_id, 
            u.user_fullname, 
            u.user_email
        LIMIT 1
    ";

    $stmt = $this->conn->prepare($query);
    if (!$stmt) {
        return [
            "success" => false,
            "message" => "Prepare failed: " . $this->conn->error
        ];
    }

    $stmt->bind_param("s", $code);
    $stmt->execute();

    $result = $stmt->get_result();
    $room = $result->fetch_assoc();

    // If room exists
    if ($room) {
        $room_id = $room['room_id'];

        // Fetch members
        $memberQuery = "
            SELECT 
                u.user_id,
                u.user_fullname,
                u.user_email
            FROM room_members rm
            INNER JOIN user u ON rm.user_id = u.user_id
            WHERE rm.room_id = ?
        ";

        $stmtMembers = $this->conn->prepare($memberQuery);
        if ($stmtMembers) {
            $stmtMembers->bind_param("i", $room_id);
            $stmtMembers->execute();
            $membersResult = $stmtMembers->get_result();

            $members = [];
            while ($row = $membersResult->fetch_assoc()) {
                $members[] = $row;
            }

            $room['members'] = $members;
        } else {
            $room['members'] = [];
        }

        return [
            "success" => true,
            "data" => $room
        ];
    } else {
        return [
            "success" => false,
            "message" => "Room not found"
        ];
    }
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





    











public function joinRoom($user_id, $roomCode) {
    // 1. Find the room by code
    $query = "SELECT room_id, room_creator_user_id FROM room WHERE room_code = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("s", $roomCode);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        return [
            'success' => false,
            'message' => 'Invalid room code.'
        ];
    }

    $room = $result->fetch_assoc();
    $room_id = $room['room_id'];
    $creator_id = $room['room_creator_user_id'];

    // 🚫 2. Check if the user is the room creator
    if ($creator_id == $user_id) {
        return [
            'success' => false,
            'message' => 'You cannot join your own room.'
        ];
    }

    // 3. Check if the user already joined the room
    $checkQuery = "SELECT * FROM room_members WHERE room_id = ? AND user_id = ?";
    $checkStmt = $this->conn->prepare($checkQuery);
    $checkStmt->bind_param("ii", $room_id, $user_id);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        return [
            'success' => false,
            'message' => 'You have already joined this room.'
        ];
    }

    // 4. Insert new record if not joined yet
    $insertQuery = "INSERT INTO room_members (room_id, user_id) VALUES (?, ?)";
    $insertStmt = $this->conn->prepare($insertQuery);
    $insertStmt->bind_param("ii", $room_id, $user_id);

    if ($insertStmt->execute()) {
        return [
            'success' => true,
            'message' => 'Successfully joined the room.'
        ];
    } else {
        return [
            'success' => false,
            'message' => 'Join failed. Please try again.'
        ];
    }
}






















}