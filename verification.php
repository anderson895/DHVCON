<?php
session_start();
include "src/components/header.php";

// Redirect if no registration data
if (!isset($_SESSION['register_data'])) {
    header('Location: register.php');
    exit;
}

$user = &$_SESSION['register_data'];

// Verification code expiration (5 minutes)
$timeLeft = max(0, 300 - (time() - $user['code_generated_time']));

// Resend cooldown (5 minutes)
if (!isset($user['last_resend_time'])) {
    $user['last_resend_time'] = 0;
}
$resendCooldown = max(0, 300 - (time() - $user['last_resend_time']));
?>

<div class="min-h-screen flex flex-col items-center justify-center bg-gray-50 px-4">

    <p class="mb-4 text-blue-500 text-lg font-medium">
        Verification code expires in: <span id="timer" class="font-semibold text-black"><?= $timeLeft ?>s</span>
    </p>

    <div class="bg-white p-8 rounded-2xl shadow-md w-full max-w-md text-center border border-gray-200">
        <h2 class="text-3xl font-semibold text-gray-800 mb-6">Verify Your Account</h2>

        <!-- Uploaded Requirements Display -->
        <?php if (!empty($user['requirements'])): ?>
            <div class="bg-gray-100 p-4 rounded-lg mb-6 text-left border border-gray-300" hidden>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Uploaded Requirements:</h3>
                <ul class="list-disc list-inside text-gray-600 space-y-1">
                    <?php foreach ($user['requirements'] as $req): ?>
                        <li>
                            <span class="font-medium text-gray-800"><?= htmlspecialchars($req['name']) ?></span>
                            <small class="text-gray-500">
                                (<?= round($req['size'] / 1024, 2) ?> KB)
                            </small>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php else: ?>
            <p class="text-gray-500 mb-6">No uploaded requirements found in session.</p>
        <?php endif; ?>
        <!-- End Uploaded Requirements -->

        <form id="frmVerify" class="space-y-6">
            <div class="flex justify-between gap-2">
                <?php for ($i = 0; $i < 6; $i++): ?>
                    <input 
                        type="text" 
                        maxlength="1" 
                        class="otp-box w-12 h-12 text-center text-gray-800 bg-gray-100 border border-gray-300 rounded-lg text-2xl focus:outline-none focus:ring-2 focus:ring-blue-400 focus:bg-white transition"
                    />
                <?php endfor; ?>
            </div>

            <button 
                type="submit" 
                id="btnVerify" 
                class="cursor-pointer w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 rounded-lg shadow-sm transition duration-200"
            >
                Verify
            </button>

            <button 
                id="resendCode" 
                class="cursor-pointer w-full mt-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-3 rounded-lg transition duration-200"
                type="button"
            >
                Resend Verification Code
            </button>
        </form>
    </div>
</div>

<?php include "src/components/footer.php"; ?>


<script>
$(document).ready(function() {
    const $otpInputs = $('.otp-box');
    const $btnVerify = $('#btnVerify');
    const $resendBtn = $('#resendCode');
    const $timer = $('#timer');

    let timeLeft = <?php echo $timeLeft; ?>;
    let resendCooldown = <?php echo $resendCooldown; ?>; 
    let expiredNotified = false;
    let countdown;

    $otpInputs.first().focus();

    function startCountdown() {
        clearInterval(countdown);
        expiredNotified = false;

        countdown = setInterval(function() {
            if (timeLeft < 0) timeLeft = 0;

            const minutes = Math.floor(timeLeft / 60).toString().padStart(2,'0');
            const seconds = (timeLeft % 60).toString().padStart(2,'0');
            $timer.text(`${minutes}:${seconds}`);

            if (timeLeft === 0 && !expiredNotified) {
                $otpInputs.prop('disabled', true);
                $btnVerify.prop('disabled', true).addClass('bg-gray-400 cursor-not-allowed');
                alertify.error('Verification code expired.');
                expiredNotified = true;
            }

            if (resendCooldown > 0) {
                resendCooldown--;
                const rMin = Math.floor(resendCooldown / 60).toString().padStart(2,'0');
                const rSec = (resendCooldown % 60).toString().padStart(2,'0');
                $resendBtn.prop('disabled', true)
                          .text(`Resend available in ${rMin}:${rSec}`)
                          .addClass('bg-gray-300 cursor-not-allowed');
            } else {
                $resendBtn.prop('disabled', false)
                          .text('Resend Verification Code')
                          .removeClass('bg-gray-300 cursor-not-allowed');
            }

            timeLeft--;
        }, 1000);
    }

    startCountdown();

    // Auto-focus next input
    $otpInputs.on('input', function() {
        const nextInput = $(this).next('.otp-box');
        if (this.value.length === 1 && nextInput.length) nextInput.focus();
    });

    // Handle backspace
    $otpInputs.on('keydown', function(e) {
        if (e.key === "Backspace" && !this.value) {
            $(this).prev('.otp-box').focus();
        }
    });

    // Form submission
    $("#frmVerify").submit(function(e) {
        e.preventDefault();

        let code = '';
        $otpInputs.each(function() { code += $(this).val().trim(); });

        if (code.length < 6) {
            alertify.error('Please enter the full verification code.');
            return;
        }

        $btnVerify.prop('disabled', true).text('Verifying...');

        $.ajax({
            type: "POST",
            url: "controller/end-points/controller.php",
            data: { verification_code: code, requestType: "SignUp" },
            dataType: 'json',
            success: function(response) {
                $btnVerify.prop('disabled', false).text('Verify');

                if (response.status === 'success') {
                    Swal.fire({
                        icon: "success",
                        title: "Account Created!",
                        text: "Please wait for the admin’s approval.",
                        confirmButtonColor: "#28a745",
                        timer: 2500,
                        timerProgressBar: true,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = "signin";
                    });
                } else {
                    alertify.error(response.message);
                    $otpInputs.val('').first().focus();
                }
            },
            error: function() {
                $btnVerify.prop('disabled', false).text('Verify');
                alertify.error('An error occurred. Please try again.');
            }
        });
    });

    // Resend button
    $resendBtn.click(function(e) {
        e.preventDefault();

        if (resendCooldown > 0) {
            const rMin = Math.floor(resendCooldown / 60).toString().padStart(2,'0');
            const rSec = (resendCooldown % 60).toString().padStart(2,'0');
            alertify.warning(`You can resend the code in ${rMin}:${rSec} minutes.`);
            return;
        }

        Swal.fire({
            title: 'Resending...',
            text: 'Please wait while we resend your verification code.',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => Swal.showLoading()
        });

        $.ajax({
            type: "POST",
            url: "controller/end-points/mailer.php",
            data: { requestType: "ResendVerification" },
            dataType: 'json',
            success: function(response) {
                Swal.close();

                if (response.status === 'success') {
                    alertify.success('Verification code resent! Timer reset to 5 minutes.');

                    $otpInputs.prop('disabled', false).val('');
                    $btnVerify.prop('disabled', false);
                    $otpInputs.first().focus();

                    timeLeft = 300;
                    resendCooldown = 300;

                    startCountdown();
                } else {
                    alertify.error(response.message);
                }
            },
            error: function() {
                Swal.close();
                alertify.error('Error sending verification code. Try again.');
            }
        });
    });
});
</script>
