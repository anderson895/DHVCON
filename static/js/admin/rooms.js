$(document).ready(function () {

  // Fetch data
  let currentPage = 1;
  let limit = 10;

  // ===============================
  // FETCH ALL ROOMS
  // ===============================
  function loadRooms(page = 1) {
    currentPage = page;

    $.ajax({
      url: "../controller/end-points/controller.php",
      method: "GET",
      data: {
        requestType: "fetch_all_room_pages",
        page: page,
        limit: limit
      },
      dataType: "json",
      beforeSend: function () {
        $('#roomTableBody').html(`
          <tr>
            <td colspan="11" class="p-6 text-center">
              <div class="flex items-center justify-center space-x-2">
                <div class="w-6 h-6 border-2 border-yellow-400 border-t-transparent rounded-full animate-spin"></div>
                <span class="text-yellow-400 font-medium">Loading...</span>
              </div>
            </td>
          </tr>
        `);
        $('#pagination').empty();
      },
      success: function (res) {
        $('#roomTableBody').empty();

        if (res.status === 200 && res.data.length > 0) {
          res.data.forEach(data => {
            $('#roomTableBody').append(`
              <tr class="hover:bg-[#2B2B2B] transition-colors">
                <td class="p-3 font-mono">${data.room_code}</td>
                <td class="p-3">${data.room_name}</td>
                <td class="p-3">${data.room_creator_name}</td>
                <td class="p-3"><img src="../static/upload/${data.room_banner}" class="w-12 h-12 rounded object-cover border border-gray-600"></td>

                <td class="p-3 text-center">

                  <button class="viewClassworkBtn cursor-pointer bg-yellow-400 hover:bg-yellow-500 text-black px-3 py-1 rounded text-xs font-semibold"
                    data-room_id="${data.room_id}"
                    data-room_name="${data.room_name}">
                    Classwork
                  </button>

                  <button class="viewMemberBtn cursor-pointer bg-yellow-400 hover:bg-yellow-500 text-black px-3 py-1 rounded text-xs font-semibold"
                    data-room_id="${data.room_id}"
                    data-room_name="${data.room_name}">
                    Member
                  </button>

                  <button class="viewMeetingBtn cursor-pointer bg-yellow-400 hover:bg-yellow-500 text-black px-3 py-1 rounded text-xs font-semibold"
                    data-room_id="${data.room_id}"
                    data-room_name="${data.room_name}">
                    Meeting
                  </button>

                </td>
              </tr>
            `);
          });

          renderPagination(res.total, limit, currentPage);

        } else {
          $('#roomTableBody').append(`
            <tr><td colspan="11" class="p-4 text-center text-gray-400 italic">No record found</td></tr>
          `);
        }
      }
    });

  }

  function renderPagination(totalRows, limit, currentPage) {
    let totalPages = Math.ceil(totalRows / limit);
    let paginationHTML = '';

    if (totalPages > 1) {
      paginationHTML += `
        <button class="px-3 cursor-pointer py-1 bg-gray-700 text-white rounded ${currentPage === 1 ? 'opacity-50 cursor-not-allowed' : ''}"
          ${currentPage === 1 ? 'disabled' : ''} data-page="${currentPage - 1}">Prev</button>
      `;

      for (let i = 1; i <= totalPages; i++) {
        paginationHTML += `
          <button class="px-3 cursor-pointer py-1 mx-1 rounded ${i === currentPage ? 'bg-yellow-400 text-black' : 'bg-gray-700 text-white'}"
            data-page="${i}">${i}</button>
        `;
      }

      paginationHTML += `
        <button class="px-3 cursor-pointer py-1 bg-gray-700 text-white rounded ${currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : ''}"
          ${currentPage === totalPages ? 'disabled' : ''} data-page="${currentPage + 1}">Next</button>
      `;
    }
    $('#pagination').html(paginationHTML);
  }

  $(document).on('click', '#pagination button', function () {
    let page = $(this).data('page');
    if (page) loadRooms(page);
  });

  loadRooms(); // initial fetch



  // ===============================
  // FETCH CLASSWORKS
  // ===============================
  function fetchAllCreatedWorks(roomId) {

    $.ajax({
      url: "../controller/end-points/controller.php",
      type: "GET",
      data: {
        requestType: "get_all_created_works",
        room_id: roomId
      },
      dataType: "json",
      success: function (response) {
        const tbody = $('#classworkTableBody');
        tbody.empty();

        if (response.status === 200 && response.data.length > 0) {
          response.data.forEach((work, index) => {
            const formattedDate = new Date(work.created_at).toLocaleDateString('en-US', {
              year: 'numeric',
              month: 'short',
              day: 'numeric'
            });

            const fileDisplay = work.classwork_file
              ? `<a href="../static/upload/${work.classwork_file}" target="_blank" class="text-blue-400 underline">${work.classwork_file}</a>`
              : `<span class="text-gray-500 italic">No file</span>`;

            const instructionText = work.classwork_instruction || 'No instructions provided.';
            const maxLength = 50;
            const truncatedInstruction = instructionText.length > maxLength
              ? instructionText.substring(0, maxLength) + '...'
              : instructionText;

            const seeMoreButton = instructionText.length > maxLength
              ? ` <button class="see-more-btn text-blue-400 hover:underline text-xs">See more</button>`
              : '';

            const row = `
              <tr class="hover:bg-[#3a3b3f]/50 transition">
                <td class="px-4 py-3 text-sm text-gray-300">${index + 1}</td>
                <td class="px-4 py-3 text-sm text-gray-300">${work.classwork_title}</td>
                <td class="px-4 py-3 text-sm text-gray-400 instruction-cell">
                  <span class="instruction-text" data-full="${instructionText}">${truncatedInstruction}</span>${seeMoreButton}
                </td>
                <td class="px-4 py-3 text-sm">${fileDisplay}</td>
                <td class="px-4 py-3 text-sm text-gray-400">${formattedDate}</td>
              </tr>
            `;
            tbody.append(row);
          });

        } else {
          tbody.html(`
            <tr>
              <td colspan="6" class="text-center text-gray-400 py-4">No created works found.</td>
            </tr>
          `);
        }
      }
    });
  }



  // ===============================
  // FETCH MEMBERS
  // ===============================
  function fetchMembers(roomId) {
    $.ajax({
      url: "../controller/end-points/controller.php",
      type: "GET",
      data: {
        requestType: "get_rooms_members",
        room_id: roomId
      },
      dataType: "json",
      success: function (res) {
        const tbody = $("#memberTableBody");
        tbody.empty();

        if (res.status === 200 && res.data.length > 0) {
          res.data.forEach((m, i) => {
            tbody.append(`
              <tr class="hover:bg-[#3a3b3f]/50 transition">
                <td class="p-3">${i + 1}</td>
                <td class="p-3">${m.user_fullname}</td>
                <td class="p-3">${m.user_email}</td>
                <td class="p-3">${m.date_joined}</td>
              </tr>
            `);
          });
        } else {
          tbody.html(`<tr><td colspan="4" class="text-center py-3 text-gray-400">No members found.</td></tr>`);
        }
      }
    });
  }


// ===============================
// FETCH MEETINGS FOR MEETING MODAL
// ===============================
function fetchMeetings(roomId) {
  $.ajax({
    url: "../controller/end-points/controller.php",
    type: "GET",
    data: {
      requestType: "getMeetingsByRoom",
      room_id: roomId
    },
    dataType: "json",

    success: function (response) {
      const tbody = $("#meetingTableBody");
      tbody.empty();

      if (response.status === 200 && response.data.length > 0) {

        response.data.forEach((meeting, index) => {

          // ==========================
          // FORMAT DATES
          // ==========================
          const start = new Date(meeting.meeting_start);
          const end = new Date(meeting.meeting_end);
          const created = new Date(meeting.meeting_start);

          const startFormatted = start.toLocaleString("en-US", {
            year: "numeric",
            month: "short",
            day: "numeric",
            hour: "2-digit",
            minute: "2-digit",
            hour12: true
          });

          const endFormatted = end.toLocaleString("en-US", {
            year: "numeric",
            month: "short",
            day: "numeric",
            hour: "2-digit",
            minute: "2-digit",
            hour12: true
          });

          const createdFormatted = created.toLocaleDateString("en-US", {
            year: "numeric",
            month: "short",
            day: "numeric"
          });

          // ==========================
          // AVERAGE RATING
          // ==========================
          const avgRating = parseFloat(meeting.average_rating) || 0;
          const avgStars = [...Array(5)].map((_, i) =>
            `<span class="${i < avgRating ? "text-yellow-400" : "text-gray-600"}">&#9733;</span>`
          ).join("");

          // ==========================
          // COMMENTS + AVATAR SECTION
          // ==========================
          let commentsHTML = "";

          if (meeting.ratings.length > 0) {
            meeting.ratings.forEach(r => {

              let userAvatar = "";
              if (r.user_profile_pict) {
                userAvatar = `
                  <img src="../static/upload/profile/${r.user_profile_pict}" 
                       alt="${r.username}" 
                       class="w-10 h-10 rounded-full object-cover">
                `;
              } else {
                const firstLetter = r.username.charAt(0).toUpperCase();
                userAvatar = `
                  <div class="w-10 h-10 rounded-full bg-gray-600 text-white flex items-center justify-center font-semibold">
                    ${firstLetter}
                  </div>
                `;
              }

              commentsHTML += `
                <div class="border border-gray-700 p-3 rounded-md bg-[#252525] mb-2 w-full max-w-[650px]">
                  <div class="flex items-center space-x-3">
                    ${userAvatar}
                    <div class="flex-1">
                      <p class="font-semibold text-white">${r.username}</p>

                      <div class="text-yellow-400 text-sm">
                        ${[1,2,3,4,5].map(i => `
                          <span class="${i <= r.rating ? "text-yellow-400" : "text-gray-600"}">&#9733;</span>
                        `).join("")}
                      </div>

                      <p class="text-gray-300 mt-1 break-words whitespace-pre-line">
                        ${r.comment}
                      </p>
                    </div>
                  </div>
                </div>
              `;
            });

          } else {
            commentsHTML = `
              <p class="text-gray-500 italic text-center w-full py-2">
                No comments yet.
              </p>
            `;
          }

          // ============================================
          // MAIN MEETING ROW
          // ============================================
          const mainRow = `
            <tr class="border-b border-gray-700">
              <td class="p-3">${index + 1}</td>

              <td class="p-3">
                <p class="text-white font-semibold">${meeting.meeting_title}</p>
                <p class="text-gray-400 text-sm">${meeting.meeting_description}</p>

                <div class="mt-2">
                  <span class="font-semibold text-gray-300">Average Rating: </span>
                  ${avgStars}
                  <span class="text-gray-400 text-sm ml-1">(${avgRating.toFixed(1)})</span>
                </div>
              </td>

              <td class="p-3 text-gray-300 text-sm">
                <p><span class="font-semibold">Start:</span> ${startFormatted}</p>
                <p><span class="font-semibold">End:</span> ${endFormatted}</p>
              </td>

              <td class="p-3 text-gray-300 text-sm">
                ${createdFormatted}
              </td>
            </tr>
          `;

          // ============================================
          // COMMENT ROW ALWAYS DISPLAYED (with toggler)
          // ============================================
          const id = meeting.meeting_id;

          const commentRow = `
            <tr class="border-b border-gray-800 bg-[#1b1b1b]">
              <td colspan="4" class="p-4">

                <!-- Toggle Header -->
                <div class="flex items-center justify-between cursor-pointer toggle-comments" data-target="comments-${id}">
                  <p class="text-gray-300 font-semibold text-lg">Comments</p>
                  <span id="arrow-${id}" class="text-white text-xl transition-transform">&#9660;</span>
                </div>

                <!-- Collapsible Section -->
                <div id="comments-${id}" class="max-h-0 overflow-hidden transition-all duration-300">
                  <div class="max-h-48 overflow-y-auto w-full flex flex-col items-center mt-3">
                    ${commentsHTML}
                  </div>
                </div>

              </td>
            </tr>
          `;

          tbody.append(mainRow);
          tbody.append(commentRow);
        });

        // =====================
        // TOGGLE ACTION
        // =====================
        $(".toggle-comments").off("click").on("click", function () {
          const targetId = $(this).data("target");
          const section = $("#" + targetId);
          const arrow = $("#arrow-" + targetId.split("-")[1]);

          if (section.hasClass("max-h-0")) {
            section.removeClass("max-h-0").addClass("max-h-48");
            arrow.css("transform", "rotate(180deg)");
          } else {
            section.removeClass("max-h-48").addClass("max-h-0");
            arrow.css("transform", "rotate(0deg)");
          }
        });

      } else {
        tbody.html(`
          <tr>
            <td colspan="4" class="text-center py-4 text-gray-400">No meetings found.</td>
          </tr>
        `);
      }
    }
  });
}







  // ===============================
  // OPEN CLASSWORK MODAL
  // ===============================
  $(document).on("click", ".viewClassworkBtn", function () {
    const room_id = $(this).data("room_id");
    const room_name = $(this).data("room_name");

    $("#classworkModalTitle").text(room_name + " - Classworks");
    fetchAllCreatedWorks(room_id);
    $("#classworkModal").removeClass("hidden");
  });



  // ===============================
  // OPEN MEMBER MODAL
  // ===============================
  $(document).on("click", ".viewMemberBtn", function () {
    const room_id = $(this).data("room_id");
    const room_name = $(this).data("room_name");

    $("#memberModalTitle").text(room_name + " - Members");
    fetchMembers(room_id);
    $("#memberModal").removeClass("hidden");
  });



  // ===============================
  // OPEN MEETING MODAL
  // ===============================
  $(document).on("click", ".viewMeetingBtn", function () {
    const room_id = $(this).data("room_id");
    const room_name = $(this).data("room_name");

    $("#meetingModalTitle").text(room_name + " - Meetings");
    fetchMeetings(room_id);
    $("#meetingModal").removeClass("hidden");
  });



  // ===============================
  // CLOSE MODALS
  // ===============================
  $(document).on("click", "#closeClassworkModal", function () {
    $("#classworkModal").addClass("hidden");
  });

  $(document).on("click", "#closeMemberModal", function () {
    $("#memberModal").addClass("hidden");
  });

  $(document).on("click", "#closeMeetingModal", function () {
    $("#meetingModal").addClass("hidden");
  });

});
