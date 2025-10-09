<?php 
  include "src/components/header.php";
?>
  <!-- Sign In Form -->
  <main class="flex-1 flex items-center justify-center px-6 py-12 bg-[#1e1f22] text-white">
    <div class="relative bg-[#2b2d31] shadow-2xl rounded-2xl w-full max-w-md p-8 space-y-6 border border-[#3c3f44]">
      <h2 class="text-3xl font-bold text-center text-white">Welcome back!</h2>
      <p class="text-center text-gray-400">Sign in to continue to DHVCON</p>

      <!-- Spinner Overlay -->
      <div id="spinner" class="absolute inset-0 flex items-center justify-center z-50  bg-[#1e1f22]/70" style="display:none;">
        <div class="w-12 h-12 border-4 border-[#3c3f44] border-t-transparent rounded-full animate-spin"></div>
      </div>

      <form id="frmLogin" method="POST" class="space-y-5">
        <div>
          <label for="email" class="block text-sm font-medium text-gray-300 mb-1">Email</label>
          <input 
            type="email" 
            id="email" 
            name="email" 
            class="w-full border border-[#3c3f44] bg-[#232428] text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#3c3f44]" 
            placeholder="Enter your email" 
            required
          >
        </div>

        <div>
          <label for="password" class="block text-sm font-medium text-gray-300 mb-1">Password</label>
          <input 
            type="password" 
            id="password" 
            name="password" 
            class="w-full border border-[#3c3f44] bg-[#232428] text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#3c3f44]" 
            placeholder="Enter your password" 
            required
          >
        </div>

        <button 
          type="submit" 
          name="btnLogin" 
          class="w-full cursor-pointer bg-[#3c3f44] text-white font-semibold py-3 rounded-full hover:bg-[#4f545c] transition"
        >
          Sign In
        </button>
      </form>

      <p class="text-center text-gray-400 text-sm">
        Don’t have an account?
        <a href="signup" class="text-blue-400 font-semibold hover:underline">Sign up</a>
      </p>
    </div>
  </main>

<?php 
  include "src/components/footer.php";
?>
<script src="static/js/signin.js"></script>
