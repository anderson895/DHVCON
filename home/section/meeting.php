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
            <p class="text-sm text-gray-300">description here</p>
           
           
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