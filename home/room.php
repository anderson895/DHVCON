<?php 
include "../src/components/home/header.php";

$code=$_GET['code'];
?>

<!-- Main Content -->
<main class="flex-1 flex flex-col bg-[#1e1f22]">
  <!-- Top Tabs -->
  <div class="flex justify-between items-center px-6 py-4 border-b border-gray-800 bg-[#2b2d31]">
    <div class="flex items-center gap-6">
      <button class="tab-btn cursor-pointer text-white font-semibold border-b-2 border-white pb-1" data-tab="feed">Feed</button>
      <button class="tab-btn cursor-pointer text-gray-400 hover:text-white" data-tab="classwork">Classwork</button>
      <button class="tab-btn cursor-pointer text-gray-400 hover:text-white" data-tab="meeting">Meeting</button>
      <button class="tab-btn cursor-pointer text-gray-400 hover:text-white" data-tab="certificate">Claimed Certificate</button>
      <button class="tab-btn cursor-pointer text-gray-400 hover:text-white" data-tab="worksubmitted">Work Submitted</button>
    </div>
  </div>





<!-- CLASSWORK SECTION -->
<section id="classwork" class="tab-section hidden p-6 text-white">
  <div class="bg-[#2b2d31] rounded-xl p-8 shadow-lg max-w-3xl mx-auto">

    <!-- Header -->
    <div class="flex items-center mb-6">
      
      <h2 class="text-2xl font-semibold">Assignment</h2>
    </div>

    <!-- Form -->
    <form class="space-y-6">

      <!-- Title -->
      <div>
        <label for="title" class="block text-sm font-medium mb-2">
          Title <span class="text-red-500">*</span>
        </label>
        <input
          type="text"
          id="title"
          placeholder="Enter assignment title"
          class="w-full p-3 bg-[#3a3b3f] border border-[#505155] rounded-lg 
          focus:ring-2 focus:ring-blue-500 focus:outline-none text-white"
        />
      </div>

      <!-- Instructions -->
      <div>
        <label for="instructions" class="block text-sm font-medium mb-2">
          Instructions (optional)
        </label>
        <textarea
          id="instructions"
          rows="5"
          placeholder="Add instructions..."
          class="w-full p-3 bg-[#3a3b3f] border border-[#505155] rounded-lg 
          focus:ring-2 focus:ring-blue-500 focus:outline-none text-white"
        ></textarea>
      </div>

      <!-- Single File Attachment -->
      <div class="border-t border-gray-600 pt-6">
        <h3 class="text-sm font-medium mb-4">Attach File</h3>
        <label
          for="file-upload"
          class="flex flex-col items-center justify-center p-6 bg-[#3a3b3f] border-2 border-dashed border-gray-500 rounded-lg cursor-pointer hover:border-blue-500 transition"
        >
          <span class="material-icons text-4xl text-gray-300 mb-2">upload_file</span>
          <span class="text-sm text-gray-300">Click to upload or drag a file here</span>
          <input id="file-upload" type="file" class="hidden" />
        </label>
      </div>

      <!-- Submit Button -->
      <div class="flex justify-end pt-4">
        <button
          type="submit"
          class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2 rounded-lg transition"
        >
          Post
        </button>
      </div>
    </form>
  </div>
</section>









  <!-- FEED SECTION -->
<section id="feed" class="tab-section p-6 bg-gradient-to-r from-[#1e1f22] via-[#2f3150] to-[#1e1f22] space-y-10">
  <!-- Welcome Banner -->
  <div class="rounded-2xl bg-[#2b2d31] p-10 text-center shadow-lg">
    <h1 class="text-4xl font-extrabold mb-3 text-white">Welcome to ROOM 101</h1>
    <p class="text-gray-400 mb-2 text-lg">
      Empowering collaboration and innovation — earn your certificate through our official platform.
    </p>
  </div>
  <!-- Create Post Section -->
  

  <!-- News Feed Timeline -->
  <div class="bg-[#2b2d31] rounded-2xl shadow-lg p-8">
    <h2 class="text-2xl font-bold text-white mb-6 border-b border-gray-700 pb-2 flex items-center gap-2">
      <span class="material-icons-round text-[#5865f2]">timeline</span> Latest News & Updates
    </h2>

    <div class="relative border-l-2 border-[#5865f2]/40 pl-8 space-y-10">

      <!-- Post 1 -->
      <a href="view_task" class="block relative flex gap-6 items-start cursor-pointer no-underline">
        <div class="bg-[#1e1f22] rounded-2xl p-6 w-full hover:bg-[#2f3150] transition">
          <div class="flex justify-between items-center">
            <h3 class="text-xl font-semibold text-white">DHVCON 2025 Launch Event</h3>
            <span class="text-gray-400 text-sm flex items-center gap-1">
              <span class="material-icons-round text-gray-400 text-sm">calendar_today</span> Oct 10, 2025
            </span>
          </div>
          <p class="text-gray-400 text-sm mt-1">By Admin</p>
          <p class="text-gray-300 text-sm mt-3">Join us for the grand launch of DHVCON 2025 featuring speakers, workshops, and new features on our collaboration platform.</p>
        </div>
      </a>

      <!-- Post 2 -->
      <a href="view_update" class="block relative flex gap-6 flex-row-reverse items-start cursor-pointer no-underline">
        <div class="bg-[#1e1f22] rounded-2xl p-6 w-full hover:bg-[#2f3150] transition">
          <div class="flex justify-between items-center">
            <h3 class="text-xl font-semibold text-white">Certificate Claim System Updated</h3>
            <span class="text-gray-400 text-sm flex items-center gap-1">
              <span class="material-icons-round text-gray-400 text-sm">update</span> Oct 8, 2025
            </span>
          </div>
          <p class="text-gray-400 text-sm mt-1">By Dev Team</p>
          <p class="text-gray-300 text-sm mt-3">You can now instantly claim your verified certificates once your project is reviewed by an admin.</p>
        </div>
      </a>

      <!-- Post 3 -->
      <a href="view_rooms" class="block relative flex gap-6 items-start cursor-pointer no-underline">
        <div class="bg-[#1e1f22] rounded-2xl p-6 w-full hover:bg-[#2f3150] transition">
          <div class="flex justify-between items-center">
            <h3 class="text-xl font-semibold text-white">New Collaboration Rooms Added</h3>
            <span class="text-gray-400 text-sm flex items-center gap-1">
              <span class="material-icons-round text-gray-400 text-sm">calendar_today</span> Oct 5, 2025
            </span>
          </div>
          <p class="text-gray-400 text-sm mt-1">By Team DHVCON</p>
          <p class="text-gray-300 text-sm mt-3">We’ve introduced new themed meeting rooms for design, development, and research — explore and connect!</p>
        </div>
      </a>

    </div>
  </div>
</section>



  <!-- MEETING SECTION -->
  <section id="meeting" class="tab-section hidden p-6 text-white">
    <div class="bg-[#2b2d31] rounded-xl p-8 shadow-lg text-center mb-8">
      <h2 class="text-3xl font-bold mb-3">Meetings</h2>
      <p class="text-gray-400 mb-6">Collaborate with your team — create or join a meeting below.</p>
      <div class="flex justify-center gap-4">
        <button class="cursor-pointer bg-white text-black font-semibold px-5 py-2.5 rounded-md hover:bg-gray-200 transition">
          Create Meeting
        </button>
        <button class="cursor-pointer bg-[#5865f2] text-white font-semibold px-5 py-2.5 rounded-md hover:bg-[#4752c4] transition">
          Join Meeting
        </button>
      </div>
    </div>

    <!-- Meeting Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
      <div class="bg-[#2b2d31] rounded-xl overflow-hidden shadow-md">
        <img src="https://images.unsplash.com/photo-1606112219348-204d7d8b94ee?w=600" alt="Meeting" class="w-full h-40 object-cover">
        <div class="p-4 space-y-3">
          <h3 class="font-semibold text-lg text-white">Meeting Name</h3>
          <p class="text-gray-400 text-sm">Ends 10/10 • Organized by EA</p>
          <p class="text-sm text-gray-300">Claim 700 Discord Orbs</p>
          <button class="w-full bg-[#5865f2] text-white py-2 rounded-md hover:bg-[#4752c4] transition">Join Meeting</button>
        </div>
      </div>
    </div>
  </section>

  <!-- CLAIMED CERTIFICATE SECTION -->
  <section id="certificate" class="tab-section hidden p-6 text-white">
    <div class="bg-[#2b2d31] rounded-xl p-6 shadow-lg">
      <h2 class="text-2xl font-bold mb-3">Claimed Certificates</h2>
      <p class="text-gray-400">You haven’t claimed any certificates yet.</p>
    </div>
  </section>

  <!-- UPLOADED FILES SECTION -->
  <section id="worksubmitted" class="tab-section hidden p-6 text-white">
   <div class="bg-[#2b2d31] rounded-2xl shadow-lg p-8">
    <h2 class="text-2xl font-bold text-white mb-6 border-b border-gray-700 pb-2 flex items-center gap-2">
      Work Submitted
    </h2>

    <div class="relative  space-y-10">

      <!-- Post 1 -->
      <a href="view_task" class="block relative flex gap-6 items-start cursor-pointer no-underline">
        <div class="bg-[#1e1f22] rounded-2xl p-6 w-full hover:bg-[#2f3150] transition">
          <div class="flex justify-between items-center">
            <h3 class="text-xl font-semibold text-white">DHVCON 2025 Launch Event</h3>
            <span class="text-gray-400 text-sm flex items-center gap-1">
              <span class="material-icons-round text-gray-400 text-sm">calendar_today</span> Oct 10, 2025
            </span>
          </div>
          <p class="text-gray-400 text-sm mt-1">By Admin</p>
          <p class="text-gray-300 text-sm mt-3">Join us for the grand launch of DHVCON 2025 featuring speakers, workshops, and new features on our collaboration platform.</p>
        </div>
      </a>

      <!-- Post 2 -->
      <a href="view_update" class="block relative flex gap-6 flex-row-reverse items-start cursor-pointer no-underline">
        <div class="bg-[#1e1f22] rounded-2xl p-6 w-full hover:bg-[#2f3150] transition">
          <div class="flex justify-between items-center">
            <h3 class="text-xl font-semibold text-white">Certificate Claim System Updated</h3>
            <span class="text-gray-400 text-sm flex items-center gap-1">
              <span class="material-icons-round text-gray-400 text-sm">update</span> Oct 8, 2025
            </span>
          </div>
          <p class="text-gray-400 text-sm mt-1">By Dev Team</p>
          <p class="text-gray-300 text-sm mt-3">You can now instantly claim your verified certificates once your project is reviewed by an admin.</p>
        </div>
      </a>

      <!-- Post 3 -->
      <a href="view_rooms" class="block relative flex gap-6 items-start cursor-pointer no-underline">
        <div class="bg-[#1e1f22] rounded-2xl p-6 w-full hover:bg-[#2f3150] transition">
          <div class="flex justify-between items-center">
            <h3 class="text-xl font-semibold text-white">New Collaboration Rooms Added</h3>
            <span class="text-gray-400 text-sm flex items-center gap-1">
              <span class="material-icons-round text-gray-400 text-sm">calendar_today</span> Oct 5, 2025
            </span>
          </div>
          <p class="text-gray-400 text-sm mt-1">By Team DHVCON</p>
          <p class="text-gray-300 text-sm mt-3">We’ve introduced new themed meeting rooms for design, development, and research — explore and connect!</p>
        </div>
      </a>

    </div>
  </div>
  </section>

</main>

<!-- jQuery Section Toggle -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
  $(".tab-btn").click(function(){
    const tab = $(this).data("tab");

    // Hide all sections
    $(".tab-section").hide();

    // Show selected section
    $("#" + tab).show();

    // Reset all tab styles
    $(".tab-btn")
      .removeClass("text-white font-semibold border-b-2 border-white pb-1")
      .addClass("text-gray-400");

    // Highlight active tab
    $(this)
      .removeClass("text-gray-400")
      .addClass("text-white font-semibold border-b-2 border-white pb-1");
  });
});
</script>

<?php 
include "../src/components/home/footer.php";
?>
