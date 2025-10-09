const urlParams = new URLSearchParams(window.location.search);
const code = urlParams.get('code');

let room_id = null;
$(document).ready(function() {

    // ADD LOADING SPINNER
    const spinner = `
        <div id="loadingSpinner" class="flex justify-center items-center min-h-[40vh]">
            <div class="loader ease-linear rounded-full border-8 border-t-8 border-gray-200 h-16 w-16"></div>
        </div>
    `;

    // Add spinner CSS (you can put this in your CSS file instead)
    const spinnerStyle = `
        <style>
        .loader {
            border-top-color: #303030ff;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg);}
            100% { transform: rotate(360deg);}
        }
        </style>
    `;
    $('head').append(spinnerStyle);

    // FETCH ROOM DETAILS
        function fetchRoomsDetails() {
        // Show spinner while fetching room details
        $('#pendingWorksContainer').html(spinner);

        $.ajax({
            url: `../controller/end-points/controller.php?requestType=getRoomDetails&code=${code}`,
            type: "GET",
            dataType: "json",
            success: function(response) {
                console.log("Room Details:", response);

                const data = response.data;
                room_id = data.room_id;

                $(".roomName").text(data.room_name);
                $(".roomDescription").text(data.room_description);
                $(".roomBanner").attr("src", data.room_banner);

              if (response.user_id !== data.creator_id) {
                    $('.creator-only').hide();
                    $('.joiner-only').show();
                } else {
                    $('.creator-only').show();
                    $('.joiner-only').hide();
                }


            // Now fetch pending works after we have room_id
            fetchAllWorksPending();
        },
        error: function() {
            $('#pendingWorksContainer').html('<p class="text-red-500 text-center mt-10">Failed to load room details.</p>');
        }
    });
}


    function fetchAllWorksPending() {
        if (!room_id) return;

        // Show spinner while fetching pending works
        $('#pendingWorksContainer').html(spinner);

        $.ajax({
            url: `../controller/end-points/controller.php?requestType=getAllPendingClasswork&room_id=${room_id}`,
            type: "GET",
            dataType: "json",
            success: function(response) {
                console.log("Pending Works:", response);

                const container = $('#pendingWorksContainer');
                container.empty(); // remove existing posts

                if (response.status === 200 && response.data.length > 0) {
                    response.data.forEach(function(work, index) {
                        const flexClass = index % 2 === 1 ? 'flex-row-reverse' : '';

                        const post = `
                        <a href="view_task?classwork_id=${work.classwork_id}" class="block relative flex gap-6 ${flexClass} items-start cursor-pointer no-underline">
                            <div class="bg-[#1e1f22] rounded-2xl p-6 w-full hover:bg-[#2f3150] transition">
                                <div class="flex justify-between items-center">
                                    <h3 class="text-xl font-semibold text-white">${work.classwork_title}</h3>
                                    <span class="text-gray-400 text-sm flex items-center gap-1">
                                        <span class="material-icons-round text-gray-400 text-sm">calendar_today</span>
                                        ${new Date(work.classwork_date_start || Date.now()).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}
                                    </span>
                                </div>
                                <p class="text-gray-400 text-sm mt-1">By User ${work.classwork_by_user_id}</p>
                                <p class="text-gray-300 text-sm mt-3">${work.classwork_instruction}</p>
                            </div>
                        </a>`;

                        container.append(post);
                    });
                } else {
                    // Display banner if no pending works
                    const banner = `
                    <div class="col-span-full flex flex-col items-center justify-center min-h-[40vh] text-center animate-fadeIn w-full">
                        <img src="../static/image/no_rooms_banner.png" 
                            alt="No Pending Works" 
                            class="w-full max-w-3xl h-auto object-cover mb-8 rounded-2xl">
                        <h2 class="text-2xl font-bold text-white mb-3">No Pending Works Available</h2>
                        <p class="text-gray-400 text-lg">You currently have no pending classworks in this room.</p>
                    </div>`;

                    container.html(banner);
                }
            },
            error: function() {
                $('#pendingWorksContainer').html('<p class="text-red-500 text-center mt-10">Failed to load pending works.</p>');
            }
        });
    }

    fetchRoomsDetails();

});









$(document).ready(function () {
  // ✅ Load last active tab (if any)
  const savedTab = localStorage.getItem("activeTab");
  if (savedTab) {
    showTab(savedTab);
  } else {
    // Default: show first tab if nothing saved
    const firstTab = $(".tab-btn").first().data("tab");
    showTab(firstTab);
  }

  // ✅ On tab click
  $(".tab-btn").click(function () {
    const tab = $(this).data("tab");
    // Save the active tab to localStorage
    localStorage.setItem("activeTab", tab);

    // Show the clicked tab
    showTab(tab);
  });

  // ✅ Function to handle tab switching
  function showTab(tab) {
    // Hide all tab sections
    $(".tab-section").hide();

    // Show selected section
    $("#" + tab).show();

    // Reset all tab styles
    $(".tab-btn")
      .removeClass("text-white font-semibold border-b-2 border-white pb-1")
      .addClass("text-gray-400");

    // Highlight active tab
    $(`.tab-btn[data-tab='${tab}']`)
      .removeClass("text-gray-400")
      .addClass("text-white font-semibold border-b-2 border-white pb-1");
  }



  
  $("#frmCreateClasswork").submit(function (e) {
    e.preventDefault();

    $('#spinner').show();
    $('#btnCreateWork').prop('disabled', true);

    var formData = new FormData(this);
    formData.append('requestType', 'CreateClasswork');
    formData.append('room_id', room_id); 

    $.ajax({
        type: "POST",
        url: "../controller/end-points/controller.php",
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function (response) {
            console.log(response);

            if (response.status === "success") {
                alertify.success('Created Successfully');
                setTimeout(function () {
                    // ✅ Reload page but keep tab
                    location.reload();
                }, 1000);
            } else {
                $('#spinner').hide();
                $('#btnCreateWork').prop('disabled', false);
                alertify.error(response.message);
            }
        }
    });
});



});











$(document).ready(function() {

  // Open modal
  $('#btnCreateMeeting').click(function() {
    $('#createMeetingModal').fadeIn().css('display', 'flex');
  });

  // Close modal
  $('#closeModal, #cancelMeeting').click(function() {
    $('#createMeetingModal').fadeOut();
  });

  // Handle form submission
  $('#meetingForm').submit(function(e) {
    e.preventDefault();

    const meetingData = {
      link: $('input[name="meeting_link"]').val(),
      title: $('input[name="meeting_title"]').val(),
      description: $('textarea[name="meeting_description"]').val(),
      start: $('input[name="start_date"]').val(),
      end: $('input[name="end_date"]').val()
    };

    console.log("Meeting Data:", meetingData);
    alert("Meeting saved successfully!");
    $('#createMeetingModal').fadeOut();
  });

});






$(document).ready(function() {
    $("#file-upload").on("change", function() {
        const file = this.files[0];
        const preview = $("#file-preview");
        preview.empty(); 

        if (!file) return;

        // If image, show thumbnail
        if (file.type.startsWith("image/")) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = $("<img>")
                    .attr("src", e.target.result)
                    .addClass("max-h-48 rounded-lg shadow-md mt-2");
                preview.append(img);
            };
            reader.readAsDataURL(file);
        } else {
            // For other file types, just show file name
            preview.text("Selected file: " + file.name);
        }
    });
});
