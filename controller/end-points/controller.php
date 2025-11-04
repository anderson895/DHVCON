<?php
include('../class.php');

$db = new global_class();

session_start();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['requestType'])) {
       if ($_POST['requestType'] == 'SignUp') {

            if (!isset($_SESSION['register_data'])) {
                echo json_encode(['status' => 'error', 'message' => 'No registration data found.']);
                exit;
            }

            $registerData = $_SESSION['register_data'];

            // --- Expiration check (5 minutes) ---
            if (time() - $registerData['code_generated_time'] > 300) {
                unset($_SESSION['register_data']);
                echo json_encode(['status' => 'error', 'message' => 'Verification code expired.']);
                exit;
            }

            // --- Verification code check ---
            if (!isset($_POST['verification_code'])) {
                echo json_encode(['status' => 'error', 'message' => 'Verification code is required.']);
                exit;
            }

            if ($_POST['verification_code'] !== $registerData['verification_code']) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid verification code.']);
                exit;
            }

            // --- Move files from session → permanent folder ---
            $finalFileNames = []; // store exact filenames for DB

            if (!empty($registerData['requirements'])) {

                $uploadDir = __DIR__ . '/../../static/upload/requirements/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

                foreach ($registerData['requirements'] as $req) {
                    $cleanFileName = preg_replace("/[^a-zA-Z0-9_\.-]/", "_", basename($req['name']));
                    $newFileName = uniqid() . '_' . $cleanFileName;
                    $destination = $uploadDir . $newFileName;

                    // Decode base64 content and save file
                    $decoded = base64_decode($req['content']);
                    if (file_put_contents($destination, $decoded) !== false) {
                        $finalFileNames[] = $newFileName; // store for DB
                    } else {
                        error_log("❌ Failed to save file: {$req['name']} → $destination");
                    }
                }
            }

            // --- Save user to DB ---
            $full_name    = $registerData['full_name'];
            $email        = $registerData['email'];
            $password     = $registerData['password'];
            $user_type    = $registerData['user_type'];

            // Use exact filenames saved on disk
            $requirementsJSON = json_encode($finalFileNames);

            $result = $db->SignUp($full_name, $email, $password, $user_type, $requirementsJSON);

            if ($result['success']) {
                // Clear session after successful registration
                unset($_SESSION['register_data']);
                echo json_encode(['status' => 'success', 'message' => $result['message']]);
            } else {
                echo json_encode(['status' => 'error', 'message' => $result['message']]);
            }
        }else if ($_POST['requestType'] == 'removeUserFromMeeting') {
            $meeting_id = $_POST['meeting_id'] ?? null;
            $user_id = $_POST['user_id'] ?? null;

            if (!$meeting_id || !$user_id) {
                echo json_encode([
                    "status" => 400,
                    "message" => "Invalid request."
                ]);
                exit;
            }

            if ($db->removeUserFromMeeting($meeting_id, $user_id)) {
                echo json_encode([
                    "status" => 200,
                    "message" => "User removed successfully."
                ]);
            } else {
                echo json_encode([
                    "status" => 500,
                    "message" => "Failed to remove user."
                ]);
            }
            exit;
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

        }else if ($_POST['requestType'] == 'ratingMeeting') {
               $user_id = $_SESSION['user_id'];
                $meeting_id = $_POST['meeting_id'];
                $rating = $_POST['rating'];

                // Correct parameter order
                $result = $db->ratingMeeting($meeting_id, $rating, $user_id);

                if ($result['status'] === 'success') {
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


        }else if ($_POST['requestType'] == 'updateJoinRequest') {
            $jr_id = $_POST['jr_id'] ?? null;
            $action = $_POST['action'] ?? null; // 'approved' or 'rejected'

            // Validate input
            if (!$jr_id || !in_array($action, ['approved', 'rejected'])) {
                echo json_encode([
                    "status" => 400,
                    "message" => "Invalid request."
                ]);
                exit;
            }

            // Prepare update
            $stmt = $db->updateJoinRequestStatus($jr_id, $action);
            if (!$stmt) {
                echo json_encode([
                    "status" => 500,
                    "message" => "Failed to prepare update query."
                ]);
                exit;
            }

            // Execute update
            if ($stmt->execute()) {
                echo json_encode([
                    "status" => 200,
                    "message" => "Join request updated successfully."
                ]);
            } else {
                echo json_encode([
                    "status" => 500,
                    "message" => "Failed to update join request."
                ]);
            }

            $stmt->close();
            exit;

        }else if ($_POST['requestType'] == 'createRoom') {
                $user_id = $_SESSION['user_id'];
                $roomName = $_POST['roomName'];
                $roomDescription = $_POST['roomDescription'];

                $roomBanner = isset($_FILES['roomBanner']) ? $_FILES['roomBanner'] : null;
                $uploadDir = '../../static/upload/';
                $roomImageFileName = null;

                // Handle banner upload
                if ($roomBanner && $roomBanner['error'] === UPLOAD_ERR_OK) {
                    $ext = pathinfo($roomBanner['name'], PATHINFO_EXTENSION);
                    $roomImageFileName = uniqid('room_', true) . '.' . $ext;
                    $bannerPath = $uploadDir . $roomImageFileName;

                    if (!move_uploaded_file($roomBanner['tmp_name'], $bannerPath)) {
                        echo json_encode(['status' => 500, 'message' => 'Error uploading room banner image.']);
                        exit;
                    }
                } elseif ($roomBanner && $roomBanner['error'] !== UPLOAD_ERR_NO_FILE) {
                    echo json_encode(['status' => 400, 'message' => 'Invalid image upload.']);
                    exit;
                }

                $insertedId = $db->createRoom($roomName, $roomDescription, $roomImageFileName, $user_id);

                if ($insertedId) {
                    echo json_encode(['status' => 200, 'message' => 'Room created successfully.', 'room_id' => $insertedId]);
                } else {
                    echo json_encode(['status' => 500, 'message' => 'Failed to create room.']);
                }

            }else if ($_POST['requestType'] === 'sendchats') {
                $user_id = $_SESSION['user_id'];

                $roomCode = $_POST['roomCode'] ?? '';
                $message = trim($_POST['message'] ?? '');

                if ($message && $roomCode) {
                    // Assuming your sendchats function signature is: sendchats($message, $senderId, $roomCode)
                    $result = $db->sendchats($message, $user_id, $roomCode);

                    if ($result) {
                        echo json_encode(['success' => true, 'message' => 'Message sent']);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Failed to send message']);
                    }
                } else {
                    echo json_encode(['success' => false, 'message' => 'Message or RoomCode missing']);
                }

            } else if ($_POST['requestType'] === 'loadchats') {
                $user_id = $_SESSION['user_id'];
                $roomCode = $_POST['roomCode'] ?? '';

                if ($roomCode) {
                    // Assuming your loadchats function signature is: loadchats($userId, $roomCode)
                    $messages = $db->loadchats($user_id, $roomCode);

                    echo json_encode([
                        'success' => true,
                        'data' => $messages
                    ]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'RoomCode missing']);
                }

            }elseif ($_POST['requestType'] == 'updateRoom') {
                $user_id = $_SESSION['user_id'];
                $roomId = $_POST['room_id'];
                $roomName = $_POST['roomName'];
                $roomDescription = $_POST['roomDescription'];

                $roomBanner = isset($_FILES['roomBanner']) ? $_FILES['roomBanner'] : null;
                $uploadDir = '../../static/upload/';
                $roomImageFileName = null;

                // Handle new banner upload if provided
                if ($roomBanner && $roomBanner['error'] === UPLOAD_ERR_OK) {
                    $ext = pathinfo($roomBanner['name'], PATHINFO_EXTENSION);
                    $roomImageFileName = uniqid('room_', true) . '.' . $ext;
                    $bannerPath = $uploadDir . $roomImageFileName;

                    if (!move_uploaded_file($roomBanner['tmp_name'], $bannerPath)) {
                        echo json_encode(['status' => 500, 'message' => 'Error uploading room banner image.']);
                        exit;
                    }
                } elseif ($roomBanner && $roomBanner['error'] !== UPLOAD_ERR_NO_FILE) {
                    echo json_encode(['status' => 400, 'message' => 'Invalid image upload.']);
                    exit;
                }

                $updated = $db->updateRoom($roomId, $roomName, $roomDescription, $roomImageFileName, $user_id);

                if ($updated) {
                    echo json_encode(['status' => 200, 'message' => 'Room updated successfully.', 'room_id' => $roomId]);
                } else {
                    echo json_encode(['status' => 500, 'message' => 'Failed to update room.']);
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
            
        }else if($_POST['requestType'] === 'fetchUsers') {

            $filter = $_POST['filter'] ?? null;
            $data = $db->getUsers($filter);
            echo json_encode(["success" => true, "data" => $data]);
           
            
        }else if($_POST['requestType'] === 'updateStatus') {

            $id = intval($_POST['id']);
            $status = intval($_POST['status']);
            $ok = $db->updateUserStatus($id, $status);
            echo json_encode(["success" => $ok]);
           
            
        }else if($_POST['requestType'] === 'updateProfile') {
            
            $user_id = $_SESSION['user_id'];
            $fullname = trim($_POST['fullname']);
            $email = trim($_POST['email']);



            $file_upload = $_FILES['profilePic'];
            $uploadDir = '../../static/upload/profile/';
            $fileName = null;

            if (isset($file_upload) && $file_upload['error'] === UPLOAD_ERR_OK) {
                // Get original file extension
                $fileExtension = pathinfo($file_upload['name'], PATHINFO_EXTENSION);
                // Create a clean, unique filename
                $fileName = uniqid('profile_', true) . '.' . strtolower($fileExtension);
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


            $res = $db->updateProfile($user_id, $fullname, $email,$fileName);
            echo json_encode(['success' => $res]);
           
            
        }else if($_POST['requestType'] === 'updatePassword') {

            $user_id = $_SESSION['user_id'];
            $old_pass = $_POST['old_password'];
            $new_pass = $_POST['new_password'];

            $res = $db->updatePassword($user_id, $old_pass, $new_pass);
            echo json_encode(['success' => $res]);
           
            
        }else if ($_POST['requestType'] == 'Login') {
                    $email = $_POST['email'];
                    $password = $_POST['password'];
                    $loginResult = $db->Login($email, $password);

                    if ($loginResult['success']) {

                        echo json_encode([
                            'status' => 'success',
                            'message' => $loginResult['message'],
                            'data' => isset($loginResult['data']) ? $loginResult['data'] : null
                        ]);

                    } else {
                        echo json_encode([
                            'status' => 'error',
                            'message' => $loginResult['message']
                        ]);
                    }

        }else if ($_POST['requestType'] == 'recordMeetingLog') {
                    $user_id = $_SESSION['user_id'];
                    $meeting_id = $_POST['meeting_id'];
                     $result = $db->recordMeetingLog($meeting_id, $user_id);

                    if ($result === 'exists') {
                        echo json_encode(['status' => 409, 'message' => 'Already logged']);
                    } elseif ($result) {
                        echo json_encode(['status' => 200, 'message' => 'Log recorded']);
                    } else {
                        echo json_encode(['status' => 500, 'message' => 'Error recording log']);
                    }
        }else if ($_POST['requestType'] == 'requestToJoin') {
                    $user_id = $_SESSION['user_id'];

                    $meeting_id = $_POST['meeting_id'];
                     $result = $db->requestToJoin($meeting_id, $user_id);

                    if ($result === 'exists') {
                        echo json_encode(['status' => 409, 'message' => 'Already sent']);
                    } elseif ($result) {
                        echo json_encode(['status' => 200, 'message' => 'Request sent']);
                    } else {
                        echo json_encode(['status' => 500, 'message' => 'Error recording log']);
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
        }else if ($_POST['requestType'] == 'CreateMeeting') {
            $user_id = $_SESSION['user_id'];
            $meeting_link = $_POST['meeting_link'];
            $meeting_title = $_POST['meeting_title'];
            $meeting_description = $_POST['meeting_description'];
            $start_date = $_POST['start_date'];
            $end_date = $_POST['end_date'];
            $room_id = $_POST['room_id']; 

            $insertedId = $db->CreateMeeting($user_id, $meeting_link, $meeting_title, $meeting_description, $start_date, $end_date, $room_id);

            if ($insertedId) {
                echo json_encode([
                    'status' => 'success',
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                    'message' => 'Classwork created successfully.',
                    'classwork_id' => $insertedId
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                    'message' => 'Failed to create classwork.'
                ]);
            }
        }else if ($_POST['requestType'] == 'closeMeeting') {
            $meeting_id = $_POST['meeting_id'];

            $insertedId = $db->CloseMeeting($meeting_id);

            if ($insertedId) {
                echo json_encode(['status' => 200]);
            } else {
                echo json_encode(['status' => 500, 'message' => 'Failed to close meeting.']);
            }
            exit;
            
        }else if ($_POST['requestType'] == 'LeaveRoom') {
            $room_code = $_POST['room_code'];
            $user_id = $_SESSION['user_id'];
            $result = $db->LeaveRoom($room_code,$user_id );

            if ($result) {
                echo json_encode(['status' => 200]);
            } else {
                echo json_encode(['status' => 500, 'message' => 'Failed to close meeting.']);
            }
            exit;
            
        }else if ($_POST['requestType'] == 'deleteRoom') {
            $room_id = $_POST['room_id'];

            $result = $db->deleteRoom($room_id);

            if ($result) {
                echo json_encode(['status' => 200]);
            } else {
                echo json_encode(['status' => 500, 'message' => 'Failed to close meeting.']);
            }
            exit;
            
        }


            // =========================
            // 🔹 Update Classwork
            // =========================
            else if ($_POST['requestType'] == 'UpdateClasswork') {
                $classwork_id = $_POST['classwork_id'];
                $title = $_POST['title'];
                $instructions = $_POST['instructions'];

                $file_upload = $_FILES['file_upload'];
                $uploadDir = '../../static/upload/';
                $fileName = null;

                if (isset($file_upload) && $file_upload['error'] === UPLOAD_ERR_OK) {
                    $fileExtension = pathinfo($file_upload['name'], PATHINFO_EXTENSION);
                    $fileName = uniqid('classwork_', true) . '.' . strtolower($fileExtension);
                    $filePath = $uploadDir . $fileName;

                    if (!move_uploaded_file($file_upload['tmp_name'], $filePath)) {
                        echo json_encode([
                            'status' => 500,
                            'message' => 'Error uploading file.'
                        ]);
                        exit;
                    }
                }

                $updated = $db->UpdateClasswork($classwork_id, $title, $instructions, $fileName);

                if ($updated) {
                    echo json_encode([
                        'status' => 'success',
                        'message' => 'Classwork updated successfully.'
                    ]);
                } else {
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Failed to update classwork.'
                    ]);
                }
            }


            // =========================
            // 🔹 Delete Classwork
            // =========================
            else if ($_POST['requestType'] == 'DeleteClasswork') {
                $classwork_id = $_POST['classwork_id'];

                $deleted = $db->DeleteClasswork($classwork_id);

                if ($deleted) {
                    echo json_encode([
                        'status' => 'success',
                        'message' => 'Classwork deleted successfully.'
                    ]);
                } else {
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Failed to delete classwork.'
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
                    
        }else if ($_GET['requestType'] === 'getMeetingsByRoom') {
                    $room_id = intval($_GET['room_id']);
                    $user_id = $_SESSION['user_id']; // current logged-in user

                    $response = $db->GetMeetingsByRoom($room_id, $user_id); // ✅ pass user_id

                    if ($response) {
                        echo json_encode([
                            'status' => 200,
                            'user_id' => $user_id,
                            'data' => $response
                        ]);
                    } else {
                        echo json_encode([
                            'status' => 500,
                            'user_id' => $user_id,
                            'message' => 'Failed to fetch meetings'
                        ]);
                    }

                    
        }else if ($_GET['requestType'] === 'viewMeetingLogs') {
                $meeting_id = intval($_GET['meeting_id']);
                $response = $db->viewMeetingLogs($meeting_id);

                if ($response === false) {
                    echo json_encode(['status' => 500, 'message' => 'Error fetching meeting logs.']);
                } elseif (empty($response)) {
                    echo json_encode(['status' => 404, 'message' => 'No logs found.']);
                } else {
                    echo json_encode(['status' => 200, 'data' => $response]);
                }
                exit;

        }else if ($_GET['requestType'] == 'getRoomById') {

            $room_id = $_GET['room_id'];
            $response = $db->getRoomById($room_id);

            if ($response['success']) {
                echo json_encode([
                    'status' => 200,
                    'data' => $response['data']
                ]);
            } else {
                echo json_encode([
                    'status' => 500,
                    'message' => $response['message']
                ]);
            }


        }else if ($_GET['requestType'] == 'fetchAllClaimedCertificates') {

            $room_id = $_GET['room_id'];
            $user_id = $_SESSION['user_id'];
            
            $response = $db->fetchAllClaimedCertificates($user_id, $room_id);

            if ($response['status'] === 200) {
                echo json_encode([
                    'status' => 200,
                    'data' => $response['data'],
                    'user_id'=>$user_id
                ]);
            } else {
                echo json_encode([
                    'status' => 500,
                    'message' => 'Something went wrong'
                ]);
            }


        }else if ($_GET['requestType'] == 'dashboard_analytics') {
         
              $data = $db->getDataAnalytics();

                if ($data) {
                    echo json_encode([
                        'success' => true,
                        'data' => $data
                    ]);
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Failed to retrieve analytics'
                    ]);
                }

        }else if ($_GET['requestType'] == 'updateStatus') {
         
              $data = $db->getDataAnalytics();

                if ($data) {
                    echo json_encode([
                        'success' => true,
                        'data' => $data
                    ]);
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Failed to retrieve analytics'
                    ]);
                }

        }else if ($_GET['requestType'] == 'GetClassworkById') {
                $classwork_id = $_GET['classwork_id'];
                $result = $db->GetClassworkById($classwork_id);

                if ($result) {
                    echo json_encode([
                        'status' => 'success',
                        'data' => $result
                    ]);
                } else {
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Classwork not found.'
                    ]);
                }
        }else if ($_GET['requestType'] == 'checkMemberStatus') {
                $user_id = $_SESSION['user_id'] ?? null;
                $meeting_id = $_GET['meeting_id'] ?? null;

                // Validate inputs
                if (!$user_id || !$meeting_id || !is_numeric($meeting_id)) {
                    echo json_encode([
                        "status" => 400,
                        "message" => "Invalid user or meeting ID."
                    ]);
                    exit;
                }

                // Check member status
                $status = $db->checkMemberStatus($meeting_id, $user_id);

                if ($status === false) {
                    // Query failed
                    echo json_encode([
                        "status" => 500,
                        "message" => "Failed to fetch join request status."
                    ]);
                } elseif ($status === null) {
                    // User is neither creator nor member
                    echo json_encode([
                        "status" => 200,
                        "message" => "Success",
                        "data" => [
                            "join_request_status" => "Not member"
                        ]
                    ]);
                } else {
                    // User is creator or member
                    echo json_encode([
                        "status" => 200,
                        "message" => "Success",
                        "data" => [
                            "join_request_status" => $status
                        ]
                    ]);
                }

                exit;



        }else if ($_GET['requestType'] == 'checkPendingRequests') {
            $meeting_id=$_GET['meeting_id'];
            $pendingCount = $db->getPendingJoinRequests($meeting_id); 
            echo json_encode([
                "status" => 200,
                "message" => "Success",
                "data" => [
                    "pending_count" => $pendingCount
                ]
            ]);
            exit;


        }else if ($_GET['requestType'] == 'getPendingRequestsDetails') {
           $meeting_id = $_GET['meeting_id'] ?? null;

            if (!$meeting_id) {
                echo json_encode([
                    "status" => 400,
                    "message" => "Meeting ID is required.",
                    "data" => []
                ]);
                exit;
            }

            // Call the class method
            $pendingRequests = $db->getPendingRequestsDetails($meeting_id);

            echo json_encode([
                "status" => 200,
                "message" => "Success",
                "data" => $pendingRequests
            ]);
            exit;

        }else if ($_GET['requestType'] == 'getApprovedUsers') {
            $meeting_id = $_GET['meeting_id'] ?? null;

            $approvedUsers = $db->getApprovedUsers($meeting_id);
            echo json_encode([
                "status" => 200,
                "message" => "Success",
                "data" => $approvedUsers
            ]);
            exit;

        }else if ($_GET['requestType'] == 'get_users_data') {
            $user_id = $_GET['user_id'] ?? null;

            // Fetch the profile image from DB
            $result = $db->get_users_data($user_id);

            echo json_encode([
                "status" => 200,
                "message" => "Success",
                "data" => $result ?? null
            ]);
            exit;




            exit;
        }else if ($_GET['requestType'] == 'generateMeetingCode') {

               function generateCode() {
                    $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                    $code = 'MTG-';
                    for ($i = 0; $i < 6; $i++) {
                        $code .= $chars[rand(0, strlen($chars) - 1)];
                    }
                    return $code;
                }

                // Loop until a unique code is found
                do {
                    $code = generateCode();
                    $exists = $db->checkMeetingLink($code);
                } while ($exists); 
                
                echo json_encode([
                    'status' => 'success',
                    'meeting_code' => $code
                ]);
                exit;


        }else{
            echo "404";
        }
    }else {
        echo 'No GET REQUEST';
    }

}
?>