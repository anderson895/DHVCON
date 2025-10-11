<?php
session_start();
include "auth.php";

$db = new auth_class();
$cert = new certificate_class();

// Check both session and GET values properly
if (isset($_SESSION['user_id'], $_GET['meeting_id'], $_GET['meeting_pass'])) {

    $user_id = intval($_SESSION['user_id']);
    $meeting_id = intval($_GET['meeting_id']);
    $meeting_pass = $_GET['meeting_pass'];

    // Check if user exists and active
    $On_Session = $db->check_account($user_id);

    if (!empty($On_Session)) {
        // Fetch meeting certificate details
        $certificate_data = $cert->meeting_certificate($meeting_id, $meeting_pass, $user_id);

        if (!empty($certificate_data)) {
            // You can process or display certificate data here
            // Example:
            // print_r($certificate_data);
        } else {
            header('Location: ../404');
            exit;
        }

    } else {
        header('Location: ../404');
        exit;
    }

} else {
    header('Location: ../404');
    exit;
}
?>








<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Certificate</title>
  <link href="../src/output.css" rel="stylesheet" />
  
  <!-- Google Material Icons -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="bg-[#1e1f22] text-white min-h-screen flex">






</body>


</html>


