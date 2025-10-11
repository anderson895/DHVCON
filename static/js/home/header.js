$(document).ready(function() {

  function getRoomNameFromURL() {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get('room_name'); 
  }

  function markActiveRoom() {
    const roomName = getRoomNameFromURL();

    // Remove previous active styles from rooms
    $("#joinedRooms a, #createdRooms a").removeClass("bg-[#56585d] font-bold");

    // Highlight the room if room_name exists in URL
    if (roomName) {
      $(`#joinedRooms a span:contains("${roomName}"), #createdRooms a span:contains("${roomName}")`).each(function() {
        if ($(this).text() === roomName) {
          $(this).closest('a').addClass("bg-[#56585d] font-bold");
        }
      });
    } 
    // Otherwise, highlight the "Rooms" link if we're on /home/
    else {
      const path = window.location.pathname;
      if (path.endsWith("/home/")) {
        $('a[href="../home/"]').addClass('bg-gray-700 text-white font-semibold');
      }
    }
  }

  // Load rooms the user has joined
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
        markActiveRoom(); // Call after rendering
      },
      error: function() {
        $("#joinedRooms").html(`<p class="text-red-500 px-3 text-sm">Error fetching rooms.</p>`);
      }
    });
  }

  // Load rooms the user has created
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
        markActiveRoom(); // Call after rendering
      },
      error: function() {
        $("#createdRooms").html(`<p class="text-red-500 px-3 text-sm">Error fetching rooms.</p>`);
      }
    });
  }

  // Load both on page load
  load_getJoinedRooms();
  load_getCreatedRooms();

});


