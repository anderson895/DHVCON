<?php
session_start();
require '../../vendor/autoload.php';
include('../class.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

class RegisterUser {
    private $full_name;
    private $email;
    private $user_type;
    private $password;
    private $requirementsJSON;

    private $db;
    private $verificationCode;
    private $codeExpiry = 300; // 5 minutes
    private $resendCooldown = 300; // 5 minutes

    public function __construct($db, $postData = []) {
        $this->db = $db;
        $this->full_name = $postData['full_name'] ?? '';
        $this->email     = $postData['email'] ?? '';
        $this->user_type = $postData['user_type'] ?? '';
        $this->password  = $postData['password'] ?? '';

        $uploadedFiles = [];

        // ✅ Store uploaded files in session only (base64)
        if (!empty($_FILES['requirements']['name'][0])) {
            foreach ($_FILES['requirements']['name'] as $key => $fileName) {
                $fileTmpPath = $_FILES['requirements']['tmp_name'][$key];
                $fileError   = $_FILES['requirements']['error'][$key];

                if ($fileError === UPLOAD_ERR_OK) {
                    $fileData = file_get_contents($fileTmpPath);
                    $uploadedFiles[] = [
                        'name' => $fileName,
                        'type' => $_FILES['requirements']['type'][$key],
                        'size' => $_FILES['requirements']['size'][$key],
                        'content' => base64_encode($fileData)
                    ];
                } else {
                    error_log("⚠️ Upload error for $fileName: $fileError");
                }
            }
        }

        $this->requirementsJSON = json_encode($uploadedFiles);
    }

    public function register() {
        if ($this->isEmailExist()) {
            return ['status' => 'error', 'message' => 'Email already registered.'];
        }

        $this->generateVerificationCode();
        $this->storeSession();

        if ($this->sendVerificationEmail()) {
            return ['status' => 'success', 'message' => 'Verification code sent!'];
        }

        return ['status' => 'error', 'message' => 'Failed to send verification email.'];
    }

    public function resendVerification() {
        if (!isset($_SESSION['register_data'])) {
            return ['status' => 'error', 'message' => 'No registration session found.'];
        }

        $user = &$_SESSION['register_data'];
        $current_time = time();

        if (!isset($user['last_resend_time'])) {
            $user['last_resend_time'] = 0;
        }

        $remainingCooldown = $this->resendCooldown - ($current_time - $user['last_resend_time']);
        if ($remainingCooldown > 0) {
            return ['status' => 'error', 'message' => "Please wait {$remainingCooldown} seconds before resending."];
        }

        $user['verification_code'] = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $user['code_generated_time'] = $current_time;
        $user['last_resend_time'] = $current_time;

        $this->full_name = $user['full_name'];
        $this->email = $user['email'];
        $this->verificationCode = $user['verification_code'];

        if ($this->sendVerificationEmail()) {
            return ['status' => 'success', 'message' => 'Verification code resent!'];
        }

        return ['status' => 'error', 'message' => 'Failed to resend verification email.'];
    }

    private function isEmailExist() {
        return $this->db->isEmailExist($this->email);
    }

    private function generateVerificationCode() {
        $this->verificationCode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    private function storeSession() {
        $_SESSION['register_data'] = [
            'full_name' => $this->full_name,
            'email' => $this->email,
            'password' => $this->password,
            'user_type' => $this->user_type,
            'requirements' => json_decode($this->requirementsJSON, true),
            'verification_code' => $this->verificationCode,
            'code_generated_time' => time(),
            'last_resend_time' => 0
        ];
    }

 

    private function sendVerificationEmail() {
        $fullname = $this->full_name;
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'rodriguezryan325@gmail.com';
            $mail->Password = 'ofvf yxut wpcc iecx';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('rodriguezryan325@gmail.com', 'DHVCON');
            $mail->addAddress($this->email, $fullname);
            $mail->addReplyTo('rodriguezryan325@gmail.com', 'No Reply');

            $mail->isHTML(true);
            $mail->Subject = 'DHVCON Verification Code';
            $mail->Body = "
                <h2>Hello $fullname!</h2>
                <p>Your verification code is: <b>{$this->verificationCode}</b></p>
                <p>This code will expire in 5 minutes.</p>
            ";

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Verification email error: " . $e->getMessage());
            return false;
        }
    }
}

// --- Handle requests ---
$db = new global_class();
$requestType = $_POST['requestType'] ?? '';

if ($requestType === 'Register') {
    $register = new RegisterUser($db, $_POST);
    echo json_encode($register->register());
    exit;
}

if ($requestType === 'ResendVerification') {
    $register = new RegisterUser($db);
    echo json_encode($register->resendVerification());
    exit;
}

echo json_encode(['status' => 'error', 'message' => 'Invalid request type.']);
