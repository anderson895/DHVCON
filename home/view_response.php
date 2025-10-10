<?php 
include "../src/components/home/header.php";
?>

<!-- Main Content -->
<main class="flex-1 bg-[#1e1f22] min-h-screen py-10 px-4 sm:px-6">
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

    <!-- LEFT: Created Works Table Card -->
    <section class="bg-[#2b2d31] border border-[#3a3b40] shadow-xl rounded-2xl p-6 flex flex-col gap-4">
      <h3 class="text-xl font-semibold text-white">All Response</h3>

      <div class="overflow-x-auto border border-[#3a3b3f] rounded-lg custom-scrollbar">
        <table class="min-w-full rounded-lg overflow-hidden">
          <thead class="bg-[#3a3b3f]">
            <tr>
              <th class="px-4 py-3 text-left text-sm font-medium text-gray-300">#</th>
              <th class="px-4 py-3 text-left text-sm font-medium text-gray-300">Fullname</th>
              <th class="px-4 py-3 text-left text-sm font-medium text-gray-300">Email</th>
              <th class="px-4 py-3 text-left text-sm font-medium text-gray-300">Submitted</th>
              <th class="px-4 py-3 text-center text-sm font-medium text-gray-300">Date</th>
              <th class="px-4 py-3 text-center text-sm font-medium text-gray-300">Actions</th>
            </tr>
          </thead>
          <tbody id="classworkTableBody" class="divide-y divide-gray-700">
            <!-- Response rows will be appended here via JS -->
          </tbody>
        </table>
      </div>
    </section>

    <!-- RIGHT: Assignment Details Card -->
    <section class="bg-[#2b2d31] border border-[#3a3b40] shadow-xl rounded-2xl p-6 flex flex-col gap-6">
      <!-- Header -->
      <div class="flex items-center gap-3">
        <span class="material-icons-round text-[#5865f2] text-3xl">assignment</span>
        <h1 class="classwork_title capitalize text-xl md:text-2xl font-bold text-white">Assignment Task</h1>
      </div>

      <p class="postedby text-sm text-gray-400"></p>

      <!-- Description Card -->
      <div class="border border-[#3a3b40] rounded-xl p-4 md:p-6 bg-[#1e1f22] hover:border-[#5865f2] transition flex flex-col gap-4">
        <p class="classwork_instruction text-gray-300 mb-5 leading-relaxed text-sm md:text-base"></p>

        <!-- Attachment -->
        <div class="attachement-creator hidden border border-[#3a3b40] rounded-lg overflow-hidden w-full md:max-w-sm bg-[#2b2d31] hover:scale-[1.02] transition"></div>
      </div>

      <!-- User File Preview -->
      <div id="filePreview" class="flex flex-col gap-2"></div>
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

  const spinner = $("#spinner");
  const filePreview = $("#filePreview");

  // Fetch classwork
  function fetchClasswork() {
    $.ajax({
      url: "../controller/end-points/controller.php",
      type: "GET",
      data: { requestType: "getClassworkDetails", classwork_id: classworkId },
      dataType: "json",
      success: function(res) {
        if (res.status === 200 && res.data) {
          const cw = res.data;

          $(".classwork_title").text(cw.classwork_title);
          $(".postedby").html(`Posted by <span class="font-medium text-gray-300">${cw.posted_by}</span> • ${cw.posted_time}`);

          // Instructions
          $(".classwork_instruction").html(cw.classwork_instruction.replace(/\r?\n/g, "<br>"));

          // Creator attachment
          const attachmentContainer = $(".attachement-creator");
          if(cw.classwork_file && cw.classwork_file.trim() !== ""){
            const filePath = `../static/upload/${cw.classwork_file}`;
            const ext = cw.classwork_file.split(".").pop().toLowerCase();
            let fileHTML = '';
            if(["jpg","jpeg","png","gif","webp"].includes(ext)){
              fileHTML = `<img src="${filePath}" class="object-cover w-full h-auto">
                          <div class="p-3 text-sm text-gray-400 border-t border-[#3a3b40] bg-[#1e1f22]">
                            <span class="truncate block text-gray-200 font-medium">${cw.classwork_file}</span>
                            <p class="text-xs text-gray-500">Image Attachment</p>
                          </div>`;
            } else {
              fileHTML = `<div class="text-center p-3">
                            <span class="material-icons-round text-[#5865f2] text-4xl mb-2">description</span>
                            <div class="text-sm text-gray-400">
                              <span class="truncate block text-gray-200 font-medium">${cw.classwork_file}</span>
                              <a href="${filePath}" target="_blank" class="text-[#5865f2] hover:underline text-xs">Download</a>
                            </div>
                          </div>`;
            }
            attachmentContainer.html(fileHTML).removeClass("hidden");
          } 
        }
      },
      error: function(e){ console.error(e); }
    });
  }

  fetchClasswork();


});


</script>



