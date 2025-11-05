<section id="meeting" class="tab-section hidden p-6 text-white">
    <div class="creator-only bg-[#2b2d31] rounded-xl p-8 shadow-lg text-center mb-8">
      <h2 class="text-3xl font-bold mb-3">Meetings</h2>
      <p class="text-gray-400 mb-6">Collaborate with your team — create or join a meeting below.</p>
      <div class="flex justify-center gap-4">
        <button id="btnCreateMeeting" 
                class="cursor-pointer bg-white text-black font-semibold px-5 py-2.5 rounded-md hover:bg-gray-200 transition">
          Create Meeting
        </button>
      </div>
    </div>

    <!-- Meeting Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">



        <div class="bg-[#2b2d31] rounded-xl overflow-hidden shadow-md">
          <div class="p-4 space-y-3">
            <h3 class="font-semibold text-lg text-white">Meeting Title Here</h3>
            <p class="text-gray-400 text-sm">Start datetime here  • Ends datetime here </p>
            <p class="text-sm text-gray-300">description here </p>
           
           
            <a href="https://meet.google.com/" target="_blank" 
              class="joiner-only block w-full text-center bg-[#5865f2] text-white py-2 rounded-md hover:bg-[#4752c4] transition cursor-pointer">
              Join Meeting
            </a>

            <button 
              class="creator-only w-full text-center bg-red-500 text-white py-2 rounded-md hover:bg-red-300 transition cursor-pointer">
              Close Meeting
            </button>

            <button 
              class="joiner-only w-full text-center bg-[#5865f2] text-white py-2 rounded-md hover:bg-[#4752c4] transition cursor-pointer">
              Generate Certificate
            </button>

            <button 
              class="creator-only w-full text-center bg-[#5865f2] text-white py-2 rounded-md hover:bg-[#4752c4] transition cursor-pointer">
              Meeting Logs
            </button>



          </div>
        </div>


    </div>

    
  </section>


<!-- Comment Modal -->
<div id="commentModal" class="hidden fixed inset-0 flex items-center justify-center z-50" style="background-color: rgba(0, 0, 0, 0.5);">
  <div class="bg-[rgba(43,45,49,0.9)] rounded-xl shadow-lg w-96 p-6 text-white">
    <h3 class="text-lg font-semibold mb-3">Leave a Comment</h3>

    <!-- Clickable stars inside modal -->
    <div id="selectedStars" class="flex justify-center space-x-1 mb-4 text-2xl cursor-pointer">
      <!-- Stars will be rendered here -->
    </div>

    <textarea id="commentText" rows="4" class="w-full p-2 rounded-md bg-[rgba(55,65,81,0.7)] text-white placeholder-gray-400" placeholder="Write your comment here..."></textarea>
    
    <div class="flex justify-end mt-4 space-x-2">
      <button id="cancelComment" class="cursor-pointer bg-[rgba(107,114,128,0.8)] px-4 py-2 rounded-md hover:bg-[rgba(75,85,99,0.9)]">Cancel</button>
      <button id="submitComment" class="cursor-pointer bg-[rgba(88,101,242,0.9)] px-4 py-2 rounded-md hover:bg-[rgba(71,82,196,1)]">Submit</button>
    </div>
  </div>
</div>
