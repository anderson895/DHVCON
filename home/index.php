<?php 
include "../src/components/home/header.php";
?>

  <!-- Main Content -->
   <main class="flex-1 bg-[#1e1f22] ml-0 md:ml-60 p-4 transition-all duration-300">

    <!-- Banner -->
   <section class="p-6 bg-gradient-to-r ">
        <div class="rounded-2xl bg-[#2b2d31] p-10 text-center shadow-lg">
            <h1 class="text-4xl font-extrabold mb-3 text-white">Welcome to DHVCON</h1>
            <p class="text-gray-400 mb-5 text-lg">Empowering collaboration and innovation — earn your certificate through our official platform.</p>
            <div class="flex justify-center gap-4">
            <button id="btnCreateRoom" class="cursor-pointer bg-white text-black font-semibold px-5 py-2.5 rounded-md hover:bg-gray-200 transition">
                Create Room
            </button>
            <button class="btnJoinRoom cursor-pointer bg-[#5865f2] text-white font-semibold px-5 py-2.5 rounded-md hover:bg-[#4752c4] transition"
            data-code=''
            >
                Join Room
            </button>
            </div>
        </div>
    </section>


  
   <section class="px-6 pb-12">
    <div class="mb-4"></div>
        <div class="room-list grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        </div>
  </section>

    
  </main>





<!-- Create Room Modal -->
<div id="createRoomModal" class="fixed inset-0 bg-[rgba(0,0,0,0.5)] flex items-center justify-center z-50 p-4 sm:p-0" style="display:none;">
  <div class="bg-[#2b2d31] p-6 sm:p-8 rounded-2xl w-full max-w-md shadow-lg relative">
    <h2 class="text-xl sm:text-2xl font-bold mb-4 text-white text-center sm:text-left">Create a New Room</h2>

    <!-- Spinner Overlay -->
    <div id="spinnerCreate" class="absolute inset-0 flex items-center justify-center z-50 bg-[#1e1f22]/70" style="display:none;">
      <div class="w-12 h-12 border-4 border-[#3c3f44] border-t-transparent rounded-full animate-spin"></div>
    </div>

    <form id="createRoomForm" class="space-y-4">
      <input type="text" name="roomName" placeholder="Room Name" 
             class="w-full p-2 sm:p-3 rounded-md bg-[#1e1f22] text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-[#5865f2]">
      <textarea name="roomDescription" placeholder="Room Description" 
                class="w-full p-2 sm:p-3 rounded-md bg-[#1e1f22] text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-[#5865f2]"></textarea>
      <label class="block text-white cursor-pointer">Room Banner:</label>
      <input type="file" name="roomBanner" 
             class="w-full p-2 sm:p-3 rounded-md bg-[#1e1f22] text-white border border-gray-600 cursor-pointer">

      <div class="flex flex-col sm:flex-row justify-end gap-2">
        <button type="button" id="closeCreateModal" 
                class="px-4 py-2 cursor-pointer bg-gray-500 text-white rounded-md hover:bg-gray-600 w-full sm:w-auto">Cancel</button>
        <button type="submit" 
                class="px-4 cursor-pointer py-2 bg-[#5865f2] text-white rounded-md hover:bg-[#4752c4] w-full sm:w-auto">Create</button>
      </div>
    </form>
  </div>
</div>

<!-- Update Room Modal -->
<div id="updateRoomModal" class="fixed inset-0 bg-[rgba(0,0,0,0.5)] flex items-center justify-center z-50 p-4 sm:p-0" style="display:none;">
  <div class="bg-[#2b2d31] p-6 sm:p-8 rounded-2xl w-full max-w-md shadow-lg relative">
    <h2 class="text-xl sm:text-2xl font-bold mb-4 text-white text-center sm:text-left">Update Room</h2>

    <!-- Spinner Overlay -->
    <div id="spinnerUpdate" class="absolute inset-0 flex items-center justify-center z-50 bg-[#1e1f22]/70" style="display:none;">
      <div class="w-12 h-12 border-4 border-[#3c3f44] border-t-transparent rounded-full animate-spin"></div>
    </div>

    <form id="updateRoomForm" class="space-y-4">
      <input type="text" name="roomName" placeholder="Room Name" 
             class="w-full p-2 sm:p-3 rounded-md bg-[#1e1f22] text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-[#5865f2]">
      <textarea name="roomDescription" placeholder="Room Description" 
                class="w-full p-2 sm:p-3 rounded-md bg-[#1e1f22] text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-[#5865f2]"></textarea>
      <label class="block text-white cursor-pointer">Room Banner:</label>
      <input type="file" name="roomBanner" 
             class="w-full p-2 sm:p-3 rounded-md bg-[#1e1f22] text-white border border-gray-600 cursor-pointer">

      <div class="flex flex-col sm:flex-row justify-end gap-2">
        <button type="button" id="closeUpdateModal" 
                class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 w-full sm:w-auto">Cancel</button>
        <button type="submit" 
                class="px-4 py-2 cursor-pointer bg-[#5865f2] text-white rounded-md hover:bg-[#4752c4] w-full sm:w-auto">Update</button>
      </div>
    </form>
  </div>
</div>






<!-- Join Room Modal -->
<div id="joinRoomModal" class="fixed inset-0 bg-[rgba(0,0,0,0.5)] flex items-center justify-center z-50 p-4 sm:p-0" style="display:none;">
  <div class="bg-[#2b2d31] p-6 sm:p-8 rounded-2xl w-full max-w-md shadow-lg relative">
    <h2 class="text-xl sm:text-2xl font-bold mb-4 text-white text-center sm:text-left">Join a Room</h2>

    <!-- Spinner Overlay -->
    <div id="joinSpinner" class="absolute inset-0 flex items-center justify-center z-50 bg-[#1e1f22]/70" style="display:none;">
      <div class="w-12 h-12 border-4 border-[#3c3f44] border-t-transparent rounded-full animate-spin"></div>
    </div>

    <form id="joinRoomForm" class="space-y-4">
      <!-- Room Code -->
      <input type="text" name="roomCode" id="roomCode" placeholder="Enter Room Code" 
             class="w-full p-2 sm:p-3 rounded-md bg-[#1e1f22] text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-[#5865f2]">

      <!-- Action Buttons -->
      <div class="flex flex-col sm:flex-row justify-end gap-2">
        <button type="button" id="closeJoinModal" class="cursor-pointer px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 w-full sm:w-auto">Cancel</button>
        <button type="submit" id="btnConfirmJoin" class="cursor-pointer px-4 py-2 bg-[#5865f2] text-white rounded-md hover:bg-[#4752c4] w-full sm:w-auto">Join</button>
      </div>
    </form>
  </div>
</div>








<?php 
include "../src/components/home/footer.php";
?>

<script src="../static/js/home/index.js"></script>
