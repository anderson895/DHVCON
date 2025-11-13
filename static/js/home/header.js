$(document).ready(function() {

  // --- Helper: Get room_name from URL ---
  function getRoomNameFromURL() {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get('room_name'); 
  }

  // --- Highlight active room or Home link & open toggle if needed ---
  function markActiveRoom() {
    const roomName = getRoomNameFromURL();

    // Remove previous active styles
    $("#joinedRooms a, #createdRooms a").removeClass("bg-[#56585d] font-bold");

    if (roomName) {
      // Highlight matching room
      $(`#joinedRooms a span:contains("${roomName}"), #createdRooms a span:contains("${roomName}")`).each(function() {
        if ($(this).text() === roomName) {
          $(this).closest('a').addClass("bg-[#56585d] font-bold");

          // Auto expand the section if active room exists
          if ($(this).closest('#joinedRooms').length) {
            $("#joinedRooms").show();
            $("#toggleJoined span").addClass('rotate-180'); // rotate arrow
          }
          if ($(this).closest('#createdRooms').length) {
            $("#createdRooms").show();
            $("#toggleCreated span").addClass('rotate-180'); // rotate arrow
          }

          // Scroll active room into view
          $(this).closest('a')[0].scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
      });
    } else {
      const path = window.location.pathname;
      if (path.endsWith("/home/")) {
        $('a[href="../home/"]').addClass('bg-gray-700 text-white font-semibold');
      }
    }
  }

  // --- Load Joined Rooms ---
  function load_getJoinedRooms() {
    $.ajax({
      url: "../controller/end-points/controller.php?requestType=getJoinedRooms",
      type: "GET",
      dataType: "json",
      success: function(response) {
        let output = "";

        if (response.status === 200 && response.data.length > 0) {
          response.data.forEach(function(room) {
            output += `
              <a href="room?code=${room.room_code}&&room_name=${room.room_name}"
                 class="uppercase flex items-center gap-3 px-3 py-2 rounded-md hover:bg-[#3c3f44] transition">
                <span>${room.room_name}</span>
              </a>`;
          });
        } else if (response.status === 200 && response.data.length === 0) {
          output = `<p class="text-gray-500 px-3 text-sm">No joined rooms yet.</p>`;
        } else {
          output = `<p class="text-red-500 px-3 text-sm">${response.message || "Failed to load rooms."}</p>`;
        }

        $("#joinedRooms").html(output);
        markActiveRoom(); // highlight & auto-expand active
      },
      error: function() {
        $("#joinedRooms").html(`<p class="text-red-500 px-3 text-sm">Error fetching rooms.</p>`);
      }
    });
  }

  // --- Load Created Rooms ---
  function load_getCreatedRooms() {
    $.ajax({
      url: "../controller/end-points/controller.php?requestType=getCreatedRooms",
      type: "GET",
      dataType: "json",
      success: function(response) {
        let output = "";

        if (response.status === 200 && response.data.length > 0) {
          response.data.forEach(function(room) {
            output += `
              <a href="room?code=${room.room_code}&&room_name=${room.room_name}"
                 class="uppercase flex items-center gap-3 px-3 py-2 rounded-md hover:bg-[#3c3f44] transition font-semibold text-yellow-400">
                <span>${room.room_name}</span>
              </a>`;
          });
        } else if (response.status === 200 && response.data.length === 0) {
          output = `<p class="text-gray-500 px-3 text-sm">No created rooms yet.</p>`;
        } else {
          output = `<p class="text-red-500 px-3 text-sm">${response.message || "Failed to load rooms."}</p>`;
        }

        $("#createdRooms").html(output);
        markActiveRoom(); // highlight & auto-expand active
      },
      error: function() {
        $("#createdRooms").html(`<p class="text-red-500 px-3 text-sm">Error fetching rooms.</p>`);
      }
    });
  }

  // --- Toggle functionality ---
  $("#toggleCreated").click(function() {
    $("#createdRooms").slideToggle(200);
    $(this).find('span').toggleClass('rotate-180');
  });

  $("#toggleJoined").click(function() {
    $("#joinedRooms").slideToggle(200);
    $(this).find('span').toggleClass('rotate-180');
  });

  // --- Initial load ---
  load_getJoinedRooms();
  load_getCreatedRooms();

});
