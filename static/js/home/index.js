$(document).ready(function() {
  // Open modal with fadeIn
  $("#btnCreateRoom").click(function() {
    $("#createRoomModal").fadeIn(200); // 200ms fade-in
  });

  // Close modal with fadeOut
  $("#closeModal").click(function() {
    $("#createRoomModal").fadeOut(200); // 200ms fade-out
  });

  // Optional: close modal when clicking outside the modal content
  $("#createRoomModal").click(function(e) {
    if (e.target === this) {
      $(this).fadeOut(200);
    }
  });

  // Open Join Room modal
    $(document).on("click", ".btnJoinRoom", function() {

        let code = $(this).data('code');
        $("#roomCode").val(code);

    $("#joinRoomModal").fadeIn(200);
    });


  // Close modal
  $("#closeJoinModal").click(function() {
    $("#joinRoomModal").fadeOut(200);
  });

  // Close when clicking outside the modal content
  $("#joinRoomModal").click(function(e) {
    if (e.target === this) {
      $(this).fadeOut(200);
    }
  });
});





  $("#createRoomForm").submit(function (e) { 
    e.preventDefault();

    const roomBanner = $("#roomBanner").val();
    if (!roomBanner) {
        alertify.error("Please upload a room banner before submitting.");
        return; 
    }

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
                alertify.success('Created Successfully');
                setTimeout(function () {
                    location.reload();
                }, 1000);
            } else {
                $('#spinner').hide();
                $('#btnCreateRoom').prop('disabled', false);
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
            type: "GET",
            dataType: "json",
            success: function(response) {
                if (response.status === 200) { 
                    let container = $(".room-list");
                    container.empty(); 

                    response.data.forEach(room => {
                        // Use room_banner as full path
                        let bannerUrl = room.room_banner 
                            ? "../static/upload/" + room.room_banner 
                            : "../static/image/no_image.jpg";

                        // Declare card variable outside the if/else
                        let card = "";

                        if (room.room_creator_user_id == response.user_id) {
                            card = `
                               <div class="bg-white text-black rounded-xl overflow-hidden shadow-md">
                                    <img src="${bannerUrl}" alt="${room.room_name}" class="w-full h-40 object-cover">
                                    <div class="p-4 space-y-3">
                                        <h3 class="uppercase font-semibold text-lg flex items-center gap-2">
                                        ${room.room_name}
                                        </h3>
                                        <p class="text-gray-700 text-sm">${room.room_description}</p>
                                        <p class="text-gray-700 text-sm">CODE: ${room.room_code}</p>

                                        <a href="room.php?code=${room.room_code}" 
                                        class="block text-center bg-black text-white font-semibold py-2 rounded-md hover:bg-gray-800 transition">
                                        My Room
                                        </a>
                                    </div>
                                </div>


                            `;
                        } else {
                            card = `
                                <div class="bg-[#2b2d31] rounded-xl overflow-hidden shadow-md">
                                    <img src="${bannerUrl}" alt="${room.room_name}" class="w-full h-40 object-cover">
                                    <div class="p-4 space-y-3">
                                        <h3 class="uppercase font-semibold text-lg flex items-center gap-2">
                                            ${room.room_name}
                                        </h3>
                                        <p class="text-gray-400 text-sm">${room.room_description}</p>
                                        <p class="text-white-700 text-sm">CODE: ${room.room_code}</p>
                                        <button class="btnJoinRoom cursor-pointer w-full bg-[#5865f2] text-white py-2 rounded-md hover:bg-[#4752c4] transition"
                                        data-code='${room.room_code}'
                                        >
                                            Join Room
                                        </button>
                                    </div>
                                </div>
                            `;
                        }

                        container.append(card);
                    });
                } else {
                    console.error("Failed to fetch rooms");
                }
            },
            error: function(err) {
                console.error("AJAX error:", err);
            }
        });
    }

    // Initial fetch
    fetchRooms();
});







  $("#joinRoomForm").submit(function (e) { 
    e.preventDefault();

    const roomCode = $("#roomCode").val();
    if (!roomCode) {
        alertify.error("Enter Room Code First");
        return; 
    }

    $('#joinSpinner').show();
    $('#btnCreateRoom').prop('disabled', true);

    // Use FormData to handle file uploads
    var formData = new FormData(this);
    formData.append('requestType', 'joinRoom');

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
                $('#joinSpinner').hide();
                $('#btnCreateRoom').prop('disabled', false);
                alertify.error(response.message);
            }
        }
    });
});




