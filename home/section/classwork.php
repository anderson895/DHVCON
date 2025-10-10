<section id="classwork" class="tab-section hidden p-6 text-white">
  <div class="bg-[#2b2d31] rounded-2xl shadow-lg p-8">

    <!-- Spinner Overlay -->
    <div id="spinner" class="absolute inset-0 flex items-center justify-center z-50 bg-[#1e1f22]/70 hidden">
      <div class="w-12 h-12 border-4 border-[#3c3f44] border-t-transparent rounded-full animate-spin"></div>
    </div>

    <!-- Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

      <!-- LEFT: Created Works Table -->
      <div>
        <h3 class="text-xl font-semibold mb-4">All Created Works</h3>

        <div class="overflow-x-auto border border-[#3a3b3f] rounded-lg">
          <table class="min-w-full rounded-lg overflow-hidden">
            <thead class="bg-[#3a3b3f]">
              <tr>
                <th class="px-4 py-3 text-left text-sm font-medium text-gray-300">#</th>
                <th class="px-4 py-3 text-left text-sm font-medium text-gray-300">Title</th>
                <th class="px-4 py-3 text-left text-sm font-medium text-gray-300">Instructions</th>
                <th class="px-4 py-3 text-left text-sm font-medium text-gray-300">Attachment</th>
                <th class="px-4 py-3 text-left text-sm font-medium text-gray-300">Date Posted</th>
                <th class="px-4 py-3 text-center text-sm font-medium text-gray-300">Actions</th>
              </tr>
            </thead>
            <tbody id="classworkTableBody" class="divide-y divide-[#3a3b3f]">
              <!-- Dynamically inserted rows -->
              <!-- Example static row -->
              <!--
              <tr class="hover:bg-[#3a3b3f]/50 transition">
                <td class="px-4 py-3 text-sm text-gray-300">1</td>
                <td class="px-4 py-3 text-sm text-gray-300">Research Paper</td>
                <td class="px-4 py-3 text-sm text-gray-400 line-clamp-1">Write a 3-page report...</td>
                <td class="px-4 py-3 text-sm text-blue-400 underline cursor-pointer">document.pdf</td>
                <td class="px-4 py-3 text-sm text-gray-400">Oct 10, 2025</td>
                <td class="px-4 py-3 text-center">
                  <button class="text-blue-500 hover:underline mr-2 edit-btn">Edit</button>
                  <button class="text-red-500 hover:underline delete-btn">Delete</button>
                </td>
              </tr>
              -->
            </tbody>
          </table>
        </div>
      </div>

      <!-- RIGHT: Form -->
      <div class="bg-[#26272b] p-6 rounded-xl shadow-inner">
        <h3 class="text-xl font-semibold mb-4">Create New Work</h3>

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

          <!-- File Preview -->
          <div id="file-preview" class="mt-4 text-gray-300"></div>

          <!-- File Upload -->
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

    </div>
  </div>
</section>
