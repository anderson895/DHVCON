$(document).ready(function() {
    // Open modal
    $("button:contains('Create Room')").click(function() {
      $("#createRoomModal").removeClass("hidden");
    });

    // Close modal
    $("#closeModal").click(function() {
      $("#createRoomModal").addClass("hidden");
    });

    // Optional: close modal when clicking outside the modal content
    $("#createRoomModal").click(function(e) {
      if (e.target === this) {
        $(this).addClass("hidden");
      }
    });

    // Handle form submission
    $("#createRoomForm").submit(function(e) {
      e.preventDefault();
      $("#createRoomModal").addClass("hidden");
    });
  });


  $("#createRoomForm").submit(function (e) { 
    e.preventDefault();

    $('#spinner').show();
    $('#btnCreateRoom').prop('disabled', true);

    // Use FormData to handle file uploads
    var formData = new FormData(this);
    formData.append('requestType', 'createRoom');

    $.ajax({
        type: "POST",
        url: "../controller/end-points/controller.php",
        data: formData,
        processData: false, // important for FormData
        contentType: false, // important for FormData
        dataType: 'json',
        success: function (response) {
            console.log(response);

            if (response.status === "success") {
                alertify.success('Created Successful');
                
                setTimeout(function () {
                    location.reload();
                }, 1000);

            } else {
                $('#spinner').hide();
                $('#btnCreateRoom').prop('disabled', false);
                console.log(response); 
                alertify.error(response.message);
            }
        },
        error: function(xhr, status, error) {
            $('#spinner').hide();
            $('#btnCreateRoom').prop('disabled', false);
            console.log(xhr.responseText);
            alertify.error("Something went wrong!");
        }
    });
});






$(document).ready(function() {
    function fetchRooms() {
        $.ajax({
            url: "../controller/end-points/controller.php?requestType=getAllRooms",
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 200) {  // ✅ changed here
                    let container = $('.grid.grid-cols-1'); // your grid container
                    container.empty(); // clear any existing cards

                    response.data.forEach(room => {
                        // Use room_banner as full path
                        let bannerUrl = room.room_banner 
                            ? '../static/upload/' + room.room_banner 
                            : 'https://via.placeholder.com/600x240?text=No+Banner';

                        let card = `
                            <div class="bg-[#2b2d31] rounded-xl overflow-hidden shadow-md">
                                <img src="${bannerUrl}" alt="${room.room_name}" class="w-full h-40 object-cover">
                                <div class="p-4 space-y-3">
                                    <h3 class="uppercase font-semibold text-lg flex items-center gap-2">
                                        ${room.room_name}
                                    </h3>
                                    <p class="text-gray-400 text-sm">${room.room_description}</p>
                                    <button class="cursor-pointer w-full bg-[#5865f2] text-white py-2 rounded-md hover:bg-[#4752c4] transition">
                                        Join Room
                                    </button>
                                </div>
                            </div>
                        `;
                        container.append(card);
                    });
                } else {
                    console.error('Failed to fetch rooms');
                }
            },
            error: function(err) {
                console.error('AJAX error:', err);
            }
        });
    }

    // Initial fetch
    fetchRooms();
});
