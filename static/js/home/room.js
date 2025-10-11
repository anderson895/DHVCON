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





function fetchRoomsDetails() {
  $('#pendingWorksContainer').html(spinner);

  $.ajax({
    url: `../controller/end-points/controller.php?requestType=getRoomDetails&code=${code}`,
    type: "GET",
    dataType: "json",
    success: function (response) {
      console.log("Room Details:", response);

      if (response.status !== 200 || !response.data) {
        $('#pendingWorksContainer').html('<p class="text-red-500 text-center mt-10">Room not found.</p>');
        return;
      }

      const data = response.data;
      room_id = data.room_id;
      const isCreator = response.user_id === data.creator_id;

      $(".roomName").text(data.room_name || 'Unnamed Room');
      $(".roomDescription").text(data.room_description || 'No description provided.');
      $(".roomBanner").attr("src", data.room_banner || "../static/image/default_banner.png");

      // 🔒 Role-based tab visibility
      if (isCreator) {
        $('.creator-only').show();
        $('.joiner-only').hide();
      } else {
        $('.creator-only').hide();
        $('.joiner-only').show();
      }

      // ✅ Load last active tab only if allowed
      const savedTab = localStorage.getItem("activeTab");
      if (savedTab && isTabAllowed(savedTab, isCreator)) {
        showTab(savedTab);
      } else {
        // Default tab based on role
        const defaultTab = isCreator
          ? $(".creator-only.tab-btn").first().data("tab") || "feed"
          : $(".joiner-only.tab-btn").first().data("tab") || "feed";
        showTab(defaultTab);
      }

      // ✅ Tab click handler with access control
      $(".tab-btn").off("click").on("click", function () {
        const tab = $(this).data("tab");
        if (!isTabAllowed(tab, isCreator)) {
          alert("You don’t have permission to access this section.");
          return;
        }

        localStorage.setItem("activeTab", tab);
        showTab(tab);
      });

      // ✅ Tab switch logic
      function showTab(tab) {
        $(".tab-section").hide();
        $("#" + tab).show();

        $(".tab-btn")
          .removeClass("text-white font-semibold border-b-2 border-white pb-1")
          .addClass("text-gray-400");

        $(`.tab-btn[data-tab='${tab}']`)
          .removeClass("text-gray-400")
          .addClass("text-white font-semibold border-b-2 border-white pb-1");
      }

      // ✅ Role-based access checker
      function isTabAllowed(tab, isCreator) {
        const creatorTabs = ["classwork", "members"];
        const joinerTabs = ["worksubmitted", "certificate"];
        const publicTabs = ["feed", "meeting"];

        if (publicTabs.includes(tab)) return true;
        if (isCreator && creatorTabs.includes(tab)) return true;
        if (!isCreator && joinerTabs.includes(tab)) return true;
        return false;
      }

      // Fetch other data after setup
      fetchAllWorksPending(data.room_name); 
      fetchRoomMembers(room_id);
      fetchAllCreatedWorks(room_id,data.room_name);

      fetchAllClaimedCertificates(room_id, data.room_name);

      fetchAllWorks_TurnIn(room_id);
      fetchMeetings(room_id);


      fetchClaimedCertificates(room_id);

    },
    error: function () {
      $('#pendingWorksContainer').html('<p class="text-red-500 text-center mt-10">Failed to load room details.</p>');
    },
  });
}














function fetchClaimedCertificates(room_id) {
  $.ajax({
    url: `../controller/end-points/controller.php`,
    type: 'GET',
    data: {
      requestType: 'fetchAllClaimedCertificates',
      room_id: room_id
    },
    dataType: 'json',
    success: function(response) {
      const certList = $('#certList');
      const certContainer = $('#certificate > div'); // 🎯 target the background div
      certList.empty(); // clear previous entries

      if (response.status === 200 && response.data.length > 0) {
        // ✅ Show background
        certContainer.addClass('bg-[#2b2d31] p-6 shadow-lg rounded-xl');

        // 🪪 Render certificates
        response.data.forEach(cert => {
          const certCard = `
            <div class="bg-[#1e1f22] p-4 rounded-lg shadow-md flex justify-between items-center">
              <div>
                <h3 class="text-lg font-semibold capitalize">${cert.meeting_title}</h3>
                <p class="text-gray-400 text-sm">Date Claimed: ${cert.claimed_date}</p>
                <p class="text-gray-400 text-sm">Meeting Ended: ${cert.meeting_end}</p>
              </div>
              <a href="certificate.php?meeting_id=${cert.claimed_meeting_id}&meeting_pass=${cert.meeting_pass}" 
                 target="_blank" 
                 rel="noopener noreferrer"
                 class="bg-[#5865f2] hover:bg-[#4752c4] text-white px-4 py-2 rounded-lg text-sm transition">
                 View Certificate
              </a>
            </div>
          `;
          certList.append(certCard);
        });

      } else {
        // 🚫 No certificates — remove background
        certContainer.removeClass('bg-[#2b2d31] p-6 shadow-lg rounded-xl');

        // 🖼️ Display "No Certificate" banner
        const noCertBanner = `
          <div class="flex flex-col items-center justify-center text-center py-16 w-full">
            <img src="../static/image/no_certificate.png" 
                 alt="No Certificate" 
                 class="w-72 h-auto object-contain mb-6 opacity-90">
            <h2 class="text-2xl font-semibold text-gray-200">No Certificates Found</h2>
            <p class="text-gray-400 mt-2 text-base">You currently You haven’t claimed any certificates yet.</p>
          </div>
        `;
        certList.append(noCertBanner);
      }
    },
    error: function() {
      alert('Failed to fetch certificates.');
    }
  });
}









function fetchAllClaimedCertificates(roomId, room_name) {
  $.ajax({
    url: `../controller/end-points/controller.php`,
    type: 'GET',
    data: {
      requestType: 'fetchAllClaimedCertificates',
      room_id: roomId
    },
    dataType: 'json',
    success: function(response) {
      
    }
  });
}














function fetchMeetings() {
    $.ajax({
        url: `../controller/end-points/controller.php`,
        type: "GET",
        data: {
            requestType: "getMeetingsByRoom",
            room_id: room_id
        },
        dataType: "json",
        success: function(response) {
            const container = $("#meeting .grid");
            container.empty(); 

            if (response.status === 200 && response.data.length > 0) {
                response.data.forEach(meeting => {
                    // Format Start & End Date (short format)
                    const startDate = new Date(meeting.meeting_start);
                    const endDate = new Date(meeting.meeting_end);
                    const formattedStart = startDate.toLocaleString('en-PH', { 
                        year: 'numeric', month: 'short', day: 'numeric', 
                        hour: '2-digit', minute: '2-digit', hour12: true 
                    });
                    const formattedEnd = endDate.toLocaleString('en-PH', { 
                        year: 'numeric', month: 'short', day: 'numeric', 
                        hour: '2-digit', minute: '2-digit', hour12: true 
                    });

                    const dateInfo = `
                        <p class="text-gray-400 text-sm"><span class="font-medium">Start:</span> ${formattedStart}</p>
                        <p class="text-gray-400 text-sm"><span class="font-medium">End:</span> ${formattedEnd}</p>
                    `;

                    // Determine action button
                    let actionButton = "";
                    if (meeting.meeting_status == 0) {
                        actionButton = `
                            <button 
                                class="w-full text-center bg-[#5865f2] text-white py-2 rounded-md hover:bg-[#4752c4] transition cursor-pointer generate-cert"
                                data-meeting-pass="${meeting.meeting_pass}"
                                data-meeting-id="${meeting.meeting_id}">
                                Generate Certificate
                            </button>
                        `;
                    } else if (meeting.meeting_status == 1) {
                         actionButton = `
                            <button 
                                class="join-meeting w-full text-center bg-[#5865f2] text-white py-2 rounded-md hover:bg-[#4752c4] transition cursor-pointer"
                                data-meeting-link="${meeting.meeting_link}"
                                data-meeting-id="${meeting.meeting_id}">
                                Join Meeting
                            </button>
                        `;
                    }
                      // Check if current user is the creator
                      let creatorButtons = '';
                      if (response.user_id === meeting.meeting_creator_user_id) {
                          // Determine if the Close Meeting button should be disabled
                          const closeDisabled = meeting.meeting_status == 0 ? 'disabled cursor-not-allowed opacity-50' : 'cursor-pointer btnCloseMeeting';

                          creatorButtons = `
                              <p class="text-yellow-400 font-medium text-sm">Meeting Pass: ${meeting.meeting_pass}</p>
                              <button class="w-full text-center bg-red-500 text-white py-2 rounded-md hover:bg-red-300 transition ${closeDisabled}"
                              data-meeting-id="${meeting.meeting_id}"
                              >
                                  Close Meeting
                              </button>
                             <button class="view-logs w-full text-center bg-[#5865f2] text-white py-2 rounded-md hover:bg-[#4752c4] transition cursor-pointer"
                                data-meeting-id="${meeting.meeting_id}">
                                Meeting Logs
                            </button>

                          `;
                      }


                    const card = `
                        <div class="bg-[#2b2d31] rounded-xl overflow-hidden shadow-md">
                            <div class="p-4 space-y-3">
                                <h3 class="font-semibold text-lg text-white uppercase">${meeting.meeting_title}</h3>
                                ${dateInfo}
                                <p class="text-sm text-gray-300">${meeting.meeting_description}</p>
                                ${creatorButtons || actionButton}
                            </div>
                        </div>
                    `;
                    container.append(card);
                });

                $(".generate-cert").click(function() {
                    const meetingPass = $(this).data("meeting-pass");
                    const meetingId = $(this).data("meeting-id");

                    Swal.fire({
                        title: 'Enter Meeting Pass',
                        input: 'password',
                        inputLabel: 'Meeting Pass',
                        inputPlaceholder: 'Enter the meeting pass',
                        showCancelButton: true,
                        confirmButtonText: 'Submit',
                        cancelButtonText: 'Cancel',
                        inputValidator: (value) => {
                            if (!value) return 'Meeting pass cannot be empty!';
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            if (result.value === meetingPass) {
                                // Show processing Swal for 1 second
                                Swal.fire({
                                    title: 'Processing...',
                                    timer: 1000,
                                    didOpen: () => {
                                        Swal.showLoading();
                                    },
                                    allowOutsideClick: false,
                                    allowEscapeKey: false
                                }).then(() => {

                                    window.open(`certificate?meeting_id=${meetingId}&&meeting_pass=${meetingPass}`, '_blank');
                                     

                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Invalid Pass',
                                    text: 'The meeting pass you entered is incorrect.'
                                });
                            }
                        }
                    });
                });


            } else {
              container.append(`
               <div class="col-span-full text-center px-4">
  <img 
    src="../static/image/no_schedule_banner.png" 
    alt="No meetings" 
    class="mx-auto w-full max-w-xs sm:max-w-sm md:max-w-md lg:max-w-lg h-auto" 
  />
  <p class="text-gray-400 mt-4 text-base sm:text-lg md:text-xl">No meetings scheduled.</p>
</div>


              `);

            }
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
}










// Handle View Logs click
$(document).on("click", ".view-logs", function () {
    const meetingId = $(this).data("meeting-id");

    $.ajax({
        url: "../controller/end-points/controller.php",
        type: "GET",
        data: {
            requestType: "viewMeetingLogs",
            meeting_id: meetingId
        },
        dataType: "json",
        success: function (res) {
            if (res.status === 200 && res.data.length > 0) {
                let logRows = "";

                res.data.forEach((log, index) => {
                    const formattedDate = new Date(log.ml_date_joined).toLocaleString("en-PH", {
                        year: "numeric",
                        month: "short",
                        day: "numeric",
                        hour: "2-digit",
                        minute: "2-digit",
                        hour12: true
                    });

                    logRows += `
                        <tr class="border-b border-gray-700 log-row">
                            <td class="px-4 py-2 text-center">${index + 1}</td>
                            <td class="px-4 py-2 name flex items-center gap-2">
                                <span class="material-icons text-[#5865f2] text-base">person</span>
                                ${log.user_fullname}
                            </td>
                            <td class="px-4 py-2 email">${log.user_email}</td>
                            <td class="px-4 py-2 text-center">${formattedDate}</td>
                        </tr>
                    `;
                });

                const tableHTML = `
                    <div class="relative mb-3">
                        <span class="material-icons absolute left-3 top-2.5 text-gray-400">search</span>
                        <input 
                            type="text" 
                            id="searchLogInput"
                            placeholder="Search name or email..."
                            class="w-full pl-10 pr-3 py-2 rounded-md bg-[#1e1f22] text-gray-200 border border-gray-600 focus:outline-none focus:border-[#5865f2]"
                        >
                    </div>
                    <div class="overflow-x-auto max-h-[400px] overflow-y-auto">
                        <table class="min-w-full text-sm text-gray-300">
                            <thead class="bg-[#1e1f22] text-gray-100 uppercase sticky top-0">
                                <tr>
                                    <th class="px-4 py-3 text-center">#</th>
                                    <th class="px-4 py-3 text-left flex items-center gap-1"> <span class="material-icons text-[#5865f2] text-sm">people</span>
                                        Name
                                    </th>
                                    <th class="px-4 py-3 text-center">Email</th>
                                    <th class="px-4 py-3 text-center">Date Joined</th>
                                </tr>
                            </thead>
                            <tbody class="bg-[#2b2d31]" id="logsTableBody">
                                ${logRows}
                            </tbody>
                        </table>
                    </div>
                `;

                Swal.fire({
                    title: "Meeting Logs",
                    html: tableHTML,
                    width: "80%",
                    background: "#2b2d31",
                    color: "#fff",
                    showConfirmButton: true,
                    confirmButtonText: "Close",
                    scrollbarPadding: false,
                    didOpen: () => {
                      
                        const searchInput = document.getElementById("searchLogInput");
                        const rows = document.querySelectorAll(".log-row");

                        searchInput.addEventListener("input", function () {
                            const query = this.value.toLowerCase();

                            rows.forEach(row => {
                                const name = row.querySelector(".name").textContent.toLowerCase();
                                const email = row.querySelector(".email").textContent.toLowerCase();

                                row.style.display = (name.includes(query) || email.includes(query)) ? "" : "none";
                            });
                        });
                    },
                    customClass: {
                        popup: "rounded-lg shadow-lg"
                    }
                });
            } else if (res.status === 404) {
                Swal.fire({
                    icon: "info",
                    title: "No Logs Found",
                    text: "No participants have joined this meeting yet.",
                    background: "#2b2d31",
                    color: "#fff"
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Unable to fetch meeting logs.",
                    background: "#2b2d31",
                    color: "#fff"
                });
            }
        },
        error: function (xhr, status, error) {
            console.error(error);
            Swal.fire({
                icon: "error",
                title: "Request Error",
                text: "Something went wrong while fetching logs.",
                background: "#2b2d31",
                color: "#fff"
            });
        }
    });
});












// Handle Join Meeting click
$(document).on("click", ".join-meeting", function() {
    const meetingId = $(this).data("meeting-id");
    const meetingLink = $(this).data("meeting-link");

    $.ajax({
        url: "../controller/end-points/controller.php",
        type: "POST",
        data: {
            requestType: "recordMeetingLog",
            meeting_id: meetingId,
        },
        dataType: "json",
        success: function(res) {
            if (res.status === 200 || res.status === 409) {
                window.open(meetingLink, "_blank");
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Failed to Log",
                    text: "Unable to record your attendance."
                });
            }
        },
        error: function(xhr, status, error) {
            console.error(error);
            Swal.fire({
                icon: "error",
                title: "Request Error",
                text: "Something went wrong while recording the log."
            });
        }
    });
});




// Inside fetchMeetings(), after appending all cards
$(document).on('click', '.btnCloseMeeting', function() {
    const button = $(this);

    console.log('click');

    // Prevent clicking if disabled
    if (button.prop("disabled")) return;

    // Optionally, confirm with user
    Swal.fire({
        title: 'Close Meeting?',
        text: "Are you sure you want to close this meeting?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, close it',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
           
            const meetingId = $(this).data("meeting-id"); 

            // Send AJAX request to update status
            $.ajax({
                url: '../controller/end-points/controller.php',
                type: 'POST',
                data: {
                    requestType: 'closeMeeting',
                    meeting_id: meetingId
                },
                dataType: 'json',
                success: function(res) {
                    if (res.status === 200) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Meeting Closed',
                            timer: 1000,
                            showConfirmButton: false
                        });

                        // Disable the button immediately
                        button.prop("disabled", true).addClass("cursor-not-allowed opacity-50").removeClass("hover:bg-red-300");
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: res.message || 'Something went wrong.'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Unable to close the meeting.'
                    });
                }
            });
        }
    });
});




    // Initial fetch
  





  // Fetch pending works
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








function fetchAllWorks_TurnIn(room_name) {
  if (!room_id) return;
  $('#submittedWorksContainer').html(spinner);

  $.ajax({
    url: `../controller/end-points/controller.php?requestType=getAllSubmittedClasswork_Joiner&room_id=${room_id}`,
    type: "GET",
    dataType: "json",
    success: function(response) {
      console.log("Pending Works:", response);

      const container = $('#submittedWorksContainer');
      const workBox = $('#worksubmitted > div'); // 🎯 target yung div na may background

      container.empty();

      if (response.status === 200 && response.data.length > 0) {
        // ✅ show background kapag may laman
        workBox.addClass('bg-[#2b2d31] rounded-2xl shadow-lg p-8');

        // 🪪 render submitted works
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
                  <h3 class="capitalize text-xl font-semibold text-white">${work.classwork_title}</h3>
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
        // 🚫 No submitted works — remove background
        workBox.removeClass('bg-[#2b2d31] rounded-2xl shadow-lg p-8');

        // 🖼️ Display banner
        const banner = `
          <div class="flex flex-col items-center justify-center text-center py-16 w-full">
            <img src="../static/image/no_rooms_banner.png" 
                 alt="No Submitted Works" 
                 class="w-72 h-auto object-contain mb-6 opacity-90">
            <h2 class="text-2xl font-semibold text-gray-200">No Submitted Works</h2>
            <p class="text-gray-400 mt-2 text-base">You haven't submitted any classwork yet.</p>
          </div>`;
        container.html(banner);
      }
    },
    error: function() {
      $('#submittedWorksContainer').html('<p class="text-red-500 text-center mt-10">Failed to load submitted works.</p>');
    }
  });
}











  // Fetch room members
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




function fetchAllCreatedWorks(roomId, room_name) {
  $.ajax({
    url: `../controller/end-points/controller.php`,
    type: 'GET',
    data: {
      requestType: 'get_all_created_works',
      room_id: roomId
    },
    dataType: 'json',
    success: function(response) {
      const tbody = $('#classworkTableBody');
      tbody.empty(); // clear existing rows

      if (response.status === 200 && response.data.length > 0) {
        response.data.forEach((work, index) => {
          const formattedDate = new Date(work.created_at).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
          });

          const fileDisplay = work.classwork_file
            ? `<a href="../static/upload/${work.classwork_file}" target="_blank" class="text-blue-400 underline cursor-pointer">${work.classwork_file}</a>`
            : `<span class="text-gray-500 italic">No file</span>`;

          const instructionText = work.classwork_instruction || 'No instructions provided.';
          const maxLength = 50;
          const truncatedInstruction = instructionText.length > maxLength
            ? instructionText.substring(0, maxLength) + '...'
            : instructionText;

          const seeMoreButton = instructionText.length > maxLength
            ? ` <button class="see-more-btn cursor-pointer text-blue-400 hover:underline text-xs">See more</button>`
            : '';

          // Append row
          const row = `
            <tr class="hover:bg-[#3a3b3f]/50 transition">
              <td class="px-4 py-3 text-sm text-gray-300">${index + 1}</td>
              <td class="px-4 py-3 text-sm text-gray-300">${work.classwork_title}</td>
              <td class="px-4 py-3 text-sm text-gray-400 instruction-cell">
                <span class="instruction-text" data-full="${instructionText}">${truncatedInstruction}</span>${seeMoreButton}
              </td>
              <td class="px-4 py-3 text-sm">${fileDisplay}</td>
              <td class="px-4 py-3 text-sm text-gray-400">${formattedDate}</td>
              <td class="px-4 py-3 text-center">
                <div class="flex justify-center items-center gap-2">
                  <button 
                    class="cursor-pointer edit-btn bg-blue-500 hover:bg-blue-700 text-white text-sm px-3 py-1.5 rounded-lg transition-colors duration-200 shadow-sm" 
                    data-id="${work.classwork_id}">
                    Edit
                  </button>
                  <a 
                    href="view_response?classwork_id=${work.classwork_id}&&room_name=${room_name}" 
                    class="view-response-btn cursor-pointer bg-gray-600 hover:bg-gray-700 text-white text-sm px-3 py-1.5 rounded-lg transition-colors duration-200 shadow-sm">
                    Response
                  </a>
                  <button 
                    class="delete-btn bg-red-500 cursor-pointer hover:bg-red-700 text-white text-sm px-3 py-1.5 rounded-lg transition-colors duration-200 shadow-sm" 
                    data-id="${work.classwork_id}">
                    Delete
                  </button>
                </div>
              </td>
            </tr>
          `;
          tbody.append(row);
        });
      } else {
        tbody.html(`
          <tr>
            <td colspan="6" class="text-center text-gray-400 py-4">
              No created works found.
            </td>
          </tr>
        `);
      }
    },
    error: function() {
      $('#classworkTableBody').html(`
        <tr>
          <td colspan="6" class="text-center text-red-400 py-4">
            Failed to fetch classworks.
          </td>
        </tr>
      `);
    }
  });
}




















// Toggle "See more" / "See less"
$(document).on('click', '.see-more-btn', function() {
  const $btn = $(this);
  const $text = $btn.siblings('.instruction-text');
  const fullText = $text.data('full');

  if ($btn.text() === 'See more') {
    $text.text(fullText);
    $btn.text('See less');
  } else {
    const maxLength = 50;
    const truncated = fullText.length > maxLength ? fullText.substring(0, maxLength) + '...' : fullText;
    $text.text(truncated);
    $btn.text('See more');
  }
});







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
      <div class="flex items-center justify-between bg-[#1f2024] p-4 rounded-xl shadow transition">
        <!-- Left side: name & email -->
        <div class="flex items-center gap-4">
          <div>
            <h3 class="capitalize text-white font-semibold">${fullName}</h3>
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












 $("#frmMeeting").submit(function (e) {
    e.preventDefault();

    $('.spinner').show();
    $('#btnCreateMeeting').prop('disabled', true);

    var formData = new FormData(this);
    formData.append('requestType', 'CreateMeeting');
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
                $('.spinner').hide();
                $('#btnCreateMeeting').prop('disabled', false);
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











// LEAVE ROOM 

$(document).ready(function() {
    $('#btnLeaveRoom').on('click', function(e) {
        e.preventDefault(); 

        let room_code = $(this).data("code");

        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to leave the room!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, leave',
            cancelButtonText: 'Cancel',
            background: '#2b2d31',  // Dark background
            color: '#fff',           // White text
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            customClass: {
                popup: 'swal-dark-popup',
                title: 'swal-dark-title',
                content: 'swal-dark-content',
                confirmButton: 'swal-dark-confirm',
                cancelButton: 'swal-dark-cancel'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "../controller/end-points/controller.php",
                    type: 'POST',
                    data: {
                        room_code: room_code,
                        requestType: "LeaveRoom"
                    },
                    success: function(response) {
                        Swal.fire({
                            title: 'Left!',
                            text: 'You have left the room.',
                            icon: 'success',
                            background: '#2b2d31',
                            color: '#fff',
                            confirmButtonColor: '#3085d6'
                        }).then(() => {
                            window.location.href = '../home/';
                        });
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Something went wrong. Please try again.',
                            icon: 'error',
                            background: '#2b2d31',
                            color: '#fff',
                            confirmButtonColor: '#d33'
                        });
                    }
                });
            }
        });
    });
});
