<?php
session_start();
include "src/components/header.php"; // optional header

$token = $_GET['token'] ?? '';

if (!$token) {
    echo "<p class='text-red-500 text-center mt-10'>Invalid or missing reset token.</p>";
    exit;
}
?>

<main class="min-h-screen flex items-center justify-center bg-gray-900 text-white p-4">
    <div class="w-full max-w-md bg-[#1f2023]/80 backdrop-blur-xl border border-[#3c3f44] rounded-3xl shadow-2xl p-10 space-y-6">
        
        <h2 class="text-3xl font-bold text-center text-indigo-400">Reset Password</h2>
        <p class="text-gray-400 text-sm text-center">Enter your new password below.</p>

        <form id="frmResetToken" class="space-y-4">
            <input type="hidden" id="token" value="<?= htmlspecialchars($token) ?>">
            <!-- New Password -->
            <div>
                <label for="new_pass" class="block text-sm font-medium text-gray-300 mb-2">New Password</label>
                <div class="relative border border-[#3c3f44] bg-[#232428] rounded-2xl focus-within:ring-2 focus-within:ring-indigo-500 transition duration-300">
                    <input type="password" id="new_pass" class="w-full pl-4 pr-10 py-3 bg-transparent text-white focus:outline-none rounded-2xl placeholder-gray-500" placeholder="Enter new password" required>
                    <span class="material-icons-outlined absolute right-3 top-1/2 -translate-y-1/2 cursor-pointer text-gray-400" onclick="togglePassword('new_pass', this)">visibility_off</span>
                </div>
            </div>
            <!-- Confirm Password -->
            <div>
                <label for="confirm_pass" class="block text-sm font-medium text-gray-300 mb-2">Confirm Password</label>
                <div class="relative border border-[#3c3f44] bg-[#232428] rounded-2xl focus-within:ring-2 focus-within:ring-indigo-500 transition duration-300">
                    <input type="password" id="confirm_pass" class="w-full pl-4 pr-10 py-3 bg-transparent text-white focus:outline-none rounded-2xl placeholder-gray-500" placeholder="Confirm new password" required>
                    <span class="material-icons-outlined absolute right-3 top-1/2 -translate-y-1/2 cursor-pointer text-gray-400" onclick="togglePassword('confirm_pass', this)">visibility_off</span>
                </div>
            </div>
            <!-- Submit Button -->
            <button type="submit" class="w-full flex items-center justify-center gap-2 cursor-pointer bg-indigo-600 hover:bg-indigo-500 font-semibold py-3 rounded-full shadow-lg transition transform hover:scale-[1.03]">
                <span class="material-icons-outlined">lock_reset</span>
                Reset Password
            </button>
        </form>

    </div>
</main>
<?php include "src/components/footer.php"; ?>
<script>
function togglePassword(fieldId, icon) {
    const input = document.getElementById(fieldId);
    if (input.type === "password") {
        input.type = "text";
        icon.textContent = "visibility";
    } else {
        input.type = "password";
        icon.textContent = "visibility_off";
        
    }
}
</script>
<script src="static/js/forgotpassword.js"></script>