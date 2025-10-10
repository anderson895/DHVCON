<?php 
include "../src/components/home/header.php";
?>


<!-- http://localhost/DHVCON/home/view_task?classwork_id=8 -->



<!-- Main Content -->
  <main class="flex-1 bg-[#1e1f22] ml-0 md:ml-60 p-4 transition-all duration-300">

  <!-- Main Container -->
  <div class="max-w-4xl w-full bg-[#2b2d31] shadow-xl rounded-2xl p-4 sm:p-6 md:p-8 flex flex-col gap-8 border border-[#3a3b40]">

    <!-- Assignment Details Section -->
    <section class="flex flex-col">
      <!-- Header -->
      <div class="flex items-center gap-3 mb-4 justify-center md:justify-start">
        <span class="material-icons-round text-[#5865f2] text-3xl">assignment</span>
        <h1 class="classwork_title capitalize text-xl md:text-2xl font-bold text-white">Assignment Task</h1>
      </div>

      <p class="postedby text-sm text-gray-400 mb-4 text-center md:text-left"></p>

      <!-- Description Card -->
      <div class="border border-[#3a3b40] rounded-xl p-4 md:p-6 bg-[#1e1f22] hover:border-[#5865f2] transition flex flex-col">
        <p class="classwork_instruction text-gray-300 mb-5 leading-relaxed text-sm md:text-base"></p>

        <!-- Attachment (hidden if empty) -->
        <div class="attachement-creator hidden border border-[#3a3b40] rounded-lg overflow-hidden w-full md:max-w-sm bg-[#2b2d31] hover:scale-[1.02] transition mx-auto md:mx-0"></div>
      </div>
    </section>

    <!-- Your Work Section -->
    <section class="flex flex-col mt-3">

     <!-- Fullscreen Spinner Overlay -->
      <div id="spinner" class="fixed inset-0 flex items-center justify-center z-[9999] bg-[#1e1f22]/80" style="display:none;">
        <div class="w-12 h-12 border-4 border-[#3c3f44] border-t-transparent rounded-full animate-spin"></div>
      </div>


      <form id="frmSubmittedWorks" class="flex flex-col mt-3">
        <div class="border border-[#3a3b40] rounded-xl p-4 md:p-6 bg-[#1e1f22] shadow-md flex flex-col gap-4">
          <div class="flex items-center justify-between">
            <h2 class="text-base md:text-lg font-semibold text-white">Your Work</h2>
            <span class="taskStatus text-xs md:text-sm text-green-400 font-medium bg-[#263230] px-2 py-1 rounded-md">Assigned</span>
          </div>

          <input type="file" id="fileInput" name="files[]" multiple class="hidden">

          <button type="button" id="addFilesBtn"
            class="cursor-pointer w-full border border-[#3a3b40] rounded-md py-2 text-gray-300 hover:bg-[#2f3150] hover:border-[#5865f2] transition text-sm md:text-base">
            + Add or create
          </button>

          <div id="filePreview" class="flex flex-col gap-2 max-h-48 overflow-y-auto p-1 custom-scrollbar"></div>

          <button type="submit" id="btnSubmitWorks"
            class="w-full cursor-pointer bg-[#5865f2] hover:bg-[#4752c4] text-white font-semibold py-2 rounded-md transition text-sm md:text-base">
            Mark as done
          </button>
        </div>
      </form>

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
      data: { requestType: "getClassworkDetails_where_user_id_only", classwork_id: classworkId },
      dataType: "json",
      success: function(res) {
        if (res.status === 200 && res.data) {
          const cw = res.data;

          $(".classwork_title").text(cw.classwork_title);
          $(".postedby").html(`Posted by <span class="font-medium text-gray-300">${cw.posted_by}</span> • ${cw.posted_time}`);

          // Update Turn In / Not Turned In status
          updateTurnInStatus(cw.sw_status);

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
          } else attachmentContainer.remove();

          // Existing user files with Remove button
          filePreview.empty();
          if(cw.sw_files){
            const files = JSON.parse(cw.sw_files);
            files.forEach(f=>{
              const showRemove = cw.sw_status !== 1; 
              filePreview.append(`
                <div class="flex items-center justify-between gap-2 text-gray-300 text-sm bg-[#2a2b2e] p-1 rounded">
                  <div class="flex items-center gap-2">
                    <i class="uil uil-file-alt"></i>
                    <a href="../../static/upload/${f}" target="_blank" class="truncate hover:text-blue-400">${f}</a>
                  </div>
                  ${showRemove ? `<button type="button" class="removeFileBtn cursor-pointer text-red-500 hover:text-red-400 text-xs" data-filename="${f}">Remove</button>` : ''}
                </div>
              `);
            });
          }
        }
      },
      error: function(e){ console.error(e); }
    });
  }

  // Function to update Turn In / Not Turned In UI
function updateTurnInStatus(status) {
  if(status == 1){
    $(".taskStatus").html('<span class="text-green-400 font-medium bg-[#263230] px-2 py-1 rounded-md">Turned in</span>');
    $("#btnSubmitWorks").text("Not turned in").attr("data-status","1")
      .removeClass("bg-[#5865f2] hover:bg-[#4752c4]").addClass("bg-gray-500 hover:bg-gray-600");
    
    // Disable file upload
    $("#addFilesBtn").prop("disabled", true).addClass("opacity-50 cursor-not-allowed");
    $("#fileInput").prop("disabled", true);
    $(".removeFileBtn").prop("disabled", true).addClass("opacity-50 cursor-not-allowed");

  } else {
    $(".taskStatus").html('<span class="text-gray-400 font-medium bg-[#2a2b2e] px-2 py-1 rounded-md">Not turned in</span>');
    $("#btnSubmitWorks").text("Mark as done").attr("data-status","0")
      .removeClass("bg-gray-500 hover:bg-gray-600").addClass("bg-[#5865f2] hover:bg-[#4752c4]");
    
    // Enable file upload
    $("#addFilesBtn").prop("disabled", false).removeClass("opacity-50 cursor-not-allowed");
    $("#fileInput").prop("disabled", false);
    $(".removeFileBtn").prop("disabled", false).removeClass("opacity-50 cursor-not-allowed");
  }
}


  // Remove file handler — bind only once
  filePreview.on("click", ".removeFileBtn", function(){
    const filename = $(this).data("filename");
    if(!confirm(`Remove ${filename}?`)) return;

    spinner.show();

    $.post("../controller/end-points/controller.php", 
      {
        requestType: "RemoveFile",
        classwork_id: classworkId,
        filename: filename
      },
      function(res){
        spinner.hide();
        if(res.status === "success"){
          // alertify.success("File removed successfully");
          fetchClasswork(); // refresh preview
        } else alertify.error(res.message);
      },
      "json"
    );
  });

  fetchClasswork();

  // Upload files immediately when selected
  $("#addFilesBtn").click(()=>$("#fileInput").click());
  $("#fileInput").on("change", function(){
    const files = $(this)[0].files;
    if(files.length === 0) return;

    const formData = new FormData();
    Array.from(files).forEach(f=>formData.append('files[]', f));
    formData.append('requestType', 'UploadFiles');
    formData.append('classwork_id', classworkId);

    spinner.show();

    $.ajax({
      url: "../controller/end-points/controller.php",
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      dataType: "json",
      success: function(res){
        spinner.hide();
        if(res.status === "success"){
          // alertify.success("Files uploaded successfully");
          fetchClasswork(); // refresh file preview
        } else alertify.error(res.message);
      },
      error: function(){
        spinner.hide();
        alertify.error("Failed to upload files");
      }
    });
  });

  // Submit/Mark as done
  $("#frmSubmittedWorks").submit(function(e){
    e.preventDefault();
    toggleTurnInStatus();
  });

  // Function to toggle Turn In / Not Turned In
  function toggleTurnInStatus(){
    const currentStatus = $("#btnSubmitWorks").attr("data-status");
    const requestType = currentStatus === "1" ? "UnsubmitWork" : "SubmittedWorks";

    spinner.show();
    $("#btnSubmitWorks").prop("disabled", true);

    $.post("../controller/end-points/controller.php",
      { requestType, classwork_id: classworkId },
      function(res){
        spinner.hide();
        $("#btnSubmitWorks").prop("disabled", false);

        if(res.status==="success"){
          fetchClasswork();
          // alertify.success(requestType==="SubmittedWorks" ? "Marked as Turned In" : "Marked as Not Turned In");
        }
      },
      "json"
    );
  }

});


</script>




<script src="../static/js/home/view_task.js"></script>