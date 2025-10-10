<?php 
  include "src/components/header.php";
  include "plugins/PageSpinner.php";
?>

<!-- Import Google Material Icons -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<!-- Sign Up Page -->
<main class="relative min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-900 via-gray-800 to-black text-white overflow-hidden">

  <!-- Background Decorative Lights -->
  <div class="absolute top-0 left-0 w-96 h-96 bg-purple-600 opacity-20 blur-3xl rounded-full"></div>
  <div class="absolute bottom-0 right-0 w-[28rem] h-[28rem] bg-indigo-700 opacity-10 blur-3xl rounded-full"></div>



  
   <!-- Fullscreen Spinner Overlay -->
<div id="spinner" class="fixed inset-0 flex items-center justify-center z-[9999] bg-black/60 backdrop-blur-sm" style="display:none;">
  <div class="w-16 h-16 border-4 border-gray-600 border-t-indigo-500 rounded-full animate-spin"></div>
</div>


  <!-- Sign Up Card -->
  <div class="relative z-10 bg-[#2b2d31]/80 backdrop-blur-xl border border-[#3c3f44] shadow-2xl rounded-2xl w-full max-w-md p-10 space-y-8 transform transition-all hover:scale-[1.01] duration-300">

    <!-- Header -->
    <div class="text-center space-y-2">
      <h2 class="text-4xl font-extrabold tracking-tight">Create Your Account</h2>
      <p class="text-gray-400">Join <span class="font-semibold text-indigo-400">DHVCON</span> and start connecting instantly.</p>
    </div>

    <!-- Sign Up Form -->
    <form id="frmSignup" method="POST" class="space-y-6">

      <!-- Full Name -->
      <div>
        <label class="block text-sm font-medium text-gray-300 mb-2">Full Name</label>
        <div class="flex items-center border border-[#3c3f44] bg-[#232428] rounded-xl px-4 py-3 focus-within:ring-2 focus-within:ring-indigo-500 transition">
          <span class="material-icons text-gray-400 mr-3">person</span>
          <input 
            type="text" 
            name="full_name" 
            class="w-full bg-transparent text-white focus:outline-none" 
            placeholder="Enter your full name" 
            required
          >
        </div>
      </div>

      <!-- Email -->
      <div>
        <label class="block text-sm font-medium text-gray-300 mb-2">Email</label>
        <div class="flex items-center border border-[#3c3f44] bg-[#232428] rounded-xl px-4 py-3 focus-within:ring-2 focus-within:ring-indigo-500 transition">
          <span class="material-icons text-gray-400 mr-3">email</span>
          <input 
            type="email" 
            name="email" 
            class="w-full bg-transparent text-white focus:outline-none" 
            placeholder="Enter your email" 
            required
          >
        </div>
      </div>

      <!-- Password -->
      <div>
        <label class="block text-sm font-medium text-gray-300 mb-2">Password</label>
        <div class="flex items-center border border-[#3c3f44] bg-[#232428] rounded-xl px-4 py-3 focus-within:ring-2 focus-within:ring-indigo-500 transition">
          <span class="material-icons text-gray-400 mr-3">lock</span>
          <input 
            type="password" 
            name="password" 
            id="password"
            class="w-full bg-transparent text-white focus:outline-none" 
            placeholder="Create a password" 
            required
          >
        </div>
      </div>

      <!-- Confirm Password -->
      <div>
        <label class="block text-sm font-medium text-gray-300 mb-2">Confirm Password</label>
        <div class="flex items-center border border-[#3c3f44] bg-[#232428] rounded-xl px-4 py-3 focus-within:ring-2 focus-within:ring-indigo-500 transition">
          <span class="material-icons text-gray-400 mr-3">lock_reset</span>
          <input 
            type="password" 
            name="confirm_password" 
            id="confirm_password"
            class="w-full bg-transparent text-white focus:outline-none" 
            placeholder="Re-enter password" 
            required
          >
        </div>
      </div>

      <!-- Submit Button -->
      <button 
        type="submit" 
        class="w-full flex items-center justify-center gap-2 cursor-pointer bg-indigo-600 hover:bg-indigo-500 font-semibold py-3 rounded-full shadow-lg transition transform hover:scale-[1.02]"
      >
        <span class="material-icons">person_add</span>
        Sign Up
      </button>
    </form>

    <!-- Footer Text -->
    <p class="text-center text-gray-400 text-sm">
      Already have an account?
      <a href="signin" class="text-indigo-400 font-semibold hover:underline">Sign in</a>
    </p>
  </div>
</main>

<?php 
  include "src/components/footer.php";
?>

<script src="static/js/signup.js"></script>
