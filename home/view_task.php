<?php 
include "../src/components/home/header.php";
?>

<!-- Main Content -->
<main class="flex-1 flex flex-col bg-[#1e1f22] min-h-screen py-10">

  <!-- Main Container -->
  <div class="max-w-6xl mx-auto bg-[#2b2d31] shadow-xl rounded-2xl p-8 flex flex-col md:flex-row gap-8 border border-[#3a3b40]">

    <!-- Left Section: Assignment Details -->
    <div class="flex-1 flex flex-col">

      <!-- Header -->
      <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
          <span class="material-icons-round text-[#5865f2] text-3xl">assignment</span>
          <h1 class="text-2xl font-bold text-white">Assignment Task</h1>
        </div>
        <span class="px-3 py-1 rounded-md text-sm font-medium bg-[#1e1f22] border border-[#3a3b40] text-[#a8a8b3]">100 pts</span>
      </div>

      <p class="text-sm text-gray-400 mb-4">Posted by <span class="font-medium text-gray-300">Ryan Rodriguez</span> • 4:31 PM</p>

      <!-- Description Card -->
      <div class="border border-[#3a3b40] rounded-xl p-6 bg-[#1e1f22] mb-8 hover:border-[#5865f2] transition">
        <p class="text-gray-300 mb-5 leading-relaxed">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur sit amet lacus vel nulla gravida commodo.</p>

        <!-- Attachment -->
        <div class="border border-[#3a3b40] rounded-lg overflow-hidden w-72 bg-[#2b2d31] hover:scale-[1.02] transition">
          <img src="https://via.placeholder.com/300x180.png?text=Image+Attachment" alt="Attachment" class="object-cover w-full">
          <div class="p-3 text-sm text-gray-400 border-t border-[#3a3b40] bg-[#1e1f22]">
            <span class="truncate block text-gray-200 font-medium">494579798_69337385341559.png</span>
            <p class="text-xs text-gray-500">Image Attachment</p>
          </div>
        </div>
      </div>

      <!-- Class Comments -->
      <div class="mt-8 border-t border-[#3a3b40] pt-4">
        <h2 class="text-sm font-semibold text-gray-300 mb-3">Class comments</h2>
        <button class="text-sm text-[#5865f2] hover:underline flex items-center gap-1">
          <span class="material-icons-round text-sm">add_comment</span> Add a class comment
        </button>
      </div>
    </div>

    <!-- Right Section: Your Work -->
    <aside class="w-full md:w-80 flex flex-col gap-5">

      <!-- Your Work Card -->
      <div class="border border-[#3a3b40] rounded-xl p-6 bg-[#1e1f22] shadow-md">
        <div class="flex items-center justify-between mb-4">
          <h2 class="text-lg font-semibold text-white">Your Work</h2>
          <span class="text-sm text-green-400 font-medium bg-[#263230] px-2 py-1 rounded-md">Assigned</span>
        </div>

        <button class="w-full border border-[#3a3b40] rounded-md py-2 text-gray-300 hover:bg-[#2f3150] hover:border-[#5865f2] transition mb-4">
          + Add or create
        </button>

        <button class="w-full bg-[#5865f2] hover:bg-[#4752c4] text-white font-semibold py-2 rounded-md transition">
          Mark as done
        </button>
      </div>

      <!-- Private Comments Card -->
      <div class="border border-[#3a3b40] rounded-xl p-6 bg-[#1e1f22] shadow-md">
        <h3 class="text-sm font-semibold text-gray-300 mb-3">Private comments</h3>
        <button class="text-sm text-[#5865f2] hover:underline flex items-center gap-1">
          <span class="material-icons-round text-sm">comment</span>
          Add comment to Ryan Rodriguez
        </button>
      </div>

    </aside>
  </div>
</main>

<?php 
include "../src/components/home/footer.php";
?>
