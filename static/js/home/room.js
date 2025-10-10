const urlParams = new URLSearchParams(window.location.search);
const code = urlParams.get('code');

let room_id = null;

$(document).ready(function() {

  // Add loading spinner
  const spinner = `
    <div id="loadingSpinner" class="flex justify-center items-center min-h-[40vh]">
      <div class="loader ease-linear rounded-full border-8 border-t-8 border-gray-200 h-16 w-16"></div>
    </div>
  `;

  // Spinner styles
  const spinnerStyle = `
    <style>
    .loader {
      border-top-color: #303030ff;
      animation: spin 1s linear infinite;
    }
    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
    </style>
  `;
  $('head').append(spinnerStyle);

  // 🧩 Fetch room details
  function fetchRoomsDetails() {
    $('#pendingWorksContainer').html(spinner);

    $.ajax({
      url: `../controller/end-points/controller.php?requestType=getRoomDetails&code=${code}`,
      type: "GET",
      dataType: "json",
      success: function(response) {
        console.log("Room Details:", response);

        if (response.status !== 200 || !response.data) {
          $('#pendingWorksContainer').html('<p class="text-red-500 text-center mt-10">Room not found.</p>');
          return;
        }

        const data = response.data;
        room_id = data.room_id;

        $(".roomName").text(data.room_name || 'Unnamed Room');
        $(".roomDescription").text(data.room_description || 'No description provided.');
        $(".roomBanner").attr("src", data.room_banner || "../static/image/default_banner.png");

        // Show or hide creator/joiner sections
        if (response.user_id !== data.creator_id) {
          $('.creator-only').hide();
          $('.joiner-only').show();
        } else {
          $('.creator-only').show();
          $('.joiner-only').hide();
        }

        // After getting room details, fetch pending works & members
        fetchAllWorksPending(data.room_name);
        fetchRoomMembers(room_id);
      },
      error: function() {
        $('#pendingWorksContainer').html('<p class="text-red-500 text-center mt-10">Failed to load room details.</p>');
      }
    });
  }

  // 🧩 Fetch pending works
  function fetchAllWorksPending(room_name) {
    if (!room_id) return;
    $('#pendingWorksContainer').html(spinner);

    $.ajax({
      url: `../controller/end-points/controller.php?requestType=getAllPendingClasswork&room_id=${room_id}`,
      type: "GET",
      dataType: "json",
      success: function(response) {
        console.log("Pending Works:", response);

        const container = $('#pendingWorksContainer');
        container.empty();

        if (response.status === 200 && response.data.length > 0) {
          response.data.forEach((work, index) => {
            const flexClass = index % 2 === 1 ? 'flex-row-reverse' : '';
            const date = new Date(work.created_at || Date.now()).toLocaleDateString('en-US', {
              month: 'short', day: 'numeric', year: 'numeric'
            });

            const post = `
              <a href="view_task?classwork_id=${work.classwork_id}&&room_name=${room_name}" 
                 class="block relative flex gap-6 ${flexClass} items-start cursor-pointer no-underline">
                <div class="bg-[#1e1f22] rounded-2xl p-6 w-full hover:bg-[#2f3150] transition">
                  <div class="flex justify-between items-center">
                    <h3 class="text-xl font-semibold text-white">${work.classwork_title}</h3>
                    <span class="text-gray-400 text-sm flex items-center gap-1">
                      <span class="material-icons-round text-gray-400 text-sm">calendar_today</span>
                      ${date}
                    </span>
                  </div>
                    <p class="text-gray-300 text-sm mt-3">
                    ${work.classwork_instruction 
                        ? (work.classwork_instruction.length > 200 
                            ? work.classwork_instruction.substring(0, 220) + '...' 
                            : work.classwork_instruction)
                        : 'No instructions provided.'}
                    </p>

                </div>
              </a>`;
            container.append(post);
          });
        } else {
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

  // 🧩 Fetch room members
  function fetchRoomMembers(roomId) {
    $.ajax({
      url: `../controller/end-points/controller.php`,
      type: 'GET',
      data: {
        requestType: 'get_rooms_members',
        room_id: roomId
      },
      dataType: 'json',
      success: function(response) {
        console.log("Members:", response);
        if (response.status === 200 && response.data.length > 0) {
          renderMembers(response.data);
        } else {
          $('#membersList').html('<p class="text-gray-400">No members found in this room.</p>');
        }
      },
      error: function() {
        $('#membersList').html('<p class="text-red-400">Failed to fetch members.</p>');
      }
    });
  }

  // 🧩 Render members
function renderMembers(members) {
  const container = $('#membersList');
  container.empty();

  members.forEach(member => {
    const fullName = member.user_fullname || `${member.firstname} ${member.lastname}`;
    const email = member.user_email || 'No email';
    const dateJoined = member.date_joined || member.created_at || null;

    // Format date nicely if available
    const joinedText = dateJoined 
      ? new Date(dateJoined).toLocaleDateString('en-US', { 
          month: 'short', 
          day: 'numeric', 
          year: 'numeric' 
        }) 
      : 'Date not available';

    const memberItem = `
      <div class="flex items-center justify-between bg-[#1f2024] p-4 rounded-xl shadow hover:bg-[#292b30] transition cursor-pointer">
        <!-- Left side: name & email -->
        <div class="flex items-center gap-4">
          <div>
            <h3 class="text-white font-semibold">${fullName}</h3>
            <p class="text-gray-400 text-sm">${email}</p>
          </div>
        </div>

       <!-- Right side: date joined -->
        <div class="flex items-center gap-2 text-gray-400 text-sm">
            <span class="material-icons-round text-[16px] text-gray-400">schedule</span>
                <span class="text-gray-500">Date Joined:</span> 
            <span class="text-gray-300">${joinedText}</span>
        </div>

      </div>`;
      
    container.append(memberItem);
  });
}




  // Start initial fetch
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
