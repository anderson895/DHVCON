<?php 
include "../src/components/home/header.php";
$meetingCode  = $_GET['code']; 

$meeting = $db->check_meeting($meetingCode );
?>

    <main class="flex-1 bg-[#1e1f22] ml-0 md:ml-60 p-4 transition-all duration-300 min-h-screen flex flex-col">

<!-- Header -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center px-4 sm:px-6 py-4 bg-[#1f1f25] rounded-md shadow-lg mb-4 border border-gray-700 gap-3 md:gap-0">
    <!-- Title -->
    <h1 class="text-2xl md:text-xl text-gray-100 font-semibold truncate w-full md:w-auto">Conference Room</h1>

    <!-- Right Controls -->
    <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-4 w-full md:w-auto">
        <!-- Room Code -->
        <div class="text-gray-400 text-sm md:text-base truncate md:mr-4 flex-shrink-0">
            Room Code: <span class="text-gray-100 font-medium"><?= $meetingCode ?></span>
        </div>

        <!-- Buttons Row (scrollable on mobile) -->
        <div class="flex flex-row gap-2 overflow-x-auto md:overflow-visible">
            <!-- Pending Requests Button -->
            <button id="pendingRequestsBtn" class="cursor-pointer relative px-3 py-1 bg-gray-700 hover:bg-gray-600 text-white rounded-md flex items-center gap-1 flex-shrink-0">
                <span class="material-icons text-base">person_add</span>
                <span class="truncate">Requests</span>
                <span id="pendingCount" class="absolute -top-2 -right-2 bg-red-600 text-xs w-5 h-5 flex items-center justify-center rounded-full hidden">0</span>
            </button>

            <!-- View Attendance Button -->
            <button id="viewAttendanceBtn" class="cursor-pointer flex items-center gap-1 px-3 py-1 bg-gray-700 hover:bg-gray-600 text-white rounded-md flex-shrink-0">
                <span class="material-icons text-base">groups</span>
                <span class="truncate">Attendance</span>
            </button>

            <!-- Leave Room Button -->
            <button id="btnLeaveRoom" class="joiner-only cursor-pointer flex items-center gap-1 px-3 py-1 bg-gray-700 hover:bg-gray-600 text-gray-100 rounded-md flex-shrink-0">
                <span class="material-icons text-base">exit_to_app</span>
                <span class="hidden sm:inline truncate">Leave Room</span>
            </button>
        </div>
    </div>
</div>





        <!-- Status -->
        <div id="status" class="w-full p-2 mb-4 bg-gray-200 rounded-md text-gray-800 text-center">
            Ready to join
        </div>

        <!-- Controls: Cam / Mic -->
        <div class="flex gap-4 mb-4">
            <button id="btnToggleCam" class="cursor-pointer flex items-center gap-2 px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-md">
                <span id="iconCam" class="material-icons">videocam</span>
                <span id="textCam">Turn Off Cam</span>
            </button>
            <button id="btnToggleMic" class="cursor-pointer flex items-center gap-2 px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-md">
                <span id="iconMic" class="material-icons">mic</span>
                <span id="textMic">Turn Off Mic</span>
            </button>
        </div>


            <!-- Main Content: Video + Chat -->
        <div class="flex flex-1 gap-4 flex-col-reverse md:flex-row">
            
            <!-- Video Section -->
            <div id="video-container" class="flex-1 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                <!-- Video players will appear here -->
            </div>

                                    <!-- Chat Section -->
                                <!-- Chat Section -->
            <div id="chat-section" class="fixed bottom-0 w-full sm:w-80 right-0 sm:right-4 bg-[#2b2d31] rounded-t-md flex flex-col shadow-lg border border-gray-700 overflow-hidden transition-all duration-300 z-50">

                <!-- Chat Header -->
                <div id="chat-toggle" class="px-4 py-2 border-b border-gray-600 text-gray-100 font-semibold flex justify-between items-center cursor-pointer">
                    <span>Chat</span>
                    <span id="chat-toggle-icon" class="material-icons text-gray-300 hover:text-white">expand_less</span>
                </div>

                <!-- Chat Messages (collapsible) -->
                <div id="chat-messages-wrapper" class="flex flex-col max-h-[60vh] overflow-hidden transition-all duration-300">
                    <div id="chat-messages" class="flex-1 p-2 overflow-y-auto text-gray-200 space-y-2">
                        <!-- Messages appear here -->
                    </div>
                </div>

                <!-- Chat Form (always visible) -->
                <form id="chat-form" class="flex flex-row items-center justify-center p-2 border-t border-gray-600 gap-2 pb-safe">
                    <input type="text" id="chat-input" placeholder="Type a message..." 
                        class="flex-1 px-3 py-2 rounded-md bg-[#1f1f25] text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-md text-white">
                        Send
                    </button>
                </form>
            </div>












 





        </div>


    </main>








<!-- Modal Overlay -->
<div id="joinerModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
  <!-- Modal Content -->
  <div class="bg-white/10 backdrop-blur-md text-gray-100 rounded-lg shadow-xl w-11/12 sm:w-96 p-6 relative border border-gray-700">
      
      <!-- Close Button -->
      <button id="closeModal" class="cursor-pointer absolute top-3 right-3 text-gray-400 hover:text-white">
        <span class="material-icons">close</span>
      </button>

      <!-- Modal Title -->
      <h2 class="text-xl font-semibold mb-4">Request Approvals</h2>

      <!-- Joiner List -->
      <div class="space-y-3 max-h-96 overflow-y-auto">
        <!-- Joiner Card -->
      </div>
  </div>
</div>


<!-- Attendance Modal -->
<div id="attendanceModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
    <div class="bg-[#232428] backdrop-blur-md text-gray-100 rounded-lg shadow-xl w-11/12 sm:w-3/4 md:w-2/3 lg:w-1/2 p-6 relative border border-gray-700">
        
        <!-- Close Button -->
        <button id="closeAttendanceModal" class="cursor-pointer absolute top-3 right-3 text-gray-400 hover:text-white">
            <span class="material-icons">close</span>
        </button>

        <!-- Modal Title -->
        <h2 class="text-2xl font-semibold mb-6">Attendance</h2>

        <!-- Attendance List -->
        <div class="space-y-4 max-h-[70vh] overflow-y-auto" id="attendanceList">
          
        </div>
    </div>
</div>




<script>






$(document).ready(function() {
    const controllerUrl = "../controller/end-points/controller.php";
    const meeting_id = "<?= $meeting[0]['meeting_id'] ?>"; 

// Function to poll server for approval status
function checkMemberStatus() {
    const interval = setInterval(function() {
        $.ajax({
            url: "../controller/end-points/controller.php",
            type: "GET",
            data: {
                requestType: "checkMemberStatus",
                meeting_id: meeting_id,
            },
            dataType: "json",
            success: function(res) {
                console.log(res);

                if (res.status === 200) {
                    const status = res.data.join_request_status;

                    if (status === "Not member") {
                        clearInterval(interval); // stop polling
                        // Redirect to kick page
                        window.location.href = "kick.php?code=<?=$meetingCode?>";
                    } 
                    // Optional: handle pending/approved/rejected if needed
                    else if (status === "creator") {
                        console.log("You are the meeting creator.");
                    } else {
                        console.log("Join request status:", status);
                    }
                } else {
                    console.warn("Server returned status:", res.status);
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
                clearInterval(interval);
                Swal.fire({
                    icon: "error",
                    title: "Approval Request Error",
                    text: "Something went wrong while checking approval."
                });
            }
        });
    }, 3000); // Poll every 3 seconds
}

checkMemberStatus();









    // Helper function to format timestamp nicely
    function formatDateTime(datetimeStr) {
        const date = new Date(datetimeStr);
        if (isNaN(date)) return datetimeStr; 
        const options = { 
            year: 'numeric', month: 'short', day: 'numeric',
            hour: '2-digit', minute: '2-digit', hour12: true 
        };
        return date.toLocaleString('en-US', options); 
    }

    // Function to fetch pending count
    function fetchPendingCount() {
        $.get(controllerUrl, { requestType: "checkPendingRequests", meeting_id: meeting_id }, function(res) {
            if (res.status === 200 && res.data.pending_count > 0) {
                $("#pendingCount").text(res.data.pending_count).removeClass("hidden");
            } else {
                $("#pendingCount").addClass("hidden");
            }
        }, "json");
    }

    // Poll every 5 seconds
    setInterval(fetchPendingCount, 5000);
    fetchPendingCount(); // initial call

    // Show modal when clicking notification button
    $("#pendingRequestsBtn").on("click", function() {
        $.get(controllerUrl, { requestType: "getPendingRequestsDetails", meeting_id: meeting_id }, function(res) {
            if (res.status === 200) {
                const container = $("#joinerModal .space-y-3");
                container.empty(); // clear existing
                if (res.data.length === 0) {
                    container.append('<p class="text-gray-300 text-center">No pending requests</p>');
                } else {
                    res.data.forEach(user => {
                        container.append(`
                            <div class="flex justify-between items-center bg-white/10 backdrop-blur-md p-3 rounded-md border border-gray-700 shadow hover:bg-white/20 transition" data-jr-id="${user.jr_id}">
                                <div>
                                    <p class="font-semibold">${user.user_fullname}</p>
                                    <p class="text-xs text-gray-400">Requested: ${formatDateTime(user.jr_requested_at)}</p>
                                </div>
                                <div class="flex flex-col gap-2 items-end">
                                    <span class="px-2 py-1 rounded-full bg-yellow-600 text-xs">Pending</span>
                                    <div class="flex gap-1 mt-1">
                                        <button class="approveBtn cursor-pointer px-2 py-1 bg-green-600 hover:bg-green-700 rounded-md text-white">Approve</button>
                                        <button class="rejectBtn cursor-pointer px-2 py-1 bg-red-600 hover:bg-red-700 rounded-md text-white">Reject</button>
                                    </div>
                                </div>
                            </div>
                        `);
                    });
                }
                $("#joinerModal").removeClass("hidden");
            }
        }, "json");
    });

    // Close modal
    $("#closeModal").on("click", function() {
        $("#joinerModal").addClass("hidden");
    });

    // Handle Approve/Reject buttons
    $(document).on("click", ".approveBtn, .rejectBtn", function() {
        const jrCard = $(this).closest("[data-jr-id]");
        const jr_id = jrCard.data("jr-id");
        const action = $(this).hasClass("approveBtn") ? "approved" : "rejected";

        $.post(controllerUrl, { requestType: "updateJoinRequest", jr_id: jr_id, action: action }, function(res) {
            if (res.status === 200) {
                jrCard.remove(); // remove the request from modal
                fetchPendingCount(); // update notification count
            } else {
                alert(res.message);
            }
        }, "json");
    });




    // View Attendance
    $("#viewAttendanceBtn").on("click", function() {
        $.get(controllerUrl, { requestType: "getApprovedUsers", meeting_id: meeting_id }, function(res) {
            if (res.status === 200) {
                const container = $("#attendanceList");
                container.empty();

                if (res.data.length === 0) {
                    container.append('<p class="text-gray-300 text-center">No approved participants yet</p>');
                } else {
                    res.data.forEach(user => {
                        container.append(`
                            <div class="flex justify-between items-center bg-gray backdrop-blur-md p-4 rounded-md border border-gray-700 shadow transition" data-user-id="${user.jr_user_id}">
                                <div>
                                    <p class="font-semibold">${user.user_fullname}</p>
                                    <p class="text-xs text-gray-400">${user.user_email}</p>
                                    <p class="text-xs text-gray-400">Joined: ${formatDateTime(user.jr_requested_at)}</p>
                                </div>
                                <div class="flex flex-col gap-2 items-end">
                                    <button class="removeMemberBtn cursor-pointer px-3 py-1 bg-red-600 hover:bg-red-700 rounded-md text-white text-sm">
                                        Remove Member
                                    </button>
                                </div>
                            </div>
                        `);
                    });
                }

                $("#attendanceModal").removeClass("hidden");
            }
        }, "json");
    });

    // Close Attendance Modal
    $("#closeAttendanceModal").on("click", function() {
        $("#attendanceModal").addClass("hidden");
    });

        // Handle Remove Member button
$(document).on("click", ".removeMemberBtn", function() {
    const userCard = $(this).closest("[data-user-id]");
    const user_id = userCard.data("user-id");

    Swal.fire({
        title: "Are you sure?",
        text: "Do you really want to remove this member?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, remove",
        cancelButtonText: "Cancel",
        reverseButtons: true,
        background: "#1e1f22", // dark background
        color: "#ffffff",       // white text
        confirmButtonColor: "#d33", // red confirm button
        cancelButtonColor: "#6c757d", // gray cancel button
        customClass: {
            title: 'swal-title-dark',
            content: 'swal-content-dark',
            popup: 'swal-popup-dark'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Send request to remove member
            $.post(controllerUrl, { 
                requestType: "removeUserFromMeeting", 
                meeting_id: meeting_id, 
                user_id: user_id 
            }, function(res) {
                if (res.status === 200) {
                    userCard.remove(); // remove from modal
                    Swal.fire({
                        icon: "success",
                        title: "Removed",
                        text: "Member has been removed successfully.",
                        timer: 1500,
                        showConfirmButton: false,
                        background: "#1e1f22",
                        color: "#ffffff"
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: res.message,
                        background: "#1e1f22",
                        color: "#ffffff"
                    });
                }
            }, "json");
        }
    });
});





    // Close Attendance Modal
    $("#closeAttendanceModal").on("click", function() {
        $("#attendanceModal").addClass("hidden");
    });

});
</script>







    

   <script>
const chatToggle = document.getElementById('chat-toggle');
const chatIcon = document.getElementById('chat-toggle-icon');
const chatMessagesWrapper = document.getElementById('chat-messages-wrapper');
const chatInput = document.getElementById('chat-input');
const chatForm = document.getElementById('chat-form');

let isCollapsed = window.innerWidth < 640;
let inputFocused = false;
let submitting = false;

// Initialize chat state
function initChat() {
    if (isCollapsed && !inputFocused) {
        chatMessagesWrapper.style.maxHeight = '0';
        chatIcon.textContent = 'expand_more';
    } else {
        chatMessagesWrapper.style.maxHeight = '60vh';
        chatIcon.textContent = 'expand_less';
    }
}
initChat();

// Toggle messages container manually
chatToggle.addEventListener('click', () => {
    if (chatMessagesWrapper.style.maxHeight === '0px' || chatMessagesWrapper.style.maxHeight === '0') {
        chatMessagesWrapper.style.maxHeight = '60vh';
        chatIcon.textContent = 'expand_less';
    } else {
        chatMessagesWrapper.style.maxHeight = '0';
        chatIcon.textContent = 'expand_more';
    }
});

// Keep chat expanded while typing
chatInput.addEventListener('focus', () => {
    inputFocused = true;
    chatMessagesWrapper.style.maxHeight = '60vh';
    chatIcon.textContent = 'expand_less';
});

chatInput.addEventListener('blur', () => {
    if (!submitting) {  // only collapse if not submitting
        inputFocused = false;
        if (isCollapsed) {
            chatMessagesWrapper.style.maxHeight = '0';
            chatIcon.textContent = 'expand_more';
        }
    }
});

// Prevent collapse when submitting
chatForm.addEventListener('submit', (e) => {
    submitting = true;
    inputFocused = true;  // keep expanded
    chatMessagesWrapper.style.maxHeight = '60vh';
    chatIcon.textContent = 'expand_less';
    
    // allow some delay before resetting
    setTimeout(() => {
        submitting = false;
    }, 100); // adjust if needed
});

// Update on resize
window.addEventListener('resize', () => {
    isCollapsed = window.innerWidth < 640;
    initChat();
});
</script>






<script src="https://download.agora.io/sdk/release/AgoraRTC_N.js"></script>


<?php 
$user = "(" . $On_Session[0]['user_id'] . ") " . $On_Session[0]['user_fullname'];
?>

<script>
const APP_ID = "b2e962fe791e4b23a34dee48010a733f";
let client = AgoraRTC.createClient({ mode: 'rtc', codec: 'vp8' });
let localTracks = { videoTrack: null, audioTrack: null };
const videoContainer = document.getElementById('video-container');
const statusBox = document.getElementById('status');
const meetingCode  = "<?= $meetingCode ?>";
const userName = `<?= $user ?>`;

function setStatus(message, type = '') {
    statusBox.textContent = message;
    if(type === "success") 
        statusBox.className = "w-full p-2 mb-4 bg-green-100 rounded-md text-green-700 text-center";
    else if(type === "error") 
        statusBox.className = "w-full p-2 mb-4 bg-red-100 rounded-md text-red-700 text-center";
    else 
        statusBox.className = "w-full p-2 mb-4 bg-gray-200 rounded-md text-gray-800 text-center";
}

async function joinMeeting(code) {
    try {
        setStatus("Requesting camera & microphone permission...");
        const stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
        stream.getTracks().forEach(track => track.stop());

        // ✅ Handle remote users
        client.on('user-published', async (user, mediaType) => {
        await client.subscribe(user, mediaType);

        let wrapper = document.getElementById('wrapper-' + user.uid);
        if (!wrapper) {
            wrapper = document.createElement('div');
            wrapper.id = 'wrapper-' + user.uid;
            wrapper.className = "relative aspect-video rounded-md overflow-hidden shadow-md bg-black m-2";

            // Video div
            const videoDiv = document.createElement('div');
            videoDiv.id = 'player-' + user.uid;
            videoDiv.className = "w-full h-full";
            wrapper.appendChild(videoDiv);

            // Default icon
            const defaultIcon = document.createElement('div');
            defaultIcon.id = 'default-icon-' + user.uid;
            defaultIcon.className = "absolute inset-0 flex items-center justify-center bg-black";
            const iconSpan = document.createElement('span');
            iconSpan.className = 'material-icons';
            iconSpan.textContent = 'account_circle';
            iconSpan.style.fontSize = '8vw';
            iconSpan.style.color = '#9ca3af';
            defaultIcon.appendChild(iconSpan);
            wrapper.appendChild(defaultIcon);

            // Username label
            const nameTag = document.createElement('div');
            nameTag.innerText = user.uid || `User ${user.uid}`;
            nameTag.className = "absolute bottom-0 left-0 w-full text-center text-white bg-black/60 text-sm py-1";
            wrapper.appendChild(nameTag);

            videoContainer.appendChild(wrapper);
        }

        if (mediaType === 'video') {
            user.videoTrack.play('player-' + user.uid);
            document.getElementById('player-' + user.uid).style.display = 'block';
            document.getElementById('default-icon-' + user.uid).classList.add('hidden'); // hide default
        }

        if (mediaType === 'audio') {
            user.audioTrack.play();
        }
    });


        // ✅ Handle when user disables cam
        client.on('user-unpublished', (user, mediaType) => {
            if (mediaType === 'video') {
                // Don’t remove — just show icon
                const player = document.getElementById('player-' + user.uid);
                const icon = document.getElementById('default-icon-' + user.uid);
                if (player && icon) {
                    player.style.display = 'none';
                    icon.classList.remove('hidden');
                    icon.classList.add('flex');
                }
            }
            if (mediaType === 'audio') {
                // Optional: mute their audio
            }
        });

        // Join
        setStatus(`Joining room: ${code} as ${userName}...`);
        await client.join(APP_ID, code, null, userName);

        // Local tracks
        localTracks.audioTrack = await AgoraRTC.createMicrophoneAudioTrack();
        localTracks.videoTrack = await AgoraRTC.createCameraVideoTrack();

        // Local wrapper
        const localWrapper = document.createElement('div');
        localWrapper.id = 'wrapper-local';
        localWrapper.className = "relative aspect-video rounded-md overflow-hidden shadow-md bg-black m-2";

        // Video div
        const localVideoDiv = document.createElement('div');
        localVideoDiv.id = 'local-player';
        localVideoDiv.className = "w-full h-full";
        localWrapper.appendChild(localVideoDiv);

        // Default user icon (hidden initially)
        const defaultIcon = document.createElement('div');
        defaultIcon.id = 'default-user-icon';
        defaultIcon.className = "absolute inset-0 hidden items-center justify-center bg-black";
        const iconSpan = document.createElement('span');
        iconSpan.className = 'material-icons';
        iconSpan.textContent = 'account_circle';
        iconSpan.style.fontSize = '8vw';
        iconSpan.style.color = '#9ca3af';
        defaultIcon.appendChild(iconSpan);
        localWrapper.appendChild(defaultIcon);

        // Username label (always visible)
        const localName = document.createElement('div');
        localName.innerText = `${userName} (You)`;
        localName.className = "username-label absolute bottom-0 left-0 w-full text-center text-white bg-black/60 text-sm py-1 z-10";
        localWrapper.appendChild(localName);

        videoContainer.appendChild(localWrapper);

        // Play and publish
        await localTracks.videoTrack.play('local-player');
        await client.publish(Object.values(localTracks));

        setStatus(`✅ Joined meeting: ${code} as ${userName}`, "success");

    } catch (err) {
        console.error(err);
        setStatus("❌ Failed to join meeting: " + err.message, "error");
    }
}


// ✅ Toggle Cam (local only)
document.getElementById('btnToggleCam').addEventListener('click', async () => {
    if (!localTracks.videoTrack) return;

    const icon = document.getElementById('iconCam');
    const text = document.getElementById('textCam');
    const defaultIcon = document.getElementById('default-user-icon');
    const localPlayer = document.getElementById('local-player');

    if (localTracks.videoTrack.enabled) {
        await localTracks.videoTrack.setEnabled(false);
        icon.textContent = 'videocam_off';
        text.textContent = 'Turn On Cam';

        localPlayer.style.display = 'none';
        defaultIcon.classList.remove('hidden');
        defaultIcon.classList.add('flex');
    } else {
        await localTracks.videoTrack.setEnabled(true);
        icon.textContent = 'videocam';
        text.textContent = 'Turn Off Cam';

        localPlayer.style.display = 'block';
        defaultIcon.classList.add('hidden');
        defaultIcon.classList.remove('flex');
    }
});


// ✅ Toggle Mic
document.getElementById('btnToggleMic').addEventListener('click', async () => {
    if (!localTracks.audioTrack) return;

    const icon = document.getElementById('iconMic');
    const text = document.getElementById('textMic');

    if (localTracks.audioTrack.enabled) {
        localTracks.audioTrack.setEnabled(false);
        icon.textContent = 'mic_off';
        text.textContent = 'Turn On Mic';
    } else {
        localTracks.audioTrack.setEnabled(true);
        icon.textContent = 'mic';
        text.textContent = 'Turn Off Mic';
    }
});


// ✅ Auto join
joinMeeting(meetingCode);


// ✅ Leave room
$(document).ready(function() {
    $('#btnLeaveRoom').on('click', function(e) {
        e.preventDefault();

        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to leave the room!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, leave',
            cancelButtonText: 'Cancel',
            background: '#2b2d31',
            color: '#fff',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '../home/';
            }
        });
    });
});
</script>






<?php 
include "../src/components/home/footer.php";
?>
<script src="../static/js/home/conference_room.js"></script>