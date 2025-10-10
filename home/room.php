<?php 
include "../src/components/home/header.php";

$code=$_GET['code'];
?>

<!-- Main Content -->
<main class="flex-1 bg-[#1e1f22] ml-0 md:ml-60 p-4 transition-all duration-300">
<!-- Top Tabs -->
<div class="flex justify-between items-center px-6 py-4 border-b border-gray-800 bg-[#2b2d31]">
  
  <!-- Left Tabs -->
  <div class="flex items-center gap-6">
    <button class="joiner-only tab-btn cursor-pointer text-white font-semibold border-b-2 border-white pb-1" data-tab="feed">Feed</button>
    <button class="tab-btn cursor-pointer text-gray-400 hover:text-white" data-tab="meeting">Meeting</button>
    <button class="joiner-only tab-btn cursor-pointer text-gray-400 hover:text-white" data-tab="worksubmitted">Work Submitted</button>
    <button class="creator-only tab-btn cursor-pointer text-gray-400 hover:text-white" data-tab="classwork">Classwork</button>
    <button class="joiner-only tab-btn cursor-pointer text-gray-400 hover:text-white" data-tab="certificate">Claimed Certificate</button>
    <button class="creator-only tab-btn cursor-pointer text-gray-400 hover:text-white" data-tab="members">Members</button>
  </div>
  
  <!-- Right Leave Button -->
  <button id="btnLeaveRoom" data-code="<?=$code?>" class="joiner-only cursor-pointer flex items-center gap-2 text-gray-400 hover:text-white">
    Leave Room
    <span class="material-icons">exit_to_app</span>
  </button>
  
</div>





<!-- CLASSWORK SECTION -->
<?php 
include "section/classwork.php";
?>
<!-- FEED SECTION -->
<?php 
include "section/feed.php";
?>
<!-- MEETING SECTION -->
<?php 
include "section/meeting.php";
?>
<!-- CLAIMED CERTIFICATE SECTION -->
<?php 
include "section/claimed_certificate.php";
?>
<!-- WORK SUBMITTED SECTION -->
<?php 
include "section/work_submitted.php";
?>


<!-- MEMBERS SECTION -->
<?php 
include "section/members.php";
?>


</main>


<?php 
include "../src/components/home/footer.php";
?>




<!-- Modal Background -->
<div id="createMeetingModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50">
  <!-- Modal Content -->
  <div class="bg-[#2b2d31] w-full max-w-lg rounded-xl shadow-lg p-6 text-white relative">
    
    <!-- Close Button -->
    <button id="closeModal" 
            class="absolute top-3 right-3 text-gray-400 hover:text-white text-2xl leading-none">&times;</button>

    <h2 class="text-2xl font-semibold mb-4 border-b border-gray-700 pb-2">
      Create Meeting
    </h2>

    <!-- Form -->
    <form id="frmMeeting" class="space-y-4">

    <!-- Fullscreen Spinner Overlay -->
      <div class="spinner fixed inset-0 flex items-center justify-center z-[9999] bg-[#1e1f22]/80" style="display:none;">
        <div class="w-12 h-12 border-4 border-[#3c3f44] border-t-transparent rounded-full animate-spin"></div>
      </div>

      
      <div>
        <label class="block mb-1 text-sm">Meeting Link</label>
        <input type="url" name="meeting_link" placeholder="https://meet.google.com/xyz-abc"
               class="w-full p-2 rounded-md bg-[#1e1f22] border border-gray-600 focus:border-[#5865f2] outline-none">
      </div>

      <div>
        <label class="block mb-1 text-sm">Meeting Title</label>
        <input type="text" name="meeting_title" placeholder="Enter meeting title"
               class="w-full p-2 rounded-md bg-[#1e1f22] border border-gray-600 focus:border-[#5865f2] outline-none">
      </div>

      <div>
        <label class="block mb-1 text-sm">Meeting Description</label>
        <textarea name="meeting_description" rows="3" placeholder="Enter description..."
                  class="w-full p-2 rounded-md bg-[#1e1f22] border border-gray-600 focus:border-[#5865f2] outline-none"></textarea>
      </div>

      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="block mb-1 text-sm">Start Date</label>
          <input type="datetime-local" name="start_date"
                 class="w-full p-2 rounded-md bg-[#1e1f22] border border-gray-600 focus:border-[#5865f2] outline-none">
        </div>
        <div>
          <label class="block mb-1 text-sm">End Date</label>
          <input type="datetime-local" name="end_date"
                 class="w-full p-2 rounded-md bg-[#1e1f22] border border-gray-600 focus:border-[#5865f2] outline-none">
        </div>
      </div>

      <div class="pt-4 flex justify-end gap-3">
        <button type="button" id="cancelMeeting" 
                class="cursor-pointer bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">Cancel</button>
        <button type="submit" id="btnCreateMeeting" class="cursor-pointer bg-[#5865f2] hover:bg-[#4752c4] text-white px-4 py-2 rounded-md">Save Meeting</button>
      </div>

    </form>
  </div>
</div>






<script src="../static/js/home/room.js"></script>