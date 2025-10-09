$(document).ready(function() {

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
              <a href="room.php?code=${room.room_code}"
                 class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-[#3c3f44] transition">
                <span>${room.room_name}</span>
              </a>`;
          });
        } else if (response.status === 200 && response.data.length === 0) {
          output = `<p class="text-gray-500 px-3 text-sm">No joined rooms yet.</p>`;
        } else {
          output = `<p class="text-red-500 px-3 text-sm">${response.message || "Failed to load rooms."}</p>`;
        }

        $("#joinedRooms").html(output);
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
              <a href="room.php?code=${room.room_code}"
                 class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-[#3c3f44] transition font-semibold text-yellow-400">
                <span>${room.room_name}</span>
              </a>`;
          });
        } else if (response.status === 200 && response.data.length === 0) {
          output = `<p class="text-gray-500 px-3 text-sm">No created rooms yet.</p>`;
        } else {
          output = `<p class="text-red-500 px-3 text-sm">${response.message || "Failed to load rooms."}</p>`;
        }

        $("#createdRooms").html(output);
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
