<?php 
include "../src/components/home/header.php";
?>


  <!-- Main Content -->
  <main class="flex-1 flex flex-col bg-[#1e1f22]">
    <!-- Top Tabs -->
    <div class="flex justify-between items-center px-6 py-4 border-b border-gray-800 bg-[#2b2d31]">
      <div class="flex items-center gap-6">
        <button class="text-white font-semibold border-b-2 border-white pb-1">All Meeting</button>
        <button class="cursor-pointer text-gray-400 hover:text-white">Claimed Certificate</button>
      </div>
     
    </div>

    <!-- Banner -->
   <section class="p-6 bg-gradient-to-r from-[#1e1f22] via-[#2f3150] to-[#1e1f22]">
        <div class="rounded-2xl bg-[#2b2d31] p-10 text-center shadow-lg">
            <h1 class="text-4xl font-extrabold mb-3 text-white">Welcome to DHVCON</h1>
            <p class="text-gray-400 mb-5 text-lg">Empowering collaboration and innovation — earn your certificate through our official platform.</p>
            <div class="flex justify-center gap-4">
            <button class="cursor-pointer bg-white text-black font-semibold px-5 py-2.5 rounded-md hover:bg-gray-200 transition">
                Create Meeting
            </button>
            <button class="cursor-pointer bg-[#5865f2] text-white font-semibold px-5 py-2.5 rounded-md hover:bg-[#4752c4] transition">
                Join Meeting
            </button>
            </div>
        </div>
    </section>


    <!-- Available Quests -->
    <section class="px-6 pb-12">
      <h2 class="text-2xl font-bold mb-4">Available Quests</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

        <!-- Quest Card -->
        <div class="bg-[#2b2d31] rounded-xl overflow-hidden shadow-md">
          <img src="https://images.unsplash.com/photo-1606112219348-204d7d8b94ee?w=600" alt="Battlefield" class="w-full h-40 object-cover">
          <div class="p-4 space-y-3">
            <h3 class="font-semibold text-lg flex items-center gap-2">
              Battlefield 6 Trailer Quest
            </h3>
            <p class="text-gray-400 text-sm">Ends 10/10 • Promoted by EA</p>
            <p class="text-sm text-gray-300 flex items-center gap-1">
              Claim 700 Discord Orbs
            </p>
            <button class="w-full bg-[#5865f2] text-white py-2 rounded-md hover:bg-[#4752c4] transition">
              Join Meeting
            </button>
          </div>
        </div>



        <!-- Quest Card -->
        <div class="bg-[#2b2d31] rounded-xl overflow-hidden shadow-md">
          <img src="https://images.unsplash.com/photo-1606112219348-204d7d8b94ee?w=600" alt="Battlefield" class="w-full h-40 object-cover">
          <div class="p-4 space-y-3">
            <h3 class="font-semibold text-lg flex items-center gap-2">
              Battlefield 6 Trailer Quest
            </h3>
            <p class="text-gray-400 text-sm">Ends 10/10 • Promoted by EA</p>
            <p class="text-sm text-gray-300 flex items-center gap-1">
              Claim 700 Discord Orbs
            </p>
            <button class="w-full bg-[#5865f2] text-white py-2 rounded-md hover:bg-[#4752c4] transition">
              Join Meeting
            </button>
          </div>
        </div>



      </div>
    </section>
  </main>
<?php 
include "../src/components/home/footer.php";
?>
