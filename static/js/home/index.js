$(document).ready(function() {





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
                    
                    if (response.data.length === 0) {
                        container.removeClass("grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6")
                                 .addClass("flex flex-col items-center justify-center min-h-[80vh]");

                        container.html(`
                            <div class="text-center animate-fadeIn">
                                <img src="../static/image/no_rooms_banner.png" 
                                     alt="No Rooms Available" 
                                     class="w-[28rem] h-[28rem] object-contain rounded-2xl mb-8">
                                <h2 class="text-3xl font-bold text-white mb-3">No Rooms Available</h2>
                                <p class="text-gray-400 text-lg max-w-lg mx-auto">
                                    Create a new room or join an existing one to start collaborating.
                                </p>
                            </div>
                        `);
                        return;
                    }
                    
                    container.removeClass("flex flex-col items-center justify-center min-h-[80vh]")
                             .addClass("grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6");

                   response.data.forEach(room => {
                    let bannerUrl = room.room_banner 
                        ? "../static/upload/" + room.room_banner 
                        : "../static/image/no_image.jpg";

                    // ✂️ Limit the description length
                    const maxLength = 100;
                    const fullDesc = room.room_description || "";
                    const shortDesc = fullDesc.length > maxLength 
                        ? fullDesc.substring(0, maxLength) + "..." 
                        : fullDesc;

                    const hasLongText = fullDesc.length > maxLength;

                    // Generate card
                    let card = "";

                    if (room.room_creator_user_id == response.user_id) {
                        card = `
                            <div class="bg-white text-black rounded-xl overflow-hidden shadow-md animate-fadeIn relative flex flex-col h-[26rem]">
                                <img src="${bannerUrl}" alt="${room.room_name}" class="w-full h-40 object-cover">
                                
                                <div class="absolute top-2 right-2 flex gap-1">
                                    <button class="edit-room cursor-pointer w-6 h-6 flex items-center justify-center bg-gray-500 rounded-full hover:bg-gray-400 transition"
                                            data-room_id="${room.room_id}" title="Edit Room">
                                        <span class="material-icons text-white text-xs">edit</span>
                                    </button>
                                    <button class="delete-room cursor-pointer w-6 h-6 flex items-center justify-center bg-red-500 rounded-full hover:bg-red-600 transition"
                                            data-room_id="${room.room_id}" title="Delete Room">
                                        <span class="material-icons text-white text-xs">close</span>
                                    </button>
                                </div>

                                <div class="p-4 flex flex-col justify-between flex-grow">
                                    <div>
                                        <h3 class="uppercase font-semibold text-lg mb-2">${room.room_name}</h3>
                                        <div class="desc-wrapper text-gray-700 text-sm">
                                            <span class="desc-text" data-full="${fullDesc}">
                                                ${shortDesc}
                                            </span>
                                            ${hasLongText ? `<span class="text-blue-600 cursor-pointer see-more hover:underline ml-1">See more</span>` : ""}
                                        </div>
                                        <p class="text-gray-700 text-sm mt-1">CODE: ${room.room_code}</p>
                                    </div>
                                    <a href="room?code=${room.room_code}&&room_name=${room.room_name}" 
                                        class="block text-center bg-black text-white font-semibold py-2 rounded-md hover:bg-gray-800 transition">
                                        My Room
                                    </a>
                                </div>
                            </div>
                        `;
                    } else {
                        card = `
                            <div class="bg-[#2b2d31] rounded-xl overflow-hidden shadow-md animate-fadeIn flex flex-col h-[26rem]">
                                <img src="${bannerUrl}" alt="${room.room_name}" class="w-full h-40 object-cover">
                                <div class="p-4 flex flex-col justify-between flex-grow">
                                    <div>
                                        <h3 class="uppercase font-semibold text-lg mb-2">${room.room_name}</h3>
                                        <div class="desc-wrapper text-gray-400 text-sm">
                                            <span class="desc-text" data-full="${fullDesc}">
                                                ${shortDesc}
                                            </span>
                                            ${hasLongText ? `<span class="text-blue-400 cursor-pointer see-more hover:underline ml-1">See more</span>` : ""}
                                        </div>
                                        <p class="text-gray-400 text-sm mt-1">CODE: ${room.room_code}</p>
                                    </div>
                                    <button class="btnJoinRoom cursor-pointer w-full bg-[#5865f2] text-white py-2 rounded-md hover:bg-[#4752c4] transition"
                                            data-code='${room.room_code}'>
                                        Join Room
                                    </button>
                                </div>
                            </div>
                        `;
                    }

                    container.append(card);
                });

                // 🔽 Toggle See More / See Less (preserve text color + scrollable)
                $(document).on("click", ".see-more", function() {
                    const wrapper = $(this).closest(".desc-wrapper");
                    const textBox = wrapper.find(".desc-text");
                    const fullText = textBox.data("full");
                    const shortText = fullText.substring(0, 100) + "...";

                    if ($(this).text() === "See more") {
                        textBox.html(`
                            <div class="max-h-24 overflow-y-auto pr-1 custom-scroll">
                                ${fullText}
                            </div>
                        `);
                        $(this).text("See less");
                    } else {
                        textBox.text(shortText);
                        $(this).text("See more");
                    }
                });




                

                    // ✅ Attach click event for delete buttons after rendering
                 $(".delete-room").click(function() {
                    let room_id = $(this).data("room_id");

                    console.log(room_id);

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "This will permanently delete the room!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel',
                        background: '#2b2d31', // Dark background
                        color: '#fff',          // White text
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
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
                                type: "POST",
                                data: {
                                    requestType: "deleteRoom",
                                    room_id: room_id
                                },
                                success: function(res) {
                                    let response = JSON.parse(res);
                                    if (response.status === 200) {
                                        Swal.fire({
                                            title: 'Deleted!',
                                            text: 'Your room has been deleted.',
                                            icon: 'success',
                                            background: '#2b2d31',
                                            color: '#fff',
                                            confirmButtonColor: '#3085d6'
                                        });
                                        fetchRooms(); // Refresh the room list
                                    } else {
                                        Swal.fire({
                                            title: 'Error!',
                                            text: response.message || 'Failed to delete room.',
                                            icon: 'error',
                                            background: '#2b2d31',
                                            color: '#fff',
                                            confirmButtonColor: '#d33'
                                        });
                                    }
                                },
                                error: function(err) {
                                    Swal.fire({
                                        title: 'Error!',
                                        text: 'AJAX error: Could not delete room.',
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














// ---------------- CREATE ROOM ----------------

// Open create modal
$("#btnCreateRoom").click(() => {
    $("#createRoomForm")[0].reset();
    $("#spinnerCreate").hide();
    $("#createRoomModal").fadeIn(200);
});

// Close create modal
$("#closeCreateModal").click(() => $("#createRoomModal").fadeOut(200));
$("#createRoomModal").click(function(e){ if(e.target === this) $(this).fadeOut(200); });




// Submit create form with validation
$("#createRoomForm").submit(function(e){
    e.preventDefault();

    // Get input values
    let roomName = $("input[name='roomName']").val().trim();
    let roomDescription = $("textarea[name='roomDescription']").val().trim();
    let roomBanner = $("input[name='roomBanner']")[0].files[0];

    // Validate inputs
    if (!roomName) {
        Swal.fire("Warning", "Please enter a room name.", "warning");
        return;
    }
    if (!roomDescription) {
        Swal.fire("Warning", "Please enter a room description.", "warning");
        return;
    }
    if (!roomBanner) {
        Swal.fire("Warning", "Please select a room banner image.", "warning");
        return;
    }

    // All validations passed, submit form
    let formData = new FormData(this);
    formData.append("requestType", "createRoom");

    $("#spinnerCreate").show();

    $.ajax({
        url: "../controller/end-points/controller.php",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(res){
            let response = JSON.parse(res);
            if(response.status === 200){
                Swal.fire("Success", response.message, "success");
                $("#createRoomModal").fadeOut(200);

                // Refresh rooms after 1 second
                setTimeout(function() {
                    location.reload();
                }, 1000);

            } else {
                Swal.fire("Error", response.message || "Failed to create room", "error");
            }
        },
        complete: function(){ 
            $("#spinnerCreate").hide(); 
        }
    });
});


// ---------------- UPDATE ROOM ----------------

// Open update modal
$(document).on("click", ".edit-room", function() {
    let roomId = $(this).data("room_id");
    $("#updateRoomForm").data("room_id", roomId);
    $("#spinnerUpdate").show();
    $("#updateRoomModal").fadeIn(200);

    $.get("../controller/end-points/controller.php", { requestType: "getRoomById", room_id: roomId }, function(response){
        if(response.status === 200){
            let room = response.data;
            $("#updateRoomForm input[name='roomName']").val(room.room_name);
            $("#updateRoomForm textarea[name='roomDescription']").val(room.room_description);
        } else {
            Swal.fire("Error", response.message || "Failed to fetch room data", "error");
            $("#updateRoomModal").fadeOut(200);
        }
    }, "json").always(() => $("#spinnerUpdate").hide());
});

// Close update modal
$("#closeUpdateModal").click(() => $("#updateRoomModal").fadeOut(200));
$("#updateRoomModal").click(function(e){ if(e.target === this) $(this).fadeOut(200); });




// Submit update form
$("#updateRoomForm").submit(function(e){
    e.preventDefault();
    let roomId = $(this).data("room_id");
    let formData = new FormData(this);
    formData.append("requestType", "updateRoom");
    formData.append("room_id", roomId);

    $("#spinnerUpdate").show();

    $.ajax({
        url: "../controller/end-points/controller.php",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(res){
            let response = JSON.parse(res);
            if(response.status === 200){
             Swal.fire("Success", response.message, "success").then(() => {
                location.reload();
            });

            } else {
                Swal.fire("Error", response.message || "Failed to update room", "error");
            }
        },
        complete: function(){ $("#spinnerUpdate").hide(); }
    });
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





