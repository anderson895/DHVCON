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

        return $items;
    }


}