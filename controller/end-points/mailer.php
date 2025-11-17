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

        // Store uploaded requirement files (base64)
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
            $mail->Username = 'jessbertsoliguin456@gmail.com'; 
            $mail->Password = 'fcdc wynb xmsf clnl';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('jessbertsoliguin456@gmail.com', 'DHVCON');
            $mail->addAddress($this->email, $fullname);
            $mail->addReplyTo('jessbertsoliguin456@gmail.com', 'No Reply');

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

/* -------------------------------------------------------------
    FORGOT PASSWORD IMPLEMENTATION
--------------------------------------------------------------*/

// Send reset link to email
function sendResetEmail($email, $token) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'jessbertsoliguin456@gmail.com';
        $mail->Password = 'fcdc wynb xmsf clnl';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('jessbertsoliguin456@gmail.com', 'DHVCON');
        $mail->addAddress($email);

        $resetLink = "https://dhvcon.space/reset-password.php?token=$token";

        $mail->isHTML(true);
        $mail->Subject = "Password Reset Request";
        $mail->Body = "
            <h2>Password Reset</h2>
            <p>Click below to reset your password:</p>
            <a href='$resetLink'>$resetLink</a>
            <p>This link expires in 1 hour.</p>
        ";

        $mail->send();
        return true;

    } catch (Exception $e) {
        return false;
    }
}

/* -------------------------------------------------------------
    HANDLE REQUEST TYPES
--------------------------------------------------------------*/

$db = new global_class();
$requestType = $_POST['requestType'] ?? '';

/* --- Registration --- */
if ($requestType === 'Register') {
    $reg = new RegisterUser($db, $_POST);
    echo json_encode($reg->register());
    exit;
}

/* --- Resend Verification --- */
if ($requestType === 'ResendVerification') {
    $reg = new RegisterUser($db);
    echo json_encode($reg->resendVerification());
    exit;
}

/* --- Forgot Password Request --- */
if ($requestType === 'ForgotPassword') {
    $email = $_POST['email'] ?? '';

    $user = $db->getUserByEmail($email);
    if (!$user) {
        echo json_encode(['status' => 'error', 'message' => 'Email not found.']);
        exit;
    }

    // Generate token
    $token = bin2hex(random_bytes(16));
    $expiry = time() + 3600; // 1 hr

    if (!$db->saveResetToken($email, $token, $expiry)) {
        echo json_encode(['status' => 'error', 'message' => 'Unable to save token.']);
        exit;
    }

    if (sendResetEmail($email, $token)) {
        echo json_encode(['status' => 'success', 'message' => 'Password reset link sent.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to send email.']);
    }

    exit;
}

/* --- Reset Password Final Step --- */
if ($requestType === 'ResetPassword') {
    $token = $_POST['token'];
    $newPassword = $_POST['newPassword'];

    $user = $db->validateResetToken($token);
    if (!$user) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid or expired token.']);
        exit;
    }

    $hash = password_hash($newPassword, PASSWORD_DEFAULT);

    if ($db->updatePasswordByToken($token, $hash)) {
        echo json_encode(['status' => 'success', 'message' => 'Password updated successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update password.']);
    }

    exit;
}

/* --- If no request type matched --- */
echo json_encode(['status' => 'error', 'message' => 'Invalid request type.']);
