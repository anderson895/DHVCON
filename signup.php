<?php 
  include "src/components/header.php";
  include "plugins/PageSpinner.php";
?>

<!-- Sign Up Form -->
<main class="flex-1 flex items-center justify-center px-6 py-12 bg-[#1e1f22] text-white">
  <div class="relative bg-[#2b2d31] shadow-2xl rounded-2xl w-full max-w-md p-8 space-y-6 border border-[#3c3f44]">
    <h2 class="text-3xl font-bold text-center text-white">Create your account</h2>
    <p class="text-center text-gray-400">Join DHVCON and start connecting instantly.</p>

    <!-- Spinner Overlay -->
    <div id="spinner" class="absolute inset-0 flex items-center justify-center z-50 bg-[#1e1f22]/70" style="display:none;">
      <div class="w-12 h-12 border-4 border-[#3c3f44] border-t-transparent rounded-full animate-spin"></div>
    </div>

    <form id="frmSignup" method="POST" class="space-y-5">
      <!-- Full Name -->
      <div>
        <label class="block text-sm font-medium text-gray-300 mb-1">Full Name</label>
        <input 
          type="text" 
          name="full_name" 
          class="w-full border border-[#3c3f44] bg-[#232428] text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#3c3f44]" 
          placeholder="Enter your name" 
          required
        >
      </div>

      <!-- Email -->
      <div>
        <label class="block text-sm font-medium text-gray-300 mb-1">Email</label>
        <input 
          type="email" 
          name="email" 
          class="w-full border border-[#3c3f44] bg-[#232428] text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#3c3f44]" 
          placeholder="Enter your email" 
          required
        >
      </div>

      <!-- Password -->
      <div>
        <label class="block text-sm font-medium text-gray-300 mb-1">Password</label>
        <input 
          type="password" 
          name="password" 
          id="password"
          class="w-full border border-[#3c3f44] bg-[#232428] text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#3c3f44]" 
          placeholder="Create a password" 
          required
        >
      </div>

      <!-- Confirm Password -->
      <div>
        <label class="block text-sm font-medium text-gray-300 mb-1">Confirm Password</label>
        <input 
          type="password" 
          name="confirm_password" 
          id="confirm_password"
          class="w-full border border-[#3c3f44] bg-[#232428] text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#3c3f44]" 
          placeholder="Re-enter password" 
          required
        >
      </div>

      <!-- Submit Button -->
      <button 
        type="submit" 
        class="cursor-pointer w-full bg-[#3c3f44] text-white font-semibold py-3 rounded-full hover:bg-[#4f545c] transition"
      >
        Sign Up
      </button>
    </form>

    <p class="text-center text-gray-400 text-sm">
      Already have an account?
      <a href="signin" class="text-blue-400 font-semibold hover:underline">Sign in</a>
    </p>
  </div>
</main>

<?php 
  include "src/components/footer.php";
?>

<script src="static/js/signup.js"></script>
