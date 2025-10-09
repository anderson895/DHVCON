// Get the current URL
const urlParams = new URLSearchParams(window.location.search);

// Get the value of "code"
const code = urlParams.get('code');

$(document).ready(function() {
    function fetchRoomsDetails() {
        $.ajax({
            url: `../controller/end-points/controller.php?requestType=getRoomDetails&code=${code}`,
            type: "GET",
            dataType: "json",
            success: function(response) {
                console.log("Room Details:", response);

                // Example: display room info in HTML
                $(".roomName").text(response.data.room_name);
                $(".roomDescription").text(response.data.room_description);
                $(".roomBanner").attr("src", response.room_banner);
            },
            error: function(err) {
                console.error("AJAX error:", err);
            }
        });
    }

    // Initial fetch
    fetchRoomsDetails();
});
