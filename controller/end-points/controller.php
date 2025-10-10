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
        }else if ($_POST['requestType'] == 'joinRoom') {
                $user_id = $_SESSION['user_id'];
                $roomCode = $_POST['roomCode'];

                $result = $db->joinRoom($user_id,$roomCode);

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
            $roomImageFileName = null; 

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

        }else if ($_POST['requestType'] === 'UploadFiles') {
            $user_id = $_SESSION['user_id'];
            $classwork_id = $_POST['classwork_id'];
            $uploadDir = '../../static/upload/';
            $uploadedFiles = [];

            if (!empty($_FILES['files']['name'][0])) {
                foreach ($_FILES['files']['name'] as $key => $name) {
                    if ($_FILES['files']['error'][$key] !== UPLOAD_ERR_OK) continue;
                    $tmp = $_FILES['files']['tmp_name'][$key];
                    $ext = pathinfo($name, PATHINFO_EXTENSION);
                    $newName = uniqid('submission_', true) . "." . $ext;
                    if (move_uploaded_file($tmp, $uploadDir . $newName)) {
                        $uploadedFiles[] = $newName;
                    }
                }
            }

            // Pass user_id and classwork_id to saveFiles
            $merged = $db->saveFiles($user_id, $classwork_id, $uploadedFiles);

            echo json_encode(['status' => 'success', 'files' => $merged]);
            exit;
        }else if($_POST['requestType'] === 'RemoveFile'){
            $user_id = $_SESSION['user_id'];
            $classwork_id = $_POST['classwork_id'];
            $filename = $_POST['filename'];
            $uploadDir = '../../static/upload/';

            // Fetch current files
            $stmt = $db->conn->prepare("SELECT sw_files FROM submitted_classwork WHERE sw_classwork_id=? AND sw_user_id=?");
            $stmt->bind_param("ii", $classwork_id, $user_id);
            $stmt->execute();
            $res = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            if(!$res) {
                echo json_encode(['status'=>'error','message'=>'No submission found']); exit;
            }

            $files = $res['sw_files'] ? json_decode($res['sw_files'], true) : [];
            $files = array_filter($files, fn($f) => $f !== $filename);
            $filesJson = json_encode(array_values($files));

            // Update DB
            $stmt = $db->conn->prepare("UPDATE submitted_classwork SET sw_files=? WHERE sw_classwork_id=? AND sw_user_id=?");
            $stmt->bind_param("sii", $filesJson, $classwork_id, $user_id);
            $stmt->execute();
            $stmt->close();

            // Delete physical file if exists
            if(file_exists($uploadDir.$filename)) unlink($uploadDir.$filename);

            echo json_encode(['status'=>'success']);
            exit;
        }else if($_POST['requestType'] === 'SubmittedWorks' || $_POST['requestType'] === 'UnsubmitWork') {
            $user_id = $_SESSION['user_id'];
            $classwork_id = $_POST['classwork_id'];
            $status = $_POST['requestType'] === 'SubmittedWorks' ? 1 : 0;
            $updated = $db->updateSwStatus($status,$user_id,$classwork_id);
            echo json_encode(['status'=> $updated ? 'success' : 'error']);
            exit;
            
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
        }else if ($_POST['requestType'] == 'CreateClasswork') {
            $user_id = $_SESSION['user_id'];
            $title = $_POST['title'];
            $instructions = $_POST['instructions'];
            $room_id = $_POST['room_id']; 

            $file_upload = $_FILES['file_upload'];
            $uploadDir = '../../static/upload/';
            $fileName = null;

            if (isset($file_upload) && $file_upload['error'] === UPLOAD_ERR_OK) {
                // Get original file extension
                $fileExtension = pathinfo($file_upload['name'], PATHINFO_EXTENSION);
                // Create a clean, unique filename
                $fileName = uniqid('classwork_', true) . '.' . strtolower($fileExtension);
                $filePath = $uploadDir . $fileName;

                // Move the uploaded file
                if (!move_uploaded_file($file_upload['tmp_name'], $filePath)) {
                    echo json_encode([
                        'status' => 500,
                        'message' => 'Error uploading file.'
                    ]);
                    exit;
                }
            } elseif ($file_upload['error'] !== UPLOAD_ERR_NO_FILE && $file_upload['error'] !== 0) {
                echo json_encode([
                    'status' => 400,
                    'message' => 'Invalid file upload.'
                ]);
                exit;
            }

            // ✅ Insert into database (adjust your method to accept room_id)
            $insertedId = $db->CreateClasswork($title, $instructions, $fileName, $user_id, $room_id);

            if ($insertedId) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Classwork created successfully.',
                    'classwork_id' => $insertedId
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Failed to create classwork.'
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

            $user_id = $_SESSION['user_id'];

            $result = $db->getAllRooms($user_id);
            echo json_encode([
                'status' => 200,
                'data' => $result,
                'user_id' => $user_id,
            ]);
        }else if ($_GET['requestType'] == 'getJoinedRooms') {

            $user_id = $_SESSION['user_id'];

            $response = $db->getJoinedRooms($user_id);

            if ($response['success']) {
                echo json_encode([
                    'status' => 200,
                    'data' => $response['data'],
                    'user_id' => $user_id
                ]);
            } else {
                echo json_encode([
                    'status' => 500,
                    'message' => $response['message']
                ]);
            }
        }else if ($_GET['requestType'] == 'getCreatedRooms') {

            $user_id = $_SESSION['user_id'];

            $response = $db->getCreatedRooms($user_id);

            if ($response['success']) {
                echo json_encode([
                    'status' => 200,
                    'data' => $response['data'],
                    'user_id' => $user_id
                ]);
            } else {
                echo json_encode([
                    'status' => 500,
                    'message' => $response['message']
                ]);
            }
        }else if ($_GET['requestType'] == 'getRoomDetails') {


            $user_id = $_SESSION['user_id'];
           $code = $_GET['code'];

            if (!$code) {
                echo json_encode([
                    'status' => 400,
                    'message' => 'Missing room code'
                ]);
                exit;
            }

            $response = $db->getRoomDetails($code);

            if ($response['success']) {
                echo json_encode([
                    'status' => 200,
                    'data' => $response['data'],
                    'user_id' => $user_id
                ]);
            } else {
                echo json_encode([
                    'status' => 500,
                    'message' => $response['message'],
                    'user_id' => $user_id
                ]);
            }

        }else if ($_GET['requestType'] == 'getAllPendingClasswork') {

            $user_id = $_SESSION['user_id'];
            $room_id = $_GET['room_id'];

            if (!$room_id) {
                echo json_encode([
                    'status' => 400,
                    'message' => 'Missing room id'
                ]);
                exit;
            }
            $response = $db->getAllPendingClasswork($user_id,$room_id);

            if ($response) {
                echo json_encode([
                    'status' => 200,
                    'data' => $response
                ]);
            } else {
                echo json_encode([
                    'status' => 500,
                    'message' => $response
                ]);
            }

        }else if ($_GET['requestType'] == 'getAllSubmittedClasswork_Joiner') {
            
            $user_id = $_SESSION['user_id'];
            $room_id = $_GET['room_id'];

            if (!$room_id) {
                echo json_encode([
                    'status' => 400,
                    'message' => 'Missing room id'
                ]);
                exit;
            }
            $response = $db->getAllSubmittedClasswork_Joiner($user_id,$room_id);

            if ($response) {
                echo json_encode([
                    'status' => 200,
                    'data' => $response
                ]);
            } else {
                echo json_encode([
                    'status' => 500,
                    'message' => $response
                ]);
            }

        }else if ($_GET['requestType'] == 'get_rooms_members') {


            $room_id = $_GET['room_id'];
            $response = $db->get_rooms_members($room_id);

            if ($response) {
                echo json_encode([
                    'status' => 200,
                    'data' => $response
                ]);
            } else {
                echo json_encode([
                    'status' => 500,
                    'message' => $response
                ]);
            }

        }else if ($_GET['requestType'] === 'getClassworkDetails_all') {
                    $id = intval($_GET['classwork_id']);
                    $response = $db->getClassworkDetails_all($id);

                    if ($response) {
                        echo json_encode([
                            'status' => 200,
                            'data' => $response
                        ]);
                    } else {
                        echo json_encode([
                            'status' => 500,
                            'message' => $response
                        ]);
                    }
                    
        }else if ($_GET['requestType'] === 'getClassworkDetails_where_user_id_only') {
                    $user_id = $_SESSION['user_id'];
                    $classwork_id = intval($_GET['classwork_id']);
                    $response = $db->getClassworkDetails_where_user_id_only($user_id,$classwork_id);

                    if ($response) {
                        echo json_encode([
                            'status' => 200,
                            'data' => $response
                        ]);
                    } else {
                        echo json_encode([
                            'status' => 500,
                            'message' => $response
                        ]);
                    }
                    
        }else if ($_GET['requestType'] === 'getWorkResponses') {
                    $id = intval($_GET['classwork_id']);
                    $response = $db->getWorkResponses($id);

                    if ($response) {
                        echo json_encode([
                            'status' => 200,
                            'data' => $response
                        ]);
                    } else {
                        echo json_encode([
                            'status' => 500,
                            'message' => $response
                        ]);
                    }
                    
        }else if ($_GET['requestType'] === 'get_all_created_works') {
                    $room_id = intval($_GET['room_id']);
                    $user_id = $_SESSION['user_id'];


                    $response = $db->get_all_created_works($room_id,$user_id);

                    if ($response) {
                        echo json_encode([
                            'status' => 200,
                            'data' => $response
                        ]);
                    } else {
                        echo json_encode([
                            'status' => 500,
                            'message' => $response
                        ]);
                    }
                    
        } else{
            echo "404";
        }
    }else {
        echo 'No GET REQUEST';
    }

}
?>