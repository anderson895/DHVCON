<?php 
include "../src/components/admin/header.php";


$pageTitle = "View All Rooms";

?>

<div class="flex justify-between items-center bg-[#0D0D0D] p-4 mb-6 rounded-md shadow-lg">
  <h2 class="text-xl font-bold text-[#FFD700] capitalize tracking-wide"><?=$pageTitle?></h2>
</div>


  <!-- Table Container -->
<div class="overflow-x-auto rounded-md">
  <table class="w-full text-sm text-left text-[#CCCCCC]">
    <thead class="bg-[#0D0D0D] text-[#FFD700] uppercase text-xs">
      <tr>
        <th class="p-3">Code</th>
        <th class="p-3">Name</th>
        <th class="p-3">Creator</th>
        <th class="p-3">Banner</th>
        <th class="p-3 text-center">View</th>
      </tr>
    </thead>
    <tbody id="roomTableBody" class="divide-y divide-gray-700">
      <!-- Dynamic Data -->
    </tbody>
  </table>
</div>

<!-- Pagination -->
<div id="pagination" class="mt-4 flex justify-center gap-2"></div>










<!-- MODAL SECTION -->

<!-- CLASSWORK MODAL -->
<div id="classworkModal" 
     class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">

  <!-- MODAL BOX -->
  <div class="bg-[#1E1E1E] w-full max-w-[1100px] max-h-[90vh] rounded-lg shadow-xl p-6 
              overflow-hidden flex flex-col">

    <!-- TITLE -->
    <h2 id="classworkModalTitle" 
        class="text-2xl text-white font-semibold mb-4">
      Classworks
    </h2>

    <!-- TABLE WRAPPER (SCROLLABLE) -->
    <div class="overflow-auto border border-gray-700 rounded-lg flex-1">

      <table class="min-w-[900px] w-full text-left text-gray-300">
        <thead class="bg-[#2A2A2A] sticky top-0 z-10">
          <tr>
            <th class="p-2">#</th>
            <th class="p-2">Title</th>
            <th class="p-2">Instruction</th>
            <th class="p-2">File</th>
            <th class="p-2">Created</th>
          </tr>
        </thead>
        <tbody id="classworkTableBody"></tbody>
      </table>

    </div>

    <!-- FOOTER -->
    <div class="mt-4 text-right">
      <button id="closeClassworkModal"
        class="cursor-pointer px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded">
        Close
      </button>
    </div>

  </div>
</div>




<!-- MEMBER MODAL -->
<div id="memberModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
  <div class="bg-[#1E1E1E] w-full max-w-[900px] max-h-[90vh] rounded-lg shadow-xl p-6 overflow-hidden flex flex-col">
    <h2 id="memberModalTitle" class="text-2xl text-white font-semibold mb-4">Members</h2>

    <div class="overflow-auto border border-gray-700 rounded-lg flex-1">
      <table class="min-w-[700px] w-full text-gray-300">
        <thead class="bg-[#2A2A2A] sticky top-0">
          <tr>
            <th class="p-2">#</th>
            <th class="p-2">Name</th>
            <th class="p-2">Email</th>
            <th class="p-2">Joined</th>
          </tr>
        </thead>
        <tbody id="memberTableBody"></tbody>
      </table>
    </div>

    <div class="mt-4 text-right">
      <button id="closeMemberModal" class="cursor-pointer px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded">Close</button>
    </div>
  </div>
</div>



<!-- MEETING MODAL -->
<div id="meetingModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
  <div class="bg-[#1E1E1E] w-full max-w-[900px] max-h-[90vh] rounded-lg shadow-xl p-6 overflow-hidden flex flex-col">
    <h2 id="meetingModalTitle" class="text-2xl text-white font-semibold mb-4">Meetings</h2>

    <div class="overflow-auto border border-gray-700 rounded-lg flex-1">
      <table class="min-w-[700px] w-full text-gray-300">
        <thead class="bg-[#2A2A2A] sticky top-0">
          <tr>
            <th class="p-2">#</th>
            <th class="p-2">Title / Rating / Comments</th>
            <th class="p-2">Schedule</th>
            <th class="p-2">Created</th>
          </tr>
        </thead>
        <tbody id="meetingTableBody"></tbody>
      </table>
    </div>

    <div class="mt-4 text-right">
      <button id="closeMeetingModal" class="cursor-pointer px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded">Close</button>
    </div>
  </div>
</div>





<?php include "../src/components/admin/footer.php"; ?>
<script src="../static/js/admin/rooms.js"></script>
