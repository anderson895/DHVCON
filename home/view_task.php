<?php 
include "../src/components/home/header.php";
?>
<style>
  /* Custom dark scrollbar */
.custom-scrollbar::-webkit-scrollbar {
  width: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
  background: #2b2d31; /* track color */
  border-radius: 9999px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
  background-color: #5865f2; /* thumb color */
  border-radius: 9999px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background-color: #4752c4;
}

</style>

<!-- Main Content -->
<main class="flex-1 flex items-center justify-center bg-[#1e1f22] min-h-screen py-10 px-4 sm:px-6">

  <!-- Main Container -->
  <div class="max-w-6xl w-full bg-[#2b2d31] shadow-xl rounded-2xl p-4 sm:p-6 md:p-8 flex flex-col md:flex-row gap-6 md:gap-8 border border-[#3a3b40]">

    <!-- Left Section: Assignment Details -->
    <div class="flex-1 flex flex-col">

      <!-- Header -->
      <div class="flex items-center gap-3 mb-4 md:mb-6 justify-center md:justify-start">
        <span class="material-icons-round text-[#5865f2] text-3xl">assignment</span>
        <h1 class="text-xl md:text-2xl font-bold text-white">Assignment Task</h1>
      </div>

      <p class="text-sm text-gray-400 mb-4 text-center md:text-left">
        Posted by <span class="font-medium text-gray-300">Ryan Rodriguez</span> • 4:31 PM
      </p>

      <!-- Description Card -->
      <div class="border border-[#3a3b40] rounded-xl p-4 md:p-6 bg-[#1e1f22] mb-6 md:mb-0 flex-1 hover:border-[#5865f2] transition flex flex-col">
        <p class="text-gray-300 mb-4 md:mb-5 leading-relaxed text-sm md:text-base">
          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur sit amet lacus vel nulla gravida commodo.
        </p>

        <!-- Attachment -->
        <div class="border border-[#3a3b40] rounded-lg overflow-hidden w-full md:max-w-xs bg-[#2b2d31] hover:scale-[1.02] transition mx-auto md:mx-0">
          <img src="#" alt="Attachment" class="object-cover w-full h-auto">
          <div class="p-3 text-sm text-gray-400 border-t border-[#3a3b40] bg-[#1e1f22]">
            <span class="truncate block text-gray-200 font-medium">494579798_69337385341559.png</span>
            <p class="text-xs text-gray-500">Image Attachment</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Right Section: Your Work -->
    <div class="w-full md:w-80 flex flex-col gap-4 md:gap-5">

      <!-- Your Work Card -->
      <div class="border border-[#3a3b40] rounded-xl p-4 md:p-6 bg-[#1e1f22] shadow-md flex flex-col gap-3 flex-1">

        <div class="flex items-center justify-between mb-2 md:mb-4">
          <h2 class="text-base md:text-lg font-semibold text-white">Your Work</h2>
          <span class="text-xs md:text-sm text-green-400 font-medium bg-[#263230] px-2 py-1 rounded-md">Assigned</span>
        </div>

        <!-- Hidden file input -->
        <input type="file" id="fileInput" multiple class="hidden">

        <!-- Add or create button -->
        <button id="addFilesBtn" class="cursor-pointer w-full border border-[#3a3b40] rounded-md py-2 text-gray-300 hover:bg-[#2f3150] hover:border-[#5865f2] transition mb-2 md:mb-4 text-sm md:text-base">
          + Add or create
        </button>

     <!-- Preview container -->
        <div id="filePreview" class="flex flex-col gap-2 max-h-48 overflow-y-auto p-1 custom-scrollbar"></div>


        <button class="w-full cursor-pointer bg-[#5865f2] hover:bg-[#4752c4] text-white font-semibold py-2 rounded-md transition text-sm md:text-base mt-2">
          Mark as done
        </button>
      </div>

    </div>
  </div>

</main>

<?php 
include "../src/components/home/footer.php";
?>

<script>
$(document).ready(function(){
  // Trigger hidden file input when button is clicked
  $("#addFilesBtn").click(function(){
    $("#fileInput").click();
  });

  // Show selected files as preview
  $("#fileInput").on("change", function(){
    const files = $(this)[0].files;
    const previewContainer = $("#filePreview");
    previewContainer.empty(); // clear previous previews

    Array.from(files).forEach(file => {
      const fileName = file.name;
      const fileType = file.type;

      // If image, show thumbnail
      if(fileType.startsWith("image/")) {
        const reader = new FileReader();
        reader.onload = function(e) {
          const img = $('<img>').attr('src', e.target.result)
                                .addClass('w-full h-24 object-cover rounded-md border border-gray-600');
          const caption = $('<span>').text(fileName)
                                    .addClass('text-xs text-gray-300 truncate');
          const wrapper = $('<div>').addClass('flex flex-col gap-1').append(img, caption);
          previewContainer.append(wrapper);
        }
        reader.readAsDataURL(file);
      } else {
        // For non-image files, just show filename
        const span = $('<span>').text(fileName)
                                .addClass('text-sm text-gray-300');
        previewContainer.append(span);
      }
    });
  });
});
</script>
