<?php
session_start();
require '../../vendor/autoload.php';
include('../config.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

// Connect to database
$db = new db_connect();
$db->connect();
$conn = $db->conn;

if (isset($_SESSION['user_id'], $_GET['meeting_id'], $_GET['meeting_pass'])) {
    $user_id = intval($_SESSION['user_id']);
    $meeting_id = intval($_GET['meeting_id']);
    $meeting_pass = $_GET['meeting_pass'];

    // Fetch user from database
    $stmt = $conn->prepare("SELECT * FROM `user` WHERE user_id = ? AND user_status = 1");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $user = $result->fetch_assoc();

    if ($user) {
        $userEmail = $user['user_email'] ?? '';

        if (empty($userEmail)) {
            echo json_encode(['status' => 'error', 'message' => 'User email not found.']);
            exit;
        }

        // Generate certificate link with user_id
        $certificateLink = "http://localhost/DHVCON/home/certificate?user_id=$user_id&meeting_id=$meeting_id&meeting_pass=$meeting_pass";

        // Send email
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'rodriguezryan325@gmail.com';
            $mail->Password = 'ofvf yxut wpcc iecx'; // consider using env variable
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('rodriguezryan325@gmail.com', 'Certificate System');
            $mail->addAddress($userEmail);
            $mail->isHTML(true);
            $mail->Subject = 'Your Certificate Link';
            $mail->Body = "
                <h2>Certificate Ready!</h2>
                <p>Click the link below to print your certificate:</p>
                <a href='$certificateLink' target='_blank'>$certificateLink</a>
                <p>Keep this link safe!</p>
            ";

            $mail->send();
            echo json_encode(['status' => 'success', 'message' => 'Certificate link sent to your email.']);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to send email: ' . $mail->ErrorInfo]);
        }

    } else {
        header('Location: ../404');
        exit;
    }

} else {
    echo json_encode(['status' => 'error', 'message' => 'Missing required parameters or session expired.']);
    exit;
}
