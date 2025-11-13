<?php 
include "../src/components/home/header.php";
$meetingCode = $_GET['code']; 
$meeting = $db->check_meeting($meetingCode);
?>

<main class="flex-1 bg-[#1e1f22] min-h-screen flex flex-col items-center justify-center p-6 transition-all duration-300 ml-0 md:ml-60">

    <!-- Centered container for meeting info -->
    <div class="w-full max-w-xl bg-[#2a2b2f] rounded-xl shadow-lg p-6 flex flex-col items-center gap-4">
        <h1 class="text-white text-2xl font-semibold text-center">Joining Meeting</h1>
        <p id="statusMessage" class="text-gray-400 text-center">Sending join request...</p>
        <!-- Loading spinner -->
        <div class="loader ease-linear rounded-full border-8 border-t-8 border-gray-700 h-16 w-16"></div>
    </div>

</main>

<?php 
include "../src/components/home/footer.php";
?>

<script>
var meetingCode = "<?= $meetingCode  ?>";
var meetingId = "<?= $meeting[0]['meeting_id'] ?>";

// Function to send initial join request
function requestToJoin() {
    $.ajax({
        url: "../controller/end-points/controller.php",
        type: "POST",
        data: {
            requestType: "requestToJoin",
            meeting_id: meetingId,
        },
        dataType: "json",
        success: function(res) {
            if (res.status === 200 || res.status === 409) {
                // Show initial waiting message
                document.getElementById("statusMessage").textContent = "Waiting for approval...";
                // Start polling for approval
                checkApproval();
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Failed to Log",
                    text: "Unable to record your attendance."
                });
            }
        },
        error: function(xhr, status, error) {
            console.error(error);
            Swal.fire({
                icon: "error",
                title: "Request Error",
                text: "Something went wrong while recording the log."
            });
        }
    });
}



// Function to poll server for approval status
function checkApproval() {
    var interval = setInterval(function() {
        $.ajax({
            url: "../controller/end-points/controller.php",
            type: "GET",
            data: {
                requestType: "checkMemberStatus",
                meeting_id: meetingId,
            },
            dataType: "json",
            success: function(res) {
                if (res.status === 200 && res.data && res.data.join_request_status) {
                    const status = res.data.join_request_status;

                    console.log("Current join request status:", status);

                    switch (status) {
                        case "approved":
                            clearInterval(interval);
                            document.getElementById("statusMessage").textContent = "Approved! Redirecting...";
                            // window.open("conference_room?code=" + meetingId, "_blank");
                            window.location.href = "conference_room?code=" + meetingCode;
                            break;

                        case "rejected":
                            clearInterval(interval);
                            document.getElementById("statusMessage").textContent = "Your join request was rejected.";
                            Swal.fire({
                                icon: "error",
                                title: "Request Rejected",
                                text: "You cannot join this meeting."
                            });
                            break;

                        case "pending":
                        default:
                            // Still waiting
                            document.getElementById("statusMessage").textContent = "Waiting for approval...";
                            break;
                    }
                } 
            }
        });
    }, 3000); // Poll every 3 seconds
}

// Automatically request to join when page loads
requestToJoin();
</script>

<style>
/* Simple spinning loader */
.loader {
  border-top-color: #4ade80; /* Tailwind green-400 */
  animation: spin 1s linear infinite;
}
@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>
