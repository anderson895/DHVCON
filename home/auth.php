<?php


include ('../controller/config.php');

date_default_timezone_set('Asia/Manila');

class auth_class extends db_connect
{
    public function __construct()
    {
        $this->connect();
    }

    public function check_account($id) {
        $id = intval($id);
        $query = "SELECT * FROM `user` WHERE user_id = $id AND user_status=1";

        $result = $this->conn->query($query);

        $items = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $items[] = $row;
            }
        }
        return $items; 
    }

}





class certificate_class extends db_connect
{
    public function __construct()
    {
        $this->connect();
    }

    
   public function meeting_certificate($meeting_id, $meeting_pass, $user_id)
{
    // Step 1: Validate if the user belongs to the meeting
    $query = "
        SELECT * FROM meeting
        LEFT JOIN room_members AS rm ON rm.room_id = meeting.meeting_room_id 
        LEFT JOIN room ON room.room_id = rm.room_id  
        LEFT JOIN user ON user.user_id = rm.user_id   
        WHERE meeting.meeting_id = ? 
        AND meeting.meeting_pass = ? 
        AND user.user_id = ?
    ";

    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("isi", $meeting_id, $meeting_pass, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $items = [];
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }

    if (empty($items)) {
        // Meeting or access invalid
        return false;
    }

    // Step 2: Check if already claimed
    $checkQuery = "
        SELECT claimed_id FROM claimed_certificate 
        WHERE claimed_meeting_id = ? AND claimed_user_id = ?
    ";
    $checkStmt = $this->conn->prepare($checkQuery);
    $checkStmt->bind_param("ii", $meeting_id, $user_id);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows === 0) {
        // Step 3: Insert new record
        $insertQuery = "
            INSERT INTO claimed_certificate (claimed_meeting_id, claimed_user_id)
            VALUES (?, ?)
        ";
        $insertStmt = $this->conn->prepare($insertQuery);
        $insertStmt->bind_param("ii", $meeting_id, $user_id);
        $insertStmt->execute();

        $items[0]['certificate_status'] = 'inserted';
    } else {
        $items[0]['certificate_status'] = 'already_exists';
    }

    return $items;
}







       





}