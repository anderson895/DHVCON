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


<!-- http://localhost/DHVCON/home/view_task?classwork_id=8 -->



<!-- Main Content -->
<main class="flex-1 flex items-center justify-center bg-[#1e1f22] min-h-screen py-10 px-4 sm:px-6">

  <!-- Main Container -->
  <div class="max-w-4xl w-full bg-[#2b2d31] shadow-xl rounded-2xl p-4 sm:p-6 md:p-8 flex flex-col gap-8 border border-[#3a3b40]">

    <!-- Assignment Details Section -->
    <section class="flex flex-col">
      <!-- Header -->
      <div class="flex items-center gap-3 mb-4 justify-center md:justify-start">
        <span class="material-icons-round text-[#5865f2] text-3xl">assignment</span>
        <h1 class="text-xl md:text-2xl font-bold text-white">Assignment Task</h1>
      </div>

      <p class="text-sm text-gray-400 mb-4 text-center md:text-left">
        Posted by <span class="font-medium text-gray-300">Ryan Rodriguez</span> • 4:31 PM
      </p>

      <!-- Description Card -->
      <div class="border border-[#3a3b40] rounded-xl p-4 md:p-6 bg-[#1e1f22] hover:border-[#5865f2] transition flex flex-col">
        <p class="classwork_instruction text-gray-300 mb-5 leading-relaxed text-sm md:text-base"></p>

        <!-- Attachment (hidden if empty) -->
        <div class="attachement-creator hidden border border-[#3a3b40] rounded-lg overflow-hidden w-full md:max-w-sm bg-[#2b2d31] hover:scale-[1.02] transition mx-auto md:mx-0"></div>
      </div>
    </section>

    <!-- Your Work Section -->
    <section class="flex flex-col mt-3">
      <div class="border border-[#3a3b40] rounded-xl p-4 md:p-6 bg-[#1e1f22] shadow-md flex flex-col gap-4">
        <div class="flex items-center justify-between">
          <h2 class="text-base md:text-lg font-semibold text-white">Your Work</h2>
          <span class="text-xs md:text-sm text-green-400 font-medium bg-[#263230] px-2 py-1 rounded-md">Assigned</span>
        </div>

        <input type="file" id="fileInput" multiple class="hidden">

        <button id="addFilesBtn"
          class="cursor-pointer w-full border border-[#3a3b40] rounded-md py-2 text-gray-300 hover:bg-[#2f3150] hover:border-[#5865f2] transition text-sm md:text-base">
          + Add or create
        </button>

        <div id="filePreview" class="flex flex-col gap-2 max-h-48 overflow-y-auto p-1 custom-scrollbar"></div>

        <button
          class="w-full cursor-pointer bg-[#5865f2] hover:bg-[#4752c4] text-white font-semibold py-2 rounded-md transition text-sm md:text-base">
          Mark as done
        </button>
      </div>
    </section>

  </div>
</main>



<?php 
include "../src/components/home/footer.php";
?>



<script>
$(document).ready(function () {
  const urlParams = new URLSearchParams(window.location.search);
  const classworkId = urlParams.get('classwork_id');
  if (!classworkId) return console.error("Missing classwork_id in URL");

  $.ajax({
    url: "../controller/end-points/controller.php",
    type: "GET",
    data: {
      requestType: "getClassworkDetails",
      classwork_id: classworkId
    },
    dataType: "json",
    success: function (res) {
      if (res.status === 200 && res.data) {
        const cw = res.data;

        // Title and details
        $("h1").text(cw.classwork_title);
        $(".text-sm.text-gray-400.mb-4").html(
          `Posted by <span class="font-medium text-gray-300">${cw.posted_by}</span> • ${cw.posted_time}`
        );

        // Instruction text with preserved spacing
        $(".classwork_instruction").html(
          cw.classwork_instruction.replace(/\r?\n/g, "<br>")
        );

        // Attachment display logic
        const attachmentContainer = $(".attachement-creator");
        if (cw.classwork_file && cw.classwork_file.trim() !== "") {
          const filePath = `../static/upload/${cw.classwork_file}`;
          const ext = cw.classwork_file.split(".").pop().toLowerCase();

          let fileHTML = "";

          if (["jpg", "jpeg", "png", "gif", "webp"].includes(ext)) {
            fileHTML = `
              <img src="${filePath}" alt="Attachment" class="object-cover w-full h-auto">
              <div class="p-3 text-sm text-gray-400 border-t border-[#3a3b40] bg-[#1e1f22]">
                <span class="truncate block text-gray-200 font-medium">${cw.classwork_file}</span>
                <p class="text-xs text-gray-500">Image Attachment</p>
              </div>
            `;
          } else {
            fileHTML = `
              <div class="text-center p-3">
                <span class="material-icons-round text-[#5865f2] text-4xl mb-2">description</span>
                <div class="text-sm text-gray-400">
                  <span class="truncate block text-gray-200 font-medium">${cw.classwork_file}</span>
                  <a href="${filePath}" target="_blank" class="text-[#5865f2] hover:underline text-xs">Download</a>
                </div>
              </div>
            `;
          }

          attachmentContainer.html(fileHTML).removeClass("hidden");
        } else {
          attachmentContainer.remove();
        }

      } else {
        console.warn("No classwork details found");
      }
    },
    error: function (xhr, status, error) {
      console.error("AJAX error:", error);
    }
  });
});
</script>






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
