<?php
include('../class.php');

$db = new global_class();

session_start();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['requestType'])) {
        if ($_POST['requestType'] == 'SignUp') {
                $full_name = $_POST['full_name'];
                $email  = $_POST['email'];
                $password   = $_POST['password'];

                $result = $db->SignUp($full_name, $email, $password);

                if ($result['success']) {
                    echo json_encode([
                        'status' => 'success',
                        'message' => $result['message'],
                    ]);
                } else {
                    echo json_encode([
                        'status' => 'error',
                        'message' => $result['message']
                    ]);
                }
        }else if ($_POST['requestType'] == 'createRoom') {

       

        $user_id = $_SESSION['user_id'];
        $roomName = $_POST['roomName'];
        $roomDescription = $_POST['roomDescription'];

        $roomBanner = $_FILES['roomBanner'];
        $uploadDir = '../../static/upload/';
        $roomImageFileName = null; // default to null if no file

        if (isset($roomBanner) && $roomBanner['error'] === UPLOAD_ERR_OK) {
            $bannerExtension = pathinfo($roomBanner['name'], PATHINFO_EXTENSION);
            $roomImageFileName = uniqid('room_', true) . '.' . $bannerExtension;
            $bannerPath = $uploadDir . $roomImageFileName;

            if (!move_uploaded_file($roomBanner['tmp_name'], $bannerPath)) {
                echo json_encode([
                    'status' => 500,
                    'message' => 'Error uploading roomBanner image.'
                ]);
                exit;
            }
        } elseif ($roomBanner['error'] !== UPLOAD_ERR_NO_FILE && $roomBanner['error'] !== 0) {
            echo json_encode([
                'status' => 400,
                'message' => 'Invalid image upload.'
            ]);
            exit;
        }

        // Call createRoom and get inserted ID
        $insertedId = $db->createRoom($roomName, $roomDescription, $roomImageFileName, $user_id);

        if ($insertedId) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Room created successfully.',
                'room_id' => $insertedId
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to create room.'
            ]);
        }


               

        }else if ($_POST['requestType'] == 'Login') {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $loginResult = $db->Login($email, $password);

            if ($loginResult['success']) {
                echo json_encode([
                    'status' => 'success',
                    'message' => $loginResult['message']
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => $loginResult['message']
                ]);
            }
        }else{
            echo "404";
        }
    }else {
        echo 'No POST REQUEST';
    }

}elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {

   if (isset($_GET['requestType']))
    {
        if ($_GET['requestType'] == 'getAllRooms') {
            $result = $db->getAllRooms();
            echo json_encode([
                'status' => 200,
                'data' => $result
            ]);
        } else{
            echo "404";
        }
    }else {
        echo 'No GET REQUEST';
    }

}
?>