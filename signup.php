<?php 
  include "src/components/header.php";
  ?>

  <!-- Sign Up Form -->
  <main class="flex-1 flex items-center justify-center px-6 py-12">
    <div class="bg-white shadow-2xl rounded-2xl w-full max-w-md p-8 space-y-6 border border-yellow-200">
      <h2 class="text-3xl font-bold text-center text-red-700">Create your account</h2>
      <p class="text-center text-gray-600">Join DHVCON and start connecting instantly.</p>

      <form action="#" method="POST" class="space-y-5">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
          <input type="text" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400" placeholder="Enter your name" required>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
          <input type="email" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400" placeholder="Enter your email" required>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
          <input type="password" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400" placeholder="Create a password" required>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
          <input type="password" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400" placeholder="Re-enter password" required>
        </div>

        <button type="submit" class="w-full bg-yellow-500 text-white font-semibold py-3 rounded-full hover:bg-yellow-600 transition">
          Sign Up
        </button>
      </form>

      <p class="text-center text-gray-600 text-sm">
        Already have an account?
        <a href="signin" class="text-red-600 font-semibold hover:underline">Sign in</a>
      </p>
    </div>
  </main>


 <?php 
  include "src/components/footer.php";
  ?>