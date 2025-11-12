<?php 
include "../src/components/home/header.php";
$meetingCode  = $_GET['code']; 
$meeting = $db->check_meeting($meetingCode);
$authorization = ($meeting[0]['meeting_creator_user_id'] != $On_Session[0]['user_id']) ? "hidden" : "";
$profile_pict=$On_Session[0]['user_profile_pict'];
$user_id = $On_Session[0]['user_id'];
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
                <button id="pendingRequestsBtn" <?=$authorization; ?> class="cursor-pointer relative px-3 py-1 bg-gray-700 hover:bg-gray-600 text-white rounded-md flex items-center gap-1 flex-shrink-0">
                    <span class="material-icons text-base">person_add</span>
                    <span class="truncate">Requests</span>
                    <span id="pendingCount" class="absolute -top-2 -right-2 bg-red-600 text-xs w-5 h-5 flex items-center justify-center rounded-full hidden">0</span>
                </button>
                <button id="viewAttendanceBtn" <?=$authorization; ?> class="cursor-pointer flex items-center gap-1 px-3 py-1 bg-gray-700 hover:bg-gray-600 text-white rounded-md flex-shrink-0">
                    <span class="material-icons text-base">groups</span>
                    <span class="truncate">Attendance</span>
                </button>
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

    <!-- Main Content: Video + Chat -->
    <div class="flex flex-1 gap-4 flex-col-reverse md:flex-row">
        <!-- Video Section -->
        <div class="flex-1 flex flex-col gap-4">
            <!-- Spotlight Section -->
            <div id="spotlight-container" class="w-full aspect-video rounded-md overflow-hidden shadow-lg bg-black hidden mb-4"></div>
            <!-- Video Grid -->
            <div id="video-container" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 overflow-y-auto max-h-screen"></div>
        </div>

        <!-- Chat Section -->
        <div id="chat-section" 
            class="fixed bottom-4 right-4 w-full max-w-[350px] sm:w-80 sm:right-4 bg-[#2b2d31] rounded-t-md flex flex-col shadow-lg border border-gray-700 overflow-hidden transition-all duration-300 z-50 hidden sm:flex">
            <!-- Chat Header -->
            <div id="chat-toggle" class="px-4 py-2 border-b border-gray-600 text-gray-100 font-semibold flex justify-between items-center cursor-pointer">
                <span>Chat</span>
                <div class="flex items-center gap-2">
                    <span id="chat-toggle-icon" class="material-icons text-gray-300 hover:text-white cursor-pointer">expand_less</span>
                    <span id="chat-close-container"></span> <!-- mobile close icon will go here -->
                </div>

            </div>

            <!-- Chat Messages -->
            <div id="chat-messages-wrapper" class="flex flex-col max-h-[60vh] overflow-hidden transition-all duration-300">
                <div id="chat-messages" class="flex-1 p-2 overflow-y-auto text-gray-200 space-y-2"></div>
            </div>

            <!-- Chat Form -->
            <form id="chat-form" class="flex flex-row items-center justify-center p-2 border-t border-gray-600 gap-2 pb-safe">
                <input type="text" id="chat-input" placeholder="Type a message..." 
                    class="flex-1 px-3 py-2 rounded-md bg-[#1f1f25] text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button type="submit" class="cursor-pointer px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-md text-white">Send</button>
            </form>
        </div>


    </div>

    <!-- Mobile Chat Button -->
    <button id="mobile-chat-btn" class="sm:hidden fixed bottom-4 right-4 z-50 bg-blue-600 hover:bg-blue-700 text-white p-4 rounded-full shadow-lg flex items-center justify-center">
        <span class="material-icons">message</span>
    </button>

    <!-- Controls: Cam / Mic / Screen -->
    <div class="flex justify-center gap-4 mb-4">
        <button id="btnToggleCam" class="cursor-pointer flex items-center gap-2 px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-md">
            <span id="iconCam" class="material-icons">videocam</span>
            <span id="textCam" hidden>Turn Off Cam</span>
        </button>
        <button id="btnToggleMic" class="cursor-pointer flex items-center gap-2 px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-md">
            <span id="iconMic" class="material-icons">mic</span>
            <span id="textMic" hidden>Turn Off Mic</span>
        </button>
        <button id="btnShareScreen" class="cursor-pointer flex items-center gap-2 px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-md">
            <span id="iconScreen" class="material-icons">stop_screen_share</span>
            <span id="textScreen" hidden>Share Screen</span>
        </button>
    </div>
</main>





<script src="https://download.agora.io/sdk/release/AgoraRTC_N.js"></script>
<script>
const APP_ID = "b2e962fe791e4b23a34dee48010a733f";
let client = AgoraRTC.createClient({ mode: 'rtc', codec: 'vp8' });
let localTracks = { videoTrack: null, audioTrack: null };
let screenTrack = null;
let isSharingScreen = false;

const videoContainer = document.getElementById('video-container');
const statusBox = document.getElementById('status');
const meetingCode  = "<?= $meetingCode ?>";
const user_id = `<?= $user_id ?>`;

// ---------------------- Helper Functions ----------------------
function setStatus(message, type = '') {
    statusBox.textContent = message;
    if(type === "success") 
        statusBox.className = "w-full p-2 mb-4 bg-green-100 rounded-md text-green-700 text-center";
    else if(type === "error") 
        statusBox.className = "w-full p-2 mb-4 bg-red-100 rounded-md text-red-700 text-center";
    else 
        statusBox.className = "w-full p-2 mb-4 bg-gray-200 rounded-md text-gray-800 text-center";
}

function disableButton(id, disabled, reason = '') {
    const btn = document.getElementById(id);
    if (!btn) return;
    btn.disabled = disabled;
    btn.style.opacity = disabled ? "0.5" : "1";
    btn.style.cursor = disabled ? "not-allowed" : "pointer";
    btn.title = reason || '';
}

let pinnedUserId = null;

function createUserWrapper(uid, name, isLocal = false) {
    const wrapper = document.createElement('div');
    wrapper.id = `wrapper-${uid}`;
    wrapper.className = "relative aspect-video rounded-md overflow-hidden shadow-md bg-black m-2";

    const videoDiv = document.createElement('div');
    videoDiv.id = isLocal ? 'local-player' : `player-${uid}`;
    videoDiv.className = "w-full h-full";
    wrapper.appendChild(videoDiv);

    const profileDiv = document.createElement('div');
    profileDiv.id = isLocal ? 'default-user-icon' : `default-icon-${uid}`;
    profileDiv.className = `absolute inset-0 ${isLocal ? 'hidden' : 'flex'} items-center justify-center text-white text-4xl font-bold rounded-full`;
    profileDiv.style.fontFamily = 'sans-serif';
    profileDiv.style.backgroundColor = '#6b7280';
    wrapper.appendChild(profileDiv);

    const nameTag = document.createElement('div');
    nameTag.id = `name-tag-${uid}`;
    nameTag.innerText = name;
    nameTag.className = "absolute bottom-0 left-0 w-full text-center text-white bg-black/60 text-sm py-1 z-20";
    wrapper.appendChild(nameTag);

    const micStatus = document.createElement('span');
    micStatus.id = `mic-status-${uid}`;
    micStatus.className = "material-icons absolute top-2 right-2 text-white bg-black/50 rounded-full p-1 cursor-pointer";
    micStatus.textContent = "mic";
    wrapper.appendChild(micStatus);

    const pinButton = document.createElement('span');
    pinButton.id = `pin-btn-${uid}`;
    pinButton.className = "material-icons absolute top-2 left-2 text-white bg-black/50 rounded-full p-1 cursor-pointer";
    pinButton.textContent = "push_pin";
    pinButton.title = "Pin User";
    wrapper.appendChild(pinButton);

    pinButton.addEventListener('click', () => {
        pinnedUserId = pinnedUserId === uid ? null : uid;
        updatePins();
    });

    return wrapper;
}

function updatePins() {
    const spotlight = document.getElementById('spotlight-container');
    document.querySelectorAll('[id^="pin-btn-"]').forEach(btn => {
        const uid = btn.id.replace('pin-btn-', '');
        const wrapper = document.getElementById(`wrapper-${uid}`);
        if (uid === pinnedUserId) {
            btn.style.color = "#facc15";
            wrapper.style.border = "3px solid #facc15";
            wrapper.style.zIndex = 10;
            if (wrapper && spotlight) {
                spotlight.innerHTML = '';
                spotlight.appendChild(wrapper);
                spotlight.classList.remove('hidden');
            }
        } else {
            btn.style.color = "white";
            wrapper.style.border = "none";
            wrapper.style.zIndex = 1;
            if (wrapper && spotlight.contains(wrapper)) {
                videoContainer.appendChild(wrapper);
                spotlight.classList.add('hidden');
            }
        }
    });
}

// ---------------------- Join Meeting ----------------------
async function joinMeeting(code) {
    try {
        setStatus("Requesting camera & microphone permission...");
        const stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
        stream.getTracks().forEach(track => track.stop());

        client.on('user-published', async (user, mediaType) => {
            await client.subscribe(user, mediaType);
            let wrapper = document.getElementById('wrapper-' + user.uid);
            if (!wrapper) {
                wrapper = createUserWrapper(user.uid, `User ${user.uid}`);
                videoContainer.appendChild(wrapper);
            }
            if (mediaType === 'video') {
                user.videoTrack.play('player-' + user.uid);
                document.getElementById('player-' + user.uid).style.display = 'block';
                document.getElementById('default-icon-' + user.uid).classList.add('hidden');
            }
            if (mediaType === 'audio') {
                user.audioTrack.play();
                const micIndicator = document.getElementById(`mic-status-${user.uid}`);
                if (micIndicator) micIndicator.textContent = "mic";
            }
            get_each_users_data(user.uid);
        });

        client.on('user-unpublished', (user, mediaType) => {
            if (mediaType === 'video') {
                const player = document.getElementById('player-' + user.uid);
                const icon = document.getElementById('default-icon-' + user.uid);
                if (player && icon) {
                    player.style.display = 'none';
                    icon.classList.remove('hidden');
                    icon.classList.add('flex');
                    get_each_users_data(user.uid);
                }
            } else if (mediaType === 'audio') {
                const micIndicator = document.getElementById(`mic-status-${user.uid}`);
                if (micIndicator) micIndicator.textContent = "mic_off";
            }
        });

        setStatus(`Joining room: ${code} as ${user_id}...`);
        await client.join(APP_ID, code, null, user_id);

        localTracks.audioTrack = await AgoraRTC.createMicrophoneAudioTrack();
        await client.publish([localTracks.audioTrack]);

        localTracks.videoTrack = await AgoraRTC.createCameraVideoTrack();
        await localTracks.videoTrack.setEnabled(false);

        const localWrapper = createUserWrapper('local', `${user_id} (You)`, true);
        videoContainer.appendChild(localWrapper);

        const localPlayer = document.getElementById('local-player');
        const defaultIcon = document.getElementById('default-user-icon');
        localPlayer.style.display = 'none';
        defaultIcon.classList.remove('hidden');
        defaultIcon.classList.add('flex');

        const iconCam = document.getElementById('iconCam');
        const textCam = document.getElementById('textCam');
        iconCam.textContent = 'videocam_off';
        textCam.textContent = 'Turn On Cam';

        // Disable share screen initially
        disableButton('btnShareScreen', true, 'Turn on camera to share screen');

        get_each_users_data(user_id, true);
        setStatus(`✅ Joined meeting: ${code} as ${user_id}`, "success");
    } catch (err) {
        console.error(err);
        setStatus("❌ Failed to join meeting: " + err.message, "error");
    }
}

// ---------------------- Toggle Camera ----------------------
document.getElementById('btnToggleCam').addEventListener('click', async () => {
    if (!localTracks.videoTrack) return;

    if (isSharingScreen && localTracks.videoTrack.enabled) {
        setStatus("⚠️ Cannot turn off camera while screen sharing.", "error");
        return;
    }

    const icon = document.getElementById('iconCam');
    const text = document.getElementById('textCam');
    const defaultIcon = document.getElementById('default-user-icon');
    const localPlayer = document.getElementById('local-player');

    if (localTracks.videoTrack.enabled) {
        await localTracks.videoTrack.setEnabled(false);
        await client.unpublish(localTracks.videoTrack);
        icon.textContent = 'videocam_off';
        text.textContent = 'Turn On Cam';
        localPlayer.style.display = 'none';
        defaultIcon.classList.remove('hidden');
        defaultIcon.classList.add('flex');
        disableButton('btnShareScreen', true, 'Turn on camera to share screen');
    } else {
        localTracks.videoTrack = await AgoraRTC.createCameraVideoTrack();
        await client.publish(localTracks.videoTrack);
        await localTracks.videoTrack.play('local-player');
        icon.textContent = 'videocam';
        text.textContent = 'Turn Off Cam';
        localPlayer.style.display = 'block';
        defaultIcon.classList.add('hidden');
        defaultIcon.classList.remove('flex');
        disableButton('btnShareScreen', false);
    }
});

// ---------------------- Toggle Mic ----------------------
document.getElementById('btnToggleMic').addEventListener('click', async () => {
    if (!localTracks.audioTrack) return;
    const icon = document.getElementById('iconMic');
    const text = document.getElementById('textMic');
    const micIndicator = document.getElementById('mic-status-local');

    if (localTracks.audioTrack.enabled) {
        await localTracks.audioTrack.setEnabled(false);
        icon.textContent = 'mic_off';
        text.textContent = 'Turn On Mic';
        if(micIndicator) micIndicator.textContent = "mic_off";
    } else {
        await localTracks.audioTrack.setEnabled(true);
        icon.textContent = 'mic';
        text.textContent = 'Turn Off Mic';
        if(micIndicator) micIndicator.textContent = "mic";
    }
});

// ---------------------- Share Screen ----------------------
document.getElementById('btnShareScreen').addEventListener('click', async () => {
    const icon = document.getElementById('iconScreen');
    const text = document.getElementById('textScreen');

    if (localTracks.videoTrack && !localTracks.videoTrack.enabled) {
        setStatus("⚠️ Please turn on your camera before sharing screen.", "error");
        return;
    }

    try {
        if (!isSharingScreen) {
            screenTrack = await AgoraRTC.createScreenVideoTrack({ encoderConfig: "1080p_1" });
            if (localTracks.videoTrack) {
                try { await client.unpublish(localTracks.videoTrack); } catch {}
                localTracks.videoTrack.stop();
                localTracks.videoTrack.close();
                localTracks.videoTrack = null;
            }
            await client.publish(screenTrack);
            screenTrack.play('local-player');

            icon.textContent = 'screen_share';
            text.textContent = 'Stop Sharing';
            isSharingScreen = true;
            setStatus("🖥️ Screen sharing started.", "success");
            disableButton('btnToggleCam', true, 'Stop screen sharing first to toggle camera');
            screenTrack.on('track-ended', async () => await stopScreenShare());
        } else {
            await stopScreenShare();
        }
    } catch (err) {
        console.error(err);
        setStatus("❌ Failed to start screen sharing: " + err.message, "error");
        await stopScreenShare();
    }
});

async function stopScreenShare() {
    const icon = document.getElementById('iconScreen');
    const text = document.getElementById('textScreen');
    const defaultIcon = document.getElementById('default-user-icon');

    try {
        if (screenTrack) {
            try { await client.unpublish(screenTrack); } catch {}
            screenTrack.stop();
            screenTrack.close();
            screenTrack = null;
        }

        // Restore camera track safely
        if (!localTracks.videoTrack) {
            localTracks.videoTrack = await AgoraRTC.createCameraVideoTrack();
        }

        // Only publish if not already published
        const publishedTracks = client.localTracks || [];
        if (!publishedTracks.includes(localTracks.videoTrack)) {
            await client.publish(localTracks.videoTrack);
        }

        localTracks.videoTrack.play('local-player');
        defaultIcon.classList.add('hidden');
        defaultIcon.classList.remove('flex');

        icon.textContent = 'stop_screen_share';
        text.textContent = 'Share Screen';
        isSharingScreen = false;
        setStatus("🖥️ Screen sharing stopped. Camera restored.", "success");
        disableButton('btnToggleCam', false);
    } catch (err) {
        console.error(err);
        setStatus("❌ Error restoring camera: " + err.message, "error");
    }
}


// ---------------------- Auto Join ----------------------
joinMeeting(meetingCode);

// ---------------------- Fetch User Data ----------------------
function get_each_users_data(userId, isLocal = false) {
    $.ajax({
        url: "../controller/end-points/controller.php",
        type: "GET",
        data: { requestType: "get_users_data", user_id: userId },
        dataType: "json",
        success: function(response) {
            if(response && response.status === 200 && response.data) {
                const { user_fullname, user_profile_pict } = response.data;
                const wrapperId = isLocal ? 'wrapper-local' : `wrapper-${userId}`;
                const profileId = isLocal ? 'default-user-icon' : `default-icon-${userId}`;
                const nameTagId = isLocal ? 'name-tag-local' : `name-tag-${userId}`;
                const wrapper = document.getElementById(wrapperId);
                const profileDiv = wrapper ? wrapper.querySelector(`#${profileId}`) : null;
                const nameTag = wrapper ? wrapper.querySelector(`#${nameTagId}`) : null;
                if(nameTag) nameTag.innerText = isLocal ? `${user_fullname} (You)` : user_fullname;
                if(profileDiv) {
                    const size = 150;
                    profileDiv.style.width = `${size}px`;
                    profileDiv.style.height = `${size}px`;
                    profileDiv.style.borderRadius = '50%';
                    profileDiv.style.position = 'absolute';
                    profileDiv.style.top = '50%';
                    profileDiv.style.left = '50%';
                    profileDiv.style.transform = 'translate(-50%, -50%)';
                    profileDiv.style.overflow = 'hidden';
                    if(user_profile_pict && user_profile_pict.trim() !== "") {
                        profileDiv.style.backgroundImage = `url('../static/upload/profile/${user_profile_pict}')`;
                        profileDiv.style.backgroundSize = 'cover';
                        profileDiv.style.backgroundPosition = 'center';
                        profileDiv.textContent = '';
                    } else {
                        profileDiv.style.backgroundImage = '';
                        profileDiv.style.backgroundColor = '#6b7280';
                        profileDiv.textContent = user_fullname.charAt(0).toUpperCase();
                    }
                    if((isLocal && !localTracks.videoTrack.enabled) || (!isLocal && wrapper.querySelector(`#player-${userId}`).style.display === 'none')) {
                        profileDiv.classList.remove('hidden');
                        profileDiv.classList.add('flex');
                    }
                }
            }
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error:", error);
        }
    });
}
</script>


















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
                           <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center bg-gray-800 backdrop-blur-md p-4 rounded-md border border-gray-700 shadow transition" data-user-id="${user.jr_user_id}">
                                <div class="mb-3 sm:mb-0">
                                    <p class="font-semibold text-sm sm:text-base">${user.user_fullname}</p>
                                    <p class="text-xs sm:text-sm text-gray-400">${user.user_email}</p>
                                    <p class="text-xs sm:text-sm text-gray-400">Joined: ${formatDateTime(user.jr_requested_at)}</p>
                                </div>
                                <div class="flex sm:flex-col gap-2 w-full sm:w-auto items-start sm:items-end">
                                    <button class="removeMemberBtn cursor-pointer px-3 py-1 bg-red-600 hover:bg-red-700 rounded-md text-white text-sm w-full sm:w-auto">
                                        Remove
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











<!-- Modal Overlay -->
<div id="joinerModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
  <!-- Modal Content -->
  <div class="bg-[#232428] backdrop-blur-md text-gray-100 rounded-lg shadow-xl w-11/12 sm:w-3/4 md:w-2/3 lg:w-1/2 p-6 relative border border-gray-700">
      
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






<?php 
include "../src/components/home/footer.php";
?>
<script src="../static/js/home/conference_room.js"></script>