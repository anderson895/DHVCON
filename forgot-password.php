<?php 
  include "src/components/header.php";
?>

<!-- Fullscreen Spinner Overlay -->
<div id="spinner" class="fixed inset-0 flex items-center justify-center z-[9998] bg-black/70 backdrop-blur-md" style="display:none;">
  <div class="w-16 h-16 border-4 border-gray-600 border-t-indigo-500 rounded-full animate-spin"></div>
</div>

<!-- Forgot Password Page -->
<main class="relative min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-900 via-gray-800 to-black text-white overflow-hidden">

  <!-- Decorative Background Circle -->
  <div class="absolute top-0 right-0 w-96 h-96 bg-indigo-600 opacity-20 blur-3xl animate-pulse"></div>

  <!-- Forgot Password Card -->
  <div class="relative z-10 bg-[#1f2023]/80 backdrop-blur-xl border border-[#3c3f44] shadow-2xl rounded-3xl w-full max-w-md p-10 space-y-8 transform transition-all hover:scale-[1.02]">

    <!-- Header -->
    <div class="text-center space-y-2">
      <h2 class="text-4xl font-extrabold tracking-tight text-white">Forgot Password</h2>
      <p class="text-gray-400 text-sm">Enter your email to receive a password reset link</p>
    </div>

    <!-- Forgot Password Form -->
    <form id="frmForgotPassword" method="POST" class="space-y-6">

      <!-- Email Field -->
      <div>
        <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Email</label>
        <div class="flex items-center border border-[#3c3f44] bg-[#232428] rounded-2xl px-4 py-3 focus-within:ring-2 focus-within:ring-indigo-500 transition duration-300">
          <span class="material-icons text-gray-400 mr-3">email</span>
          <input 
            type="email" 
            id="email" 
            name="email" 
            class="w-full bg-transparent text-white placeholder-gray-500 focus:outline-none" 
            placeholder="Enter your email" 
            required
          >
        </div>
      </div>

      <!-- Submit Button -->
      <button 
        type="submit" 
        name="btnForgotPassword" 
        class="w-full flex items-center justify-center gap-2 cursor-pointer bg-indigo-600 hover:bg-indigo-500 font-semibold py-3 rounded-full shadow-lg transition transform hover:scale-[1.03]"
      >
        <span class="material-icons">send</span>
        Send Reset Link
      </button>
    </form>

    <!-- Back to Login -->
    <p class="text-center text-gray-400 text-sm">
      Remember your password?
      <a href="signin" class="text-indigo-400 font-semibold hover:underline">Sign In</a>
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

<script src="static/js/forgotpassword.js"></script>
