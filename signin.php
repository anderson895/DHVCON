<?php 
  include "src/components/header.php";
  ?>
  <!-- Sign In Form -->
  <main class="flex-1 flex items-center justify-center px-6 py-12">
    <div class="bg-white shadow-2xl rounded-2xl w-full max-w-md p-8 space-y-6 border border-yellow-200">
      <h2 class="text-3xl font-bold text-center text-red-700">Welcome back!</h2>
      <p class="text-center text-gray-600">Sign in to continue to DHVCON</p>

      <form action="#" method="POST" class="space-y-5">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
          <input type="email" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400" placeholder="Enter your email" required>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
          <input type="password" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400" placeholder="Enter your password" required>
        </div>

        <button type="submit" class="w-full bg-yellow-500 text-white font-semibold py-3 rounded-full hover:bg-yellow-600 transition">
          Sign In
        </button>
      </form>

      <p class="text-center text-gray-600 text-sm">
        Don’t have an account?
        <a href="signup" class="text-red-600 font-semibold hover:underline">Sign up</a>
      </p>
    </div>
  </main>

<?php 
  include "src/components/footer.php";
  ?>