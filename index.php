<?php 
  include "src/components/header.php";
  ?>
  <!-- Hero Section -->
  <main class="flex flex-col md:flex-row items-center justify-between flex-1 px-8 lg:px-16 py-10 gap-10">
    <!-- Text Content -->
    <div class="max-w-lg space-y-5">
      <h2 class="text-4xl md:text-5xl font-bold text-red-700 leading-tight">
        Video calls with anyone, anytime
      </h2>
      <p class="text-lg text-gray-700">
        Connect and collaborate instantly with Teams — for free.
      </p>

      <div class="flex justify-left pt-4">
        <button class="cursor-pointer flex items-center space-x-2 bg-yellow-500 text-white px-8 py-3 rounded-full font-semibold hover:bg-yellow-600 transition">
            <span class="material-icons text-lg">videocam</span>
            <span>Get Started</span>
        </button>
      </div>

    </div>

    <!-- Decorative Elements -->
    <div class="relative w-full md:w-1/2 flex justify-center">
      <video autoplay loop muted playsinline class="rounded-2xl shadow-2xl w-full max-w-lg">
        <source src="https://statics.teams.cdn.live.net/evergreen-assets/gather/videos/hero-video-4544x2556_low.mp4" type="video/mp4">
      </video>
    </div>
  </main>


  <?php 
  include "src/components/footer.php";
  ?>