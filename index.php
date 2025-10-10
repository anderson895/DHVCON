<?php 
include "src/components/header.php";
?>

<!-- Hero Section -->
<main class="relative bg-gradient-to-br from-gray-900 via-gray-800 to-black text-white overflow-hidden">

  <!-- Decorative Circles -->
  <div class="absolute top-0 left-0 w-72 h-72 bg-purple-600 opacity-20 blur-3xl rounded-full"></div>
  <div class="absolute bottom-0 right-0 w-96 h-96 bg-indigo-500 opacity-10 blur-3xl rounded-full"></div>

  <!-- Content -->
  <section class="relative flex flex-col md:flex-row items-center justify-between px-8 lg:px-20 py-24 gap-16">

    <!-- Text Content -->
    <div class="max-w-xl space-y-6 text-center md:text-left animate-fade-in">
      <h1 class="text-5xl md:text-6xl font-extrabold leading-tight">
        Connect, Communicate, and Collaborate
      </h1>
      <p class="text-lg text-gray-300">
        Experience seamless video calls and instant collaboration — anywhere, anytime, with <span class="text-indigo-400 font-semibold">Teams</span>.
      </p>

      <div class="flex justify-center md:justify-start pt-6">
        <a href="signin" class="flex items-center space-x-3 bg-indigo-600 hover:bg-indigo-500 text-white px-8 py-4 rounded-full font-semibold shadow-lg transition transform hover:scale-105">
          <span class="material-icons text-xl">videocam</span>
          <span>Get Started</span>
        </a>
      </div>
    </div>

    <!-- Hero Image -->
    <div class="w-full md:w-1/2 flex justify-center animate-fade-in-delay">
      <img src="static/image/banner.png" alt="Video Call Illustration" class="w-full max-w-lg rounded-2xl shadow-2xl border border-gray-700">
    </div>

  </section>

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
