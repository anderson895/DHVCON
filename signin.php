<?php 
  include "src/components/header.php";
?>


<!-- Fullscreen Spinner Overlay -->
<div id="spinner" class="fixed inset-0 flex items-center justify-center z-[9998] bg-black/70 backdrop-blur-md" style="display:none;">
  <div class="w-16 h-16 border-4 border-gray-600 border-t-indigo-500 rounded-full animate-spin"></div>
</div>

<!-- Sign In Page -->
<main class="relative min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-900 via-gray-800 to-black text-white overflow-hidden">

  <!-- Decorative Background Circle (Indigo only) -->
  <div class="absolute top-0 left-0 w-96 h-96 bg-indigo-600 opacity-20 blur-3xl animate-pulse"></div>

  <!-- Sign In Card -->
  <div class="relative z-10 bg-[#1f2023]/80 backdrop-blur-xl border border-[#3c3f44] shadow-2xl rounded-3xl w-full max-w-md p-10 space-y-8 transform transition-all hover:scale-[1.02]">

    <!-- Header -->
    <div class="text-center space-y-2">
      <h2 class="text-4xl font-extrabold tracking-tight text-white">Welcome Back</h2>
      <p class="text-gray-400 text-sm">Sign in to continue to <span class="font-semibold text-indigo-400">DHVCON</span></p>
    </div>

    <!-- Sign In Form -->
    <form id="frmLogin" method="POST" class="space-y-6">

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

      <!-- Password Field -->
      <div>
        <label for="password" class="block text-sm font-medium text-gray-300 mb-2">Password</label>
        <div class="relative border border-[#3c3f44] bg-[#232428] rounded-2xl focus-within:ring-2 focus-within:ring-indigo-500 transition duration-300">
          <span class="material-icons text-gray-400 absolute left-3 top-1/2 -translate-y-1/2">lock</span>
          <input 
            type="password" 
            id="password" 
            name="password" 
            class="w-full pl-10 pr-10 py-3 bg-transparent text-white focus:outline-none rounded-2xl placeholder-gray-500" 
            placeholder="Enter your password" 
            required
          >
          <span id="togglePassword" class="material-icons text-gray-400 cursor-pointer absolute right-3 top-1/2 -translate-y-1/2">
            visibility_off
          </span>
        </div>
      </div>

      <!-- Submit Button -->
      <button 
        type="submit" 
        name="btnLogin" 
        class="w-full flex items-center justify-center gap-2 cursor-pointer bg-indigo-600 hover:bg-indigo-500 font-semibold py-3 rounded-full shadow-lg transition transform hover:scale-[1.03]"
      >
        <span class="material-icons">login</span>
        Sign In
      </button>
    </form>

    <!-- Footer Text -->
    <p class="text-center text-gray-400 text-sm">
      Don’t have an account?
      <a href="signup" class="text-indigo-400 font-semibold hover:underline">Sign up</a>
    </p>
  </div>

</main>


<?php 
  include "src/components/footer.php";
?>

<script>
  const togglePassword = document.getElementById('togglePassword');
  const passwordInput = document.getElementById('password');

  togglePassword.addEventListener('click', () => {
    if (passwordInput.type === 'password') {
      passwordInput.type = 'text';
      togglePassword.textContent = 'visibility';
    } else {
      passwordInput.type = 'password';
      togglePassword.textContent = 'visibility_off';
    }
  });
</script>


<script src="static/js/signin.js"></script>
