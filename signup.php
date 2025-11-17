<?php 
  include "src/components/header.php";
?>
<!-- Sign Up Page -->
<main class="relative min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-900 via-gray-800 to-black text-white overflow-hidden">

  <!-- Background Decorative Light (Indigo only) -->
  <div class="absolute top-0 left-0 w-96 h-96 bg-indigo-600 opacity-20 blur-3xl animate-pulse"></div>

  <!-- Fullscreen Spinner Overlay -->
  <div id="spinner" class="fixed inset-0 flex items-center justify-center z-[9998] bg-black/60 backdrop-blur-sm" style="display:none;">
    <div class="w-16 h-16 border-4 border-gray-600 border-t-indigo-500 rounded-full animate-spin"></div>
  </div>

  <!-- Sign Up Card -->
  <div class="relative z-10 bg-[#1f2023]/80 backdrop-blur-xl border border-[#3c3f44] shadow-2xl rounded-3xl w-full max-w-md p-10 space-y-8 transform transition-all hover:scale-[1.02]">

    <!-- Header -->
    <div class="text-center space-y-2">
      <h2 class="text-4xl font-extrabold tracking-tight text-white">Create Your Account</h2>
      <p class="text-gray-400">Join <span class="font-semibold text-indigo-400">DHVCON</span> and start connecting instantly.</p>
    </div>

    <!-- Sign Up Form -->
    <form id="frmSignup" method="POST" enctype="multipart/form-data" class="space-y-6">

      <!-- Full Name -->
      <div>
        <label class="block text-sm font-medium text-gray-300 mb-2">Full Name</label>
        <div class="flex items-center border border-[#3c3f44] bg-[#232428] rounded-2xl px-4 py-3 focus-within:ring-2 focus-within:ring-indigo-500 transition">
          <span class="material-icons text-gray-400 mr-3">person</span>
          <input type="text" name="full_name" class="w-full bg-transparent text-white focus:outline-none" placeholder="Enter your full name" required>
        </div>
      </div>

      <!-- Email -->
      <div>
        <label class="block text-sm font-medium text-gray-300 mb-2">Email</label>
        <div class="flex items-center border border-[#3c3f44] bg-[#232428] rounded-2xl px-4 py-3 focus-within:ring-2 focus-within:ring-indigo-500 transition">
          <span class="material-icons text-gray-400 mr-3">email</span>
          <input type="email" name="email" class="w-full bg-transparent text-white focus:outline-none" placeholder="Enter your email" required>
        </div>
      </div>

      <!-- Account Type -->
      <div>
        <label class="block text-sm font-medium text-gray-300 mb-2">Account Type</label>
        <div class="flex items-center border border-[#3c3f44] bg-[#232428] rounded-2xl px-4 py-3 focus-within:ring-2 focus-within:ring-indigo-500 transition">
          <span class="material-icons text-gray-400 mr-3">account_circle</span>
          <select name="user_type" class="cursor-pointer w-full bg-[#232428] text-white focus:outline-none rounded-lg px-2 py-1" required>
            <option value="" disabled selected class="text-gray-400">Select role</option>
            <option value="teacher" class="text-white">Teacher</option>
            <option value="student" class="text-white">Student</option>
          </select>
        </div>
      </div>

      <!-- Password -->
      <div>
        <label class="block text-sm font-medium text-gray-300 mb-2">Password</label>
        <div class="relative border border-[#3c3f44] bg-[#232428] rounded-2xl focus-within:ring-2 focus-within:ring-indigo-500 transition">
          <span class="material-icons text-gray-400 absolute left-3 top-1/2 -translate-y-1/2">lock</span>
          <input type="password" name="password" id="password" class="w-full pl-10 pr-10 py-3 bg-transparent text-white rounded-2xl focus:outline-none" placeholder="Create a password" required>
          <span class="material-icons text-gray-400 cursor-pointer absolute right-3 top-1/2 -translate-y-1/2 toggle-password" data-target="password">visibility_off</span>
        </div>
      </div>

      <!-- Confirm Password -->
      <div>
        <label class="block text-sm font-medium text-gray-300 mb-2">Confirm Password</label>
        <div class="relative border border-[#3c3f44] bg-[#232428] rounded-2xl focus-within:ring-2 focus-within:ring-indigo-500 transition">
          <span class="material-icons text-gray-400 absolute left-3 top-1/2 -translate-y-1/2">lock_reset</span>
          <input type="password" name="confirm_password" id="confirm_password" class="w-full pl-10 pr-10 py-3 bg-transparent text-white rounded-2xl focus:outline-none" placeholder="Re-enter password" required>
          <span class="material-icons text-gray-400 cursor-pointer absolute right-3 top-1/2 -translate-y-1/2 toggle-password" data-target="confirm_password">visibility_off</span>
        </div>
      </div>

      <!-- File Upload -->
      <div>
        <label class="block text-sm font-medium text-gray-300 mb-2">Upload Requirements</label>
        <div class="flex items-center border border-[#3c3f44] bg-[#232428] rounded-2xl px-4 py-3 focus-within:ring-2 focus-within:ring-indigo-500 transition">
          <span class="cursor-pointer material-icons text-gray-400 mr-3">upload_file</span>
          <input type="file" name="requirements[]" class="cursor-pointer w-full bg-transparent text-white focus:outline-none" multiple required>
        </div>
        <p class="text-xs text-gray-400 mt-1">You can upload multiple files (PDF, DOCX, JPG, PNG).</p>
      </div>

     <!-- Terms and Conditions Checkbox -->
      <div class="flex items-center space-x-3">
        <input type="checkbox" name="terms" id="terms" class="w-4 h-4 text-indigo-600 bg-gray-700 border-gray-600 rounded focus:ring-indigo-500" >
        <label for="terms" class="text-gray-400 text-sm">
          I agree to the 
          <span id="termsLink" class="text-indigo-400 hover:underline cursor-pointer">Terms and Conditions</span>
        </label>
      </div>


      <!-- Submit Button -->
      <button type="submit" class="w-full flex items-center justify-center gap-2 cursor-pointer bg-indigo-600 hover:bg-indigo-500 font-semibold py-3 rounded-full shadow-lg transition transform hover:scale-[1.02]">
        <span class="material-icons">person_add</span>
        Sign Up
      </button>

    </form>

    <!-- Footer -->
    <p class="text-center text-gray-400 text-sm">
      Already have an account?
      <a href="signin" class="text-indigo-400 font-semibold hover:underline">Sign in</a>
    </p>
  </div>

 <!-- Wave Divider -->
  <div class="absolute bottom-0 left-0 w-full overflow-hidden leading-[0]">
    <svg class="relative block w-full h-20 text-gray-900" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" viewBox="0 0 1200 120">
      <path fill="currentColor" d="M321.39,56.44C205.66,88.78,92.75,106.51,0,95.33V120H1200V0C1072.46,14.93,937.61,44.2,804.43,74.81,670.68,105.52,538.18,132.88,401.19,97.47,370.05,89.42,345.31,78.25,321.39,56.44Z"></path>
    </svg>
  </div>
  
</main>


<?php 
  include "src/components/footer.php";
?>









<!-- Terms and Conditions Modal -->
<div id="termsModal" class="fixed inset-0 flex items-center justify-center bg-black/60 backdrop-blur-sm z-50 hidden">
  <div class="bg-[#1f2023]/90 text-white rounded-2xl shadow-2xl w-11/12 max-w-lg p-6 relative max-h-[90vh] overflow-y-auto">
    <!-- Close Button -->
    <button id="closeTerms" class="absolute top-3 right-3 text-gray-400 hover:text-white cursor-pointer">
      <span class="material-icons">close</span>
    </button>

    <h2 class="text-2xl font-bold mb-4">Terms and Conditions</h2>
    <div class="text-gray-300 text-sm space-y-3">
      <p><strong>1. Information Collection:</strong> By using DHVCON, you agree that we may collect your personal information including name, email, and uploaded files for account creation and verification purposes.</p>
      <p><strong>2. Video Calls:</strong> You consent to participate in video calls via DHVCON. All video and audio data may be temporarily transmitted through our servers to facilitate the call but will not be stored permanently.</p>
      <p><strong>3. Use of Data:</strong> Collected information will only be used to provide services, improve user experience, and for internal analytics. We will not share your data with third parties without consent except as required by law.</p>
      <p><strong>4. Account Responsibility:</strong> You are responsible for maintaining the confidentiality of your account credentials. Any activity through your account is your responsibility.</p>
      <p><strong>5. Agreement:</strong> By signing up, you acknowledge that you have read and agree to these Terms and Conditions.</p>
    </div>
  </div>
</div>

<script>
  // Show Terms Modal
  const termsLink = document.getElementById('termsLink');
  const termsModal = document.getElementById('termsModal');
  const closeTerms = document.getElementById('closeTerms');

  termsLink.addEventListener('click', () => {
    termsModal.classList.remove('hidden');
  });

  closeTerms.addEventListener('click', () => {
    termsModal.classList.add('hidden');
  });

  // Close modal when clicking outside
  termsModal.addEventListener('click', (e) => {
    if (e.target === termsModal) {
      termsModal.classList.add('hidden');
    }
  });
</script>







<script>
  // Toggle visibility for all password fields
  document.querySelectorAll('.toggle-password').forEach(icon => {
    icon.addEventListener('click', () => {
      const targetId = icon.getAttribute('data-target');
      const input = document.getElementById(targetId);
      if (input.type === 'password') {
        input.type = 'text';
        icon.textContent = 'visibility';
      } else {
        input.type = 'password';
        icon.textContent = 'visibility_off';
      }
    });
  });
</script>
<script src="static/js/signup.js"></script>
