$(document).ready(function() {

  function loadRooms() {
    $.ajax({
      url: "../controller/end-points/controller.php?requestType=getJoinedRooms",
      type: "GET",
      dataType: "json",
      success: function(response) {
        let output = "";

        // ✅ Check if the response structure is valid
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

  // Load rooms on page load
  loadRooms();

});






