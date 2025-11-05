<?php
session_start();
require '../../vendor/autoload.php';
include('../config.php'); // must contain your db_connect class

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

if (!isset($_POST['meeting_id']) || !isset($_POST['room_id'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Missing parameters.'
    ]);
    exit;
}

$meeting_id = $_POST['meeting_id'];
$room_id = $_POST['room_id'];

// ✅ Connect to DB
$db = new db_connect();
$db->connect();
$conn = $db->conn;

// ✅ Get meeting details
$meetingQuery = $conn->prepare("SELECT * FROM meeting WHERE meeting_id = ?");
$meetingQuery->bind_param("i", $meeting_id);
$meetingQuery->execute();
$meetingResult = $meetingQuery->get_result();
$meeting = $meetingResult->fetch_assoc();



$start = date("F j, Y g:i A", strtotime($meeting['meeting_start']));
$end = date("F j, Y g:i A", strtotime($meeting['meeting_end']));
$meetingLink = "https://dhvcon.space/home/conference_room?code=" . $meeting['meeting_link'];


if (!$meeting) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Meeting not found.'
    ]);
    exit;
}

// ✅ Get all members of the room
$memberQuery = $conn->prepare("
    SELECT u.user_email, u.user_fullname
    FROM room_members rm
    INNER JOIN user u ON rm.user_id = u.user_id
    WHERE rm.room_id = ?
");
$memberQuery->bind_param("i", $room_id);
$memberQuery->execute();
$memberResult = $memberQuery->get_result();

if ($memberResult->num_rows === 0) {
    echo json_encode([
        'status' => 'error',
        'message' => 'No members found for this room.'
    ]);
    exit;
}

// ✅ Prepare PHPMailer
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'jessbertsoliguin456@gmail.com'; 
    $mail->Password = 'fcdc wynb xmsf clnl';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->setFrom('jessbertsoliguin456@gmail.com', 'Meeting Scheduler');
    $mail->isHTML(true);

    // ✅ Prepare email content
    $subject = "New Meeting: " . $meeting['meeting_title'];
    

   

    $body = "
        <h2>New Meeting Scheduled</h2>
        <p><strong>Title:</strong> {$meeting['meeting_title']}</p>
        <p><strong>Description:</strong> {$meeting['meeting_description']}</p>
        <p><strong>Start:</strong> {$start}</p>
        <p><strong>End:</strong> {$end}</p>
        <p><strong>Meeting Link:</strong> <a href='{$meetingLink}'>{$meeting['meeting_link']}</a></p>
        <br>
        <p>Thank you,<br>Meeting Scheduler System</p>
    ";

    $mail->Subject = $subject;
    $mail->Body = $body;

    // ✅ Send to all members
    while ($member = $memberResult->fetch_assoc()) {
        $mail->clearAddresses(); // clear previous email
        $mail->addAddress($member['user_email'], $member['user_fullname']);
        $mail->send();
    }

    echo json_encode([
        'status' => 'success',
        'message' => 'Emails sent to all members successfully.'
    ]);

} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Mailer Error: ' . $mail->ErrorInfo
    ]);
}
