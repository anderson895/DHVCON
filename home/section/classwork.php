<section id="classwork" class="tab-section hidden p-6 text-white">
  <div class="bg-[#2b2d31] rounded-xl p-8 shadow-lg max-w-3xl mx-auto">

    <!-- Header -->
    <div class="flex items-center mb-6">
      
      <h2 class="text-2xl font-semibold">Assignment</h2>
    </div>

    <!-- Spinner Overlay -->
    <div id="spinner" class="absolute inset-0 flex items-center justify-center z-50 bg-[#1e1f22]/70" style="display:none;">
      <div class="w-12 h-12 border-4 border-[#3c3f44] border-t-transparent rounded-full animate-spin"></div>
    </div>



    <!-- Form -->
      <form id="frmCreateClasswork" class="space-y-6">

      <!-- Title -->
      <div>
        <label for="title" class="block text-sm font-medium mb-2">
          Title <span class="text-red-500">*</span>
        </label>
        <input
          type="text"
          id="title"
          name="title"
          placeholder="Enter title"
          class="w-full p-3 bg-[#3a3b3f] border border-[#505155] rounded-lg 
          focus:ring-2 focus:ring-blue-500 focus:outline-none text-white"
          required
        />
      </div>

      <!-- Instructions -->
      <div>
        <label for="instructions" class="block text-sm font-medium mb-2">
          Instructions 
        </label>
        <textarea
          id="instructions"
          name="instructions"
          rows="5"
          placeholder="Add instructions..."
          class="w-full p-3 bg-[#3a3b3f] border border-[#505155] rounded-lg 
          focus:ring-2 focus:ring-blue-500 focus:outline-none text-white"
        ></textarea>
      </div>

            <!-- Preview container -->
        <div id="file-preview" class="mt-4 text-gray-300"></div>

        <!-- Your existing file input -->
        <div class="border-t border-gray-600 pt-6">
            <h3 class="text-sm font-medium mb-4">Attach File</h3>
            <label
              for="file-upload"
              class="flex flex-col items-center justify-center p-6 bg-[#3a3b3f] border-2 border-dashed border-gray-500 rounded-lg cursor-pointer hover:border-blue-500 transition"
            >
              <span class="material-icons text-4xl text-gray-300 mb-2">upload_file</span>
              <span class="text-sm text-gray-300">Click to upload or drag a file here</span>
              <input id="file-upload" name="file_upload" type="file" class="hidden" />
            </label>
        </div>


      <!-- Submit Button -->
      <div class="flex justify-end pt-4">
        <button
          type="submit"
          id="btnCreateWork"
          class="cursor-pointer bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2 rounded-lg transition"
        >
          Post
        </button>
      </div>
    </form>

  </div>
</section>