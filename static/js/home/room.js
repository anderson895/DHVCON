// Get the current URL
const urlParams = new URLSearchParams(window.location.search);
const code = urlParams.get('code');

// Declare global variable for room_id
let room_id = null;

$(document).ready(function() {

    function fetchRoomsDetails() {
        $.ajax({
            url: `../controller/end-points/controller.php?requestType=getRoomDetails&code=${code}`,
            type: "GET",
            dataType: "json",
            success: function(response) {
                console.log("Room Details:", response);

                room_id = response.data.room_id;
                
                $(".roomName").text(response.data.room_name);
                $(".roomDescription").text(response.data.room_description);
                $(".roomBanner").attr("src", response.data.room_banner);
            },
            error: function(err) {
                console.error("AJAX error:", err);
            }
        });
    }

    // Initial fetch
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
        },
        error: function (xhr, status, error) {
            $('#spinner').hide();
            $('#btnCreateWork').prop('disabled', false);
            console.log(xhr.responseText);
            alertify.error("Something went wrong!");
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
