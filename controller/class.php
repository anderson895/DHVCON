
<?php


include ('config.php');  

date_default_timezone_set('Asia/Manila');

class global_class extends db_connect
{
    public function __construct()
    {
        $this->connect();
    }




    public function ratingMeeting($meeting_id, $rating, $user_id, $comment = null) {
    $meeting_id = intval($meeting_id);
    $rating = intval($rating);
    $comment = trim($comment ?? '');

    if ($rating < 1 || $rating > 5) {
        return ['status' => 'error', 'message' => 'Invalid rating value.'];
    }

    // Check if user has already rated this meeting
    $stmt = $this->conn->prepare("SELECT * FROM meeting_ratings WHERE meeting_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $meeting_id, $user_id);
    $stmt->execute();
    $existing = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($existing) {
        // Update existing rating and comment
        $stmt = $this->conn->prepare("UPDATE meeting_ratings SET rating = ?, comment = ? WHERE meeting_id = ? AND user_id = ?");
        $stmt->bind_param("isii", $rating, $comment, $meeting_id, $user_id);
        $stmt->execute();
        $stmt->close();
    } else {
        // Insert new rating and comment
        $stmt = $this->conn->prepare("INSERT INTO meeting_ratings (meeting_id, user_id, rating, comment) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiis", $meeting_id, $user_id, $rating, $comment);
        $stmt->execute();
        $stmt->close();
    }

    // Update the average rating in meetings table
    $stmt = $this->conn->prepare("
        UPDATE meeting m
        SET m.rating = (
            SELECT ROUND(AVG(rating), 1)
            FROM meeting_ratings
            WHERE meeting_id = m.meeting_id
        )
        WHERE m.meeting_id = ?
    ");
    $stmt->bind_param("i", $meeting_id);
    $stmt->execute();
    $stmt->close();

    return ['status' => 'success', 'message' => 'Rating and comment saved successfully.'];
}





public function GetMeetingsByRoom($room_id, $user_id = null)
{
    $query = "
        SELECT 
            m.meeting_id,
            m.meeting_link,
            m.meeting_title,
            m.meeting_description,
            m.meeting_start,
            m.meeting_end,
            m.meeting_room_id,
            m.meeting_creator_user_id,
            m.meeting_status,
            m.meeting_pass,
            IFNULL((
                SELECT ROUND(AVG(r.rating),1) 
                FROM meeting_ratings r 
                WHERE r.meeting_id = m.meeting_id
            ), 0) AS average_rating,
            IFNULL((
                SELECT rating 
                FROM meeting_ratings ur 
                WHERE ur.meeting_id = m.meeting_id 
                  AND ur.user_id = ?
                LIMIT 1
            ), 0) AS user_rating
        FROM meeting m
        WHERE m.meeting_room_id = ?
        ORDER BY m.meeting_start ASC
    ";

    $stmt = $this->conn->prepare($query);
    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $user_id = intval($user_id);
    $room_id = intval($room_id);
    $stmt->bind_param("ii", $user_id, $room_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $meetings = [];

    while ($row = $result->fetch_assoc()) {
        $row['average_rating'] = floatval($row['average_rating']);
        $row['user_rating'] = intval($row['user_rating']);

        // 🔹 Fetch all ratings + comments for this meeting
        $ratingsQuery = "
            SELECT 
                r.rating, 
                r.comment, 
                u.user_fullname AS username,
                u.user_profile_pict
            FROM meeting_ratings r
            JOIN user u ON u.user_id = r.user_id
            WHERE r.meeting_id = ?
            ORDER BY r.created_at DESC
        ";

        $ratingsStmt = $this->conn->prepare($ratingsQuery);
        $ratingsStmt->bind_param("i", $row['meeting_id']);
        $ratingsStmt->execute();
        $ratingsResult = $ratingsStmt->get_result();

        $ratings = [];
        while ($ratingRow = $ratingsResult->fetch_assoc()) {
            $ratings[] = $ratingRow;
        }
        $ratingsStmt->close();

        $row['ratings'] = $ratings; // Attach to meeting

        $meetings[] = $row;
    }

    $stmt->close();
    return $meetings;
}












public function GetMeetingsByRoom_admin($room_id)
{
    $query = "
        SELECT 
            m.meeting_id,
            m.meeting_link,
            m.meeting_title,
            m.meeting_description,
            m.meeting_start,
            m.meeting_end,
            m.meeting_room_id,
            m.meeting_creator_user_id,
            m.meeting_status,
            m.meeting_pass,
            IFNULL((
                SELECT ROUND(AVG(r.rating),1) 
                FROM meeting_ratings r 
                WHERE r.meeting_id = m.meeting_id
            ), 0) AS average_rating
        FROM meeting m
        WHERE m.meeting_room_id = ?
        ORDER BY m.meeting_start ASC
    ";

    $stmt = $this->conn->prepare($query);
    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $room_id = intval($room_id);
    $stmt->bind_param("i", $room_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $meetings = [];

    while ($row = $result->fetch_assoc()) {
        $row['average_rating'] = floatval($row['average_rating']);

        // 🔹 Fetch all ratings + comments for this meeting
        $ratingsQuery = "
            SELECT 
                r.rating, 
                r.comment, 
                u.user_fullname AS username,
                u.user_profile_pict
            FROM meeting_ratings r
            JOIN user u ON u.user_id = r.user_id
            WHERE r.meeting_id = ?
            ORDER BY r.created_at DESC
        ";

        $ratingsStmt = $this->conn->prepare($ratingsQuery);
        $ratingsStmt->bind_param("i", $row['meeting_id']);
        $ratingsStmt->execute();
        $ratingsResult = $ratingsStmt->get_result();

        $ratings = [];
        while ($ratingRow = $ratingsResult->fetch_assoc()) {
            $ratings[] = $ratingRow;
        }
        $ratingsStmt->close();

        $row['ratings'] = $ratings;

        $meetings[] = $row;
    }

    $stmt->close();
    return $meetings;
}











     // Fetch rating results for a meeting
    public function getMeetingRating($meeting_id) {
        $meeting_id = intval($meeting_id);

        // Get average rating
        $stmt = $this->conn->prepare("
            SELECT 
                IFNULL(ROUND(AVG(rating), 1), 0) AS average_rating,
                COUNT(*) AS total_ratings
            FROM meeting_ratings
            WHERE meeting_id = ?
        ");
        $stmt->bind_param("i", $meeting_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return [
            'status' => 'success',
            'average_rating' => $result['average_rating'],
            'total_ratings' => $result['total_ratings']
        ];
    }

    // Optionally, fetch rating for a specific user
    public function getUserRating($meeting_id, $user_id) {
        $stmt = $this->conn->prepare("
            SELECT rating 
            FROM meeting_ratings 
            WHERE meeting_id = ? AND user_id = ?
        ");
        $stmt->bind_param("ii", $meeting_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $result ? intval($result['rating']) : 0;
    }





 // Send a chat message
    public function sendchats($message, $userId, $roomCode)
    {
        $stmt = $this->conn->prepare("INSERT INTO meeting_chats (chat_message, chat_sender, chat_meeting_code) VALUES (?, ?, ?)");
        if (!$stmt) return false;

        $stmt->bind_param("sis", $message, $userId, $roomCode);
        return $stmt->execute(); // returns true on success, false on failure
    }

    // Load chat messages for a room
    public function loadchats($userId, $roomCode)
    {
        // Join meeting_chats with user table to get fullname and position
        $stmt = $this->conn->prepare("
            SELECT mc.chat_message, mc.chat_sender, u.user_fullname, u.user_type,u.user_profile_pict
            FROM meeting_chats mc
            INNER JOIN user u ON mc.chat_sender = u.user_id
            WHERE mc.chat_meeting_code = ?
            ORDER BY mc.chat_id ASC
        ");
        if (!$stmt) return [];

        $stmt->bind_param("s", $roomCode);
        $stmt->execute();
        $result = $stmt->get_result();
        $messages = [];

        while ($row = $result->fetch_assoc()) {
            $messages[] = [
                'message' => htmlspecialchars($row['chat_message']),
                'sender_self' => $row['chat_sender'] == $userId,
                'sender_name' => $row['user_fullname'],
                'sender_position' => $row['user_type'],
                'user_profile_pict' => $row['user_profile_pict']
            ];
        }

        return $messages;
    }



     public function getUsers($filter = null) {
        $sql = "SELECT user_id, user_fullname, user_email, user_type, user_status,user_requirements FROM user";
        $conditions = [];

        if ($filter === 'pending') {
            $conditions[] = "user_status = 0";
        } elseif ($filter === 'teacher') {
            $conditions[] = "user_type = 'teacher'";
        } elseif ($filter === 'student') {
            $conditions[] = "user_type = 'student'";
        }

        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        $result = $this->conn->query($sql);
        $data = [];

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        return $data;
    }





     public function fetch_all_room_pages($limit, $offset) {
        $query = $this->conn->prepare("
            SELECT r.*,u.user_fullname AS room_creator_name
            FROM room r
            LEFT JOIN user u ON r.room_creator_user_id  = u.user_id
            WHERE room_status = '1' 
            ORDER BY room_id DESC 
            LIMIT ? OFFSET ?
        ");
        $query->bind_param("ii", $limit, $offset);

        if ($query->execute()) {
            $result = $query->get_result();
            $dogs = [];

            while ($row = $result->fetch_assoc()) {
                $dogs[] = $row;
            }

            return $dogs;
        }
        return []; 
    }


    public function count_all_room_pages() {
        $result = $this->conn->query("
            SELECT COUNT(*) as total 
            FROM room 
            WHERE room_status = '1'
        ");
        $row = $result->fetch_assoc();
        return (int)$row['total'];
    }




    public function updateUserStatus($id, $status) {
        $stmt = $this->conn->prepare("UPDATE user SET user_status = ? WHERE user_id = ?");
        $stmt->bind_param("ii", $status, $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }


    



    public function updateProfile($id, $fullname, $email, $fileName) {
            // Prepare the SQL statement with placeholders
            $stmt = $this->conn->prepare("UPDATE user SET user_fullname=?, user_email=?, user_profile_pict=? WHERE user_id=?");
            
            if (!$stmt) {
                return false; // prepare failed
            }

            // Bind parameters: s = string, i = integer
            $stmt->bind_param("sssi", $fullname, $email, $fileName, $id);

            // Execute the statement
            $result = $stmt->execute();

            // Close the statement
            $stmt->close();

            return $result;
        }



        public function getUserByEmail($email) {
        $email = $this->conn->real_escape_string(trim($email));
        $sql = "SELECT * FROM user WHERE user_email = '$email' LIMIT 1";
        $result = $this->conn->query($sql);

        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return false;
    }

    public function saveResetToken($email, $token, $expiry) {
        $email = $this->conn->real_escape_string(trim($email));
        $token = $this->conn->real_escape_string($token);
        $expiry = (int)$expiry;

        $sql = "UPDATE user SET reset_token='$token', reset_expiry=$expiry WHERE user_email='$email' LIMIT 1";
        return $this->conn->query($sql);
    }

    public function validateResetToken($token) {
        $token = $this->conn->real_escape_string($token);
        $current_time = time();

        $sql = "SELECT * FROM user WHERE reset_token='$token' AND reset_expiry > $current_time LIMIT 1";
        $result = $this->conn->query($sql);

        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return false;
    }

    // Update password with old password verification
    public function updatePassword($id, $old_pass, $new_pass) {
        $id = (int)$id;
        $old_pass = trim($old_pass);
        $new_pass = trim($new_pass);

        $sql = "SELECT user_password FROM user WHERE user_id=$id LIMIT 1";
        $result = $this->conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($old_pass, $row['user_password'])) {
                $newHash = password_hash($new_pass, PASSWORD_BCRYPT);
                $update = "UPDATE user SET user_password='$newHash' WHERE user_id=$id";
                return $this->conn->query($update) ? true : false;
            }
        }
        return false;
    }

    // Update password using reset token (forgot password)
    public function updatePasswordByToken($token, $new_pass) {
        $token = $this->conn->real_escape_string($token);
        $new_pass = trim($new_pass);
        $current_time = time();

        $sql = "SELECT user_id FROM user WHERE reset_token='$token' AND reset_expiry > $current_time LIMIT 1";
        $result = $this->conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $user_id = (int)$row['user_id'];

            $newHash = password_hash($new_pass, PASSWORD_BCRYPT);
            $update = "UPDATE user SET user_password='$newHash', reset_token=NULL, reset_expiry=NULL WHERE user_id=$user_id";
            return $this->conn->query($update) ? true : false;
        }
        return false;
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


public function isEmailExist($email) {
    $query = "SELECT user_id FROM `user` WHERE `user_email` = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    return $stmt->num_rows > 0; 
}



public function SignUp($full_name, $email, $password, $user_type, $requirementsJSON) {
    // Check if the email already exists
     if ($this->isEmailExist($email)) {
        return [
            'success' => false,
            'message' => 'Email already registered.'
        ];
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user (include requirements JSON)
    $query = "INSERT INTO `user` (`user_fullname`, `user_email`, `user_password`, `user_type`, `user_requirements`)
              VALUES (?, ?, ?, ?, ?)";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("sssss", $full_name, $email, $hashedPassword, $user_type, $requirementsJSON);

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

    $query = "INSERT INTO `room` 
              (`room_creator_user_id`, `room_banner`, `room_name`, `room_description`, `room_code`) 
              VALUES (?, ?, ?, ?, ?)";

    $stmt = $this->conn->prepare($query);
    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("issss", $user_id, $roomImageFileName, $roomName, $roomDescription, $room_code);

    if ($stmt->execute()) {
        $inserted_id = $this->conn->insert_id;
        $stmt->close();
        return $inserted_id;
    } else {
        $stmt->close();
        return false;
    }
}

public function updateRoom($roomId, $roomName, $roomDescription, $roomImageFileName, $user_id) {
    if ($roomImageFileName) {
        $query = "UPDATE `room` SET `room_name` = ?, `room_description` = ?, `room_banner` = ? 
                  WHERE `room_id` = ? AND `room_creator_user_id` = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sssii", $roomName, $roomDescription, $roomImageFileName, $roomId, $user_id);
    } else {
        $query = "UPDATE `room` SET `room_name` = ?, `room_description` = ? 
                  WHERE `room_id` = ? AND `room_creator_user_id` = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssii", $roomName, $roomDescription, $roomId, $user_id);
    }

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $result = $stmt->execute();
    $stmt->close();

    return $result; // true on success, false on failure
}








public function CreateMeeting($user_id, $meeting_link, $meeting_title, $meeting_description, $start_date, $end_date, $room_id)
{
    // ✅ Generate a unique meeting pass that does not already exist
    do {
        $meeting_pass = bin2hex(random_bytes(4)); // 8-character random hex
        $checkQuery = "SELECT COUNT(*) as count FROM `meeting` WHERE meeting_pass = ?";
        $checkStmt = $this->conn->prepare($checkQuery);
        $checkStmt->bind_param("s", $meeting_pass);
        $checkStmt->execute();
        $checkStmt->bind_result($count);
        $checkStmt->fetch();
        $checkStmt->close();
    } while ($count > 0); // repeat if pass already exists

    // ✅ Insert meeting
    $query = "
        INSERT INTO `meeting` 
        (`meeting_pass`,`meeting_link`, `meeting_title`, `meeting_description`, `meeting_start`, `meeting_end`, `meeting_room_id`,`meeting_creator_user_id`) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ";

    $stmt = $this->conn->prepare($query);
    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param(
        "ssssssii",
        $meeting_pass,
        $meeting_link,
        $meeting_title,
        $meeting_description,
        $start_date,
        $end_date,
        $room_id,
        $user_id
    );

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






// 🔹 Get single classwork by ID
public function GetClassworkById($classwork_id)
{
    $query = "SELECT * FROM `classwork` WHERE `classwork_id` = ?";
    $stmt = $this->conn->prepare($query);
    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("i", $classwork_id);
    $stmt->execute();

    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    $stmt->close();
    return $data ?: false;
}





// 🔹 Check if meeting link exists
public function checkMeetingLink($code)
{
    $query = "SELECT * FROM `meeting` WHERE `meeting_link` = ?";
    $stmt = $this->conn->prepare($query);

    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    // Bind as string (not integer)
    $stmt->bind_param("s", $code);
    $stmt->execute();

    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    $stmt->close();
    return $data ?: false;
}







// 🔹 Update existing classwork
public function UpdateClasswork($classwork_id, $title, $instructions, $fileName = null)
{
    if ($fileName) {
        $query = "
            UPDATE `classwork` 
            SET `classwork_title` = ?, 
                `classwork_instruction` = ?, 
                `classwork_file` = ? 
            WHERE `classwork_id` = ?
        ";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }
        $stmt->bind_param("sssi", $title, $instructions, $fileName, $classwork_id);
    } else {
        $query = "
            UPDATE `classwork` 
            SET `classwork_title` = ?, 
                `classwork_instruction` = ? 
            WHERE `classwork_id` = ?
        ";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }
        $stmt->bind_param("ssi", $title, $instructions, $classwork_id);
    }

    $result = $stmt->execute();
    $stmt->close();
    return $result;
}



// 🔹 Delete classwork by ID
public function DeleteClasswork($classwork_id)
{
    $query = "UPDATE `classwork` SET `classwork_status` = 0 WHERE `classwork_id` = ?";
    $stmt = $this->conn->prepare($query);
    if (!$stmt) {
        die("Prepare failed: " . $this->conn->error);
    }

    $stmt->bind_param("i", $classwork_id);
    $result = $stmt->execute();
    $stmt->close();

    return $result;
}





public function getAllRooms($user_id) {
    $query = "
        SELECT * 
        FROM `room`
        WHERE room_id NOT IN (
            SELECT room_id FROM room_members WHERE user_id = ?
        ) AND room_status=1
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









public function getClassworkDetails_all($id) {
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



public function getClassworkDetails_where_user_id_only($user_id, $classwork_id){
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
        LEFT JOIN submitted_classwork sc 
            ON sc.sw_classwork_id = c.classwork_id 
            AND sc.sw_user_id = ?
        WHERE c.classwork_id = ?
    ";

    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $classwork_id);
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




public function get_all_created_works_admin($room_id) {
    $sql = "SELECT * FROM classwork 
            WHERE classwork_room_id = ? 
              AND classwork_status = 1
            ORDER BY created_at DESC";

    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $room_id);
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
            WHERE rm.user_id = ? AND room_status='1'
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
        WHERE room_creator_user_id = ? AND room_status='1'
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
    where submitted_classwork.sw_classwork_id = ? AND submitted_classwork.sw_status=1
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










public function getRoomById($room_id)
{
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
            u.user_email AS creator_email
        FROM room r
        INNER JOIN user u ON r.room_creator_user_id = u.user_id
        WHERE r.room_id = ?
        LIMIT 1
    ";

    $stmt = $this->conn->prepare($query);
    if (!$stmt) {
        return [
            "success" => false,
            "message" => "Prepare failed: " . $this->conn->error
        ];
    }

    $stmt->bind_param("i", $room_id);
    $stmt->execute();

    $result = $stmt->get_result();
    $room = $result->fetch_assoc();

    if ($room) {
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

                // ✅ Check user account status
                if ($user['user_status'] == 0) {
                    $query->close();
                    return [
                        'success' => false,
                        'message' => 'Your account is awaiting administrator approval.'
                    ];
                } elseif ($user['user_status'] == 2) {
                    $query->close();
                    return [
                        'success' => false,
                        'message' => 'Your account has been disabled. Please contact the administrator.'
                    ];
                } elseif ($user['user_status'] != 1) {
                    $query->close();
                    return [
                        'success' => false,
                        'message' => 'Invalid account status. Please contact support.'
                    ];
                }

                // ✅ Proceed with login if status is 1
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }

                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user_type'] = $user['user_type']; 

                $query->close();
                return [
                    'success' => true,
                    'message' => 'Login successful.',
                    'data' => [
                        'user_id' => $user['user_id'],
                        'user_type' => $user['user_type'] 
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










public function CloseMeeting($meeting_id)
{
    $query = "UPDATE meeting SET meeting_status = 0 WHERE meeting_id = ?";
    $stmt = $this->conn->prepare($query);
    if (!$stmt) {
        return false;
    }

    $stmt->bind_param("i", $meeting_id);
    $result = $stmt->execute();
    $stmt->close();

    return $result; 
}








public function LeaveRoom($room_code, $user_id)
{
    // 1. Get room_id from room_code
    $query = "SELECT room_id FROM room WHERE room_code = ?";
    $stmt = $this->conn->prepare($query);
    if (!$stmt) {
        return false;
    }

    $stmt->bind_param("s", $room_code); 
    $stmt->execute();
    $stmt->bind_result($room_id);
    $stmt->fetch();
    $stmt->close();

    if (!$room_id) {
        return false;
    }

    // 2. Delete the user from room_members
    $query = "DELETE FROM room_members WHERE room_id = ? AND user_id = ?";
    $stmt = $this->conn->prepare($query);
    if (!$stmt) {
        return false;
    }

    $stmt->bind_param("ii", $room_id, $user_id);
    $result = $stmt->execute();
    $stmt->close();

    return $result;
}







public function deleteRoom($room_id)
{
    $query = "UPDATE room SET room_status = 0 WHERE room_id = ?";
    $stmt = $this->conn->prepare($query);
    if (!$stmt) {
        return false;
    }

    $stmt->bind_param("i", $room_id);
    $result = $stmt->execute();
    $stmt->close();

    return $result; 
}












public function requestToJoin($meeting_id, $user_id)
{
    // Check if record already exists
    $checkQuery = "SELECT jr_id FROM meeting_member WHERE jr_meeting_id = ? AND jr_user_id = ?";
    $checkStmt = $this->conn->prepare($checkQuery);
    if (!$checkStmt) return false;

    $checkStmt->bind_param("ii", $meeting_id, $user_id);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        $checkStmt->close();
        return "exists";
    }
    $checkStmt->close();

    // Insert new record
    $insertQuery = "INSERT INTO meeting_member (jr_meeting_id, jr_user_id) VALUES (?, ?)";
    $insertStmt = $this->conn->prepare($insertQuery);
    if (!$insertStmt) return false;

    $insertStmt->bind_param("ii", $meeting_id, $user_id);
    $result = $insertStmt->execute();
    $insertStmt->close();

    return $result ? "inserted" : false;
}




public function checkMemberStatus($meeting_id, $user_id)
{
    // 1. Check if the user is the meeting creator
    $query = "SELECT meeting_creator_user_id FROM meeting WHERE meeting_id = ?";
    $stmt = $this->conn->prepare($query);
    if (!$stmt) return false;

    $stmt->bind_param("i", $meeting_id);
    $stmt->execute();
    $stmt->bind_result($creator_id);

    if ($stmt->fetch()) {
        if ($creator_id == $user_id) {
            $stmt->close();
            return "creator"; 
        }
    }
    $stmt->close();

    // 2. If not the creator, check meeting_member table
    $query = "SELECT jr_status FROM meeting_member WHERE jr_meeting_id = ? AND jr_user_id = ?";
    $stmt = $this->conn->prepare($query);
    if (!$stmt) return false;

    $stmt->bind_param("ii", $meeting_id, $user_id);
    $stmt->execute();
    $stmt->bind_result($status);

    if ($stmt->fetch()) {
        $stmt->close();
        return $status; // 'pending', 'approved', or 'rejected'
    } else {
        $stmt->close();
        return null; // user is not in meeting_member
    }
}





public function getPendingJoinRequests($meeting_id)
{
    // Prepare SQL query to count pending join requests
    $query = "SELECT COUNT(*) as pending_count 
              FROM meeting_member 
              WHERE jr_meeting_id = ? AND jr_status = 'pending'";
    
    $stmt = $this->conn->prepare($query);
    if (!$stmt) return 0; // Return 0 if preparation fails

    $stmt->bind_param("i", $meeting_id);
    $stmt->execute();
    $stmt->bind_result($pending_count);
    $stmt->fetch();
    $stmt->close();

    return $pending_count; 
}




public function getPendingRequestsDetails($meeting_id)
{
    $query = "
        SELECT jr.*, u.*
        FROM meeting_member jr
        JOIN user u ON jr.jr_user_id = u.user_id
        WHERE jr.jr_meeting_id = ? AND jr.jr_status = 'pending'
    ";

    $stmt = $this->conn->prepare($query);
    if (!$stmt) return [];

    $stmt->bind_param("i", $meeting_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $pendingRequests = [];
    while ($row = $result->fetch_assoc()) {
        $pendingRequests[] = $row;
    }
    $stmt->close();

    return $pendingRequests;
}







public function updateJoinRequestStatus($jr_id, $action)
{
    $query = "UPDATE meeting_member SET jr_status = ? WHERE jr_id = ?";
    $stmt = $this->conn->prepare($query);
    if (!$stmt) return false;

    $stmt->bind_param("si", $action, $jr_id);
    return $stmt; // return the prepared statement for execution
}



public function removeUserFromMeeting($meeting_id, $user_id)
{
    $query = "DELETE FROM meeting_member WHERE jr_meeting_id = ? AND jr_user_id = ?";
    $stmt = $this->conn->prepare($query);
    if (!$stmt) return false;

    $stmt->bind_param("ii", $meeting_id, $user_id);
    $result = $stmt->execute();
    $stmt->close();

    return $result;
}







public function getApprovedUsers($meeting_id)
{
    $query = "
        SELECT jr.jr_id, jr.jr_user_id, jr.jr_requested_at, u.user_fullname, u.user_email
        FROM meeting_member jr
        JOIN user u ON jr.jr_user_id = u.user_id
        WHERE jr.jr_meeting_id = ? AND jr.jr_status = 'approved'
    ";

    $stmt = $this->conn->prepare($query);
    if (!$stmt) return []; // return empty array if preparation fails

    $stmt->bind_param("i", $meeting_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $approvedUsers = [];
    while ($row = $result->fetch_assoc()) {
        $approvedUsers[] = $row;
    }

    $stmt->close();
    return $approvedUsers; // return the approved users
}





public function recordMeetingLog($meeting_id, $user_id)
{
    // Check if record already exists
    $checkQuery = "SELECT ml_id FROM meeting_logs WHERE ml_meeting_id = ? AND ml_user_id = ?";
    $checkStmt = $this->conn->prepare($checkQuery);
    if (!$checkStmt) return false;

    $checkStmt->bind_param("ii", $meeting_id, $user_id);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        $checkStmt->close();
        return "exists";
    }
    $checkStmt->close();

    // Insert new record
    $insertQuery = "INSERT INTO meeting_logs (ml_meeting_id, ml_user_id) VALUES (?, ?)";
    $insertStmt = $this->conn->prepare($insertQuery);
    if (!$insertStmt) return false;

    $insertStmt->bind_param("ii", $meeting_id, $user_id);
    $result = $insertStmt->execute();
    $insertStmt->close();

    return $result ? "inserted" : false;
}







public function viewMeetingLogs($meeting_id)
{
    $query = "
        SELECT 
            ml.ml_id,
            ml.ml_date_joined,
            u.user_id,
            u.user_fullname,
            u.user_email
        FROM meeting_logs AS ml
        INNER JOIN user AS u ON ml.ml_user_id = u.user_id
        WHERE ml.ml_meeting_id = ?
        ORDER BY ml.ml_date_joined DESC
    ";

    $stmt = $this->conn->prepare($query);
    if (!$stmt) return false;

    $stmt->bind_param("i", $meeting_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $logs = [];
    while ($row = $result->fetch_assoc()) {
        $logs[] = $row;
    }

    $stmt->close();
    return $logs;
}















public function fetchAllClaimedCertificates($user_id, $room_id)
{
    $query = "
        SELECT 
            cc.claimed_id,
            cc.claimed_meeting_id,
            cc.claimed_user_id,
            cc.claimed_date,
            m.meeting_title,
            m.meeting_pass,
            m.meeting_end,
            r.room_name,
            u.user_fullname,
            u.user_email
        FROM claimed_certificate AS cc
        INNER JOIN meeting AS m ON cc.claimed_meeting_id = m.meeting_id
        INNER JOIN room AS r ON m.meeting_room_id = r.room_id
        INNER JOIN user AS u ON cc.claimed_user_id = u.user_id
        WHERE r.room_id = ? AND cc.claimed_user_id = ?
        ORDER BY cc.claimed_date DESC
    ";

    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("ii", $room_id, $user_id);

    if (!$stmt->execute()) {
        return [
            'success' => false,
            'message' => 'Database query failed.'
        ];
    }

    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);

    return [
        'success' => true,
        'status' => 200,
        'count' => count($data),
        'data' => $data
    ];
}













public function getDataAnalytics()
{
    $query = "
        SELECT 
            -- Total users
            (SELECT COUNT(*) FROM user) AS total_users,

            -- Active users
            (SELECT COUNT(*) FROM user WHERE user_status = 1) AS active_users,

            -- Users waiting for approval
            (SELECT COUNT(*) FROM user WHERE user_status = 0) AS for_approval_users,

            -- Disabled users
            (SELECT COUNT(*) FROM user WHERE user_status = 2) AS disabled_users,

            -- Total admins
            (SELECT COUNT(*) FROM user WHERE user_type = 'admin') AS total_admins,

            -- Total teachers
            (SELECT COUNT(*) FROM user WHERE user_type = 'teacher') AS total_teachers,

            -- Total students
            (SELECT COUNT(*) FROM user WHERE user_type = 'student') AS total_students,

            -- Total rooms
            (SELECT COUNT(*) FROM room) AS total_rooms,

            -- Total active rooms
            (SELECT COUNT(*) FROM room WHERE room_status = 1) AS active_rooms,

            -- Total meetings
            (SELECT COUNT(*) FROM meeting) AS total_meetings,

            -- Open meetings
            (SELECT COUNT(*) FROM meeting WHERE meeting_status = 1) AS open_meetings,

            -- Closed meetings
            (SELECT COUNT(*) FROM meeting WHERE meeting_status = 0) AS closed_meetings,

            -- Total classworks
            (SELECT COUNT(*) FROM classwork WHERE classwork_status='1') AS total_classworks,

            -- Active classworks
            (SELECT COUNT(*) FROM classwork WHERE classwork_status = 1) AS active_classworks,

            -- Archived classworks
            (SELECT COUNT(*) FROM classwork WHERE classwork_status = 0) AS archived_classworks,

            -- Total submissions
            (SELECT COUNT(*) FROM submitted_classwork WHERE sw_status = 1) AS total_submissions,

            -- Not turned in
            (SELECT COUNT(*) FROM submitted_classwork WHERE sw_status = 0) AS not_submitted,

            -- Total claimed certificates
            (SELECT COUNT(*) FROM claimed_certificate) AS total_claimed_certificates,

            -- Total room memberships
            (SELECT COUNT(*) FROM room_members) AS total_room_members,

            -- Total meeting logs (attendance)
            (SELECT COUNT(*) FROM meeting_logs) AS total_meeting_logs
    ";

    $result = $this->conn->query($query);

    if ($result) {
        return $result->fetch_assoc();
    } else {
        return false;
    }
}







public function get_users_data($user_id) {
    $user_id = intval($user_id);

    $stmt = $this->conn->prepare("SELECT * FROM user WHERE user_id = ?");
    if (!$stmt) return null;

    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    $result = $stmt->get_result();
    $profile = $result->fetch_assoc();

    $stmt->close();

    return $profile ?: null;
}







}

















class System extends db_connect
{
    public function __construct()
    {
        $this->connect();
    }




    // Get all signatories
    public function getSignatories($system_id = 1) {
        $stmt = $this->conn->prepare("SELECT signatories FROM `system` WHERE system_id = ?");
        $stmt->bind_param("i", $system_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $signatories = [];
        if ($row = $result->fetch_assoc()) {
            $signatories = json_decode($row['signatories'], true);
        }
        $stmt->close();
        return $signatories;
    }

    // Save signatories (replace all)
    public function saveSignatories($signatories, $system_id = 1) {
        $json = json_encode($signatories, JSON_PRETTY_PRINT);
        $stmt = $this->conn->prepare("UPDATE `system` SET signatories = ? WHERE system_id = ?");
        $stmt->bind_param("si", $json, $system_id);
        $stmt->execute();
        $stmt->close();
        return true;
    }

    // Add a new signatory
    public function addSignatory($name, $position, $department, $system_id = 1) {
        $signatories = $this->getSignatories($system_id);
        $signatories[] = [
            'name' => $name,
            'position' => $position,
            'department' => $department
        ];
        return $this->saveSignatories($signatories, $system_id);
    }

    // Update a signatory by index
    public function updateSignatory($index, $name, $position, $department, $system_id = 1) {
        $signatories = $this->getSignatories($system_id);
        if (isset($signatories[$index])) {
            $signatories[$index] = [
                'name' => $name,
                'position' => $position,
                'department' => $department
            ];
            return $this->saveSignatories($signatories, $system_id);
        }
        return false;
    }

    // Delete a signatory by index
    public function deleteSignatory($index, $system_id = 1) {
        $signatories = $this->getSignatories($system_id);
        if (isset($signatories[$index])) {
            array_splice($signatories, $index, 1);
            return $this->saveSignatories($signatories, $system_id);
        }
        return false;
    }

}