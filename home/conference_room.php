<?php 
include "../src/components/home/header.php";
$roomcode = $_GET['code']; 
?>

<main class="flex-1 bg-[#1e1f22] ml-0 md:ml-60 p-4 transition-all duration-300 min-h-screen flex flex-col">

    <!-- Header -->
    <div class="flex justify-between items-center px-6 py-4 bg-[#1f1f25] rounded-md shadow-lg mb-4 border border-gray-700">
        <h1 class="text-xl text-gray-100 font-semibold">Conference Room</h1>
        <div class="flex items-center space-x-4">
            <span class="text-gray-400">Room Code: <strong class="text-gray-100"><?= $roomcode ?></strong></span>
            <button id="btnLeaveRoom" 
                class="joiner-only cursor-pointer flex items-center gap-2 text-gray-400 hover:text-white order-1 sm:order-2 mb-2 sm:mb-0">
                <span class="material-icons">exit_to_app</span>
                <span class="hidden sm:inline">Leave Room</span>
            </button>
        </div>
    </div>

    <!-- Status -->
    <div id="status" class="w-full p-2 mb-4 bg-gray-200 rounded-md text-gray-800 text-center">
        Ready to join
    </div>

    <!-- Controls: Cam / Mic -->
    <div class="flex gap-4 mb-4">
        <button id="btnToggleCam" class="flex items-center gap-2 px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-md">
            <span id="iconCam" class="material-icons">videocam</span>
            <span id="textCam">Turn Off Cam</span>
        </button>
        <button id="btnToggleMic" class="flex items-center gap-2 px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-md">
            <span id="iconMic" class="material-icons">mic</span>
            <span id="textMic">Turn Off Mic</span>
        </button>
    </div>


    <!-- Main Content: Video + Chat -->
    <div class="flex flex-1 gap-4">

        <!-- Video Section -->
        <div id="video-container" class="flex-1 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            <!-- Video players will appear here -->
        </div>

        <!-- Chat Section -->
        <div class="w-80 bg-[#2b2d31] rounded-md flex flex-col shadow-lg border border-gray-700">
            <div class="px-4 py-2 border-b border-gray-600 text-gray-100 font-semibold">
                Chat
            </div>
            <div id="chat-messages" class="flex-1 p-2 overflow-y-auto text-gray-200 space-y-2">
                <!-- Messages will appear here -->
            </div>
            <form id="chat-form" class="flex p-2 border-t border-gray-600">
                <input type="text" id="chat-input" placeholder="Type a message..." 
                    class="flex-1 px-3 py-2 rounded-md bg-[#1f1f25] text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button type="submit" 
                    class="ml-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-md text-white">
                    Send
                </button>
            </form>
        </div>

    </div>
</main>

<script src="https://download.agora.io/sdk/release/AgoraRTC_N.js"></script>
<script>
const APP_ID = "b2e962fe791e4b23a34dee48010a733f";
let client = AgoraRTC.createClient({ mode: 'rtc', codec: 'vp8' });
let localTracks = { videoTrack: null, audioTrack: null };
let localPlayer;
const videoContainer = document.getElementById('video-container');
const statusBox = document.getElementById('status');
const roomCode = "<?= $roomcode ?>";

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

        client.on('user-published', async (user, mediaType) => {
            await client.subscribe(user, mediaType);
            let player = document.getElementById('player-' + user.uid);
            if (!player) {
                player = document.createElement('div');
                player.id = 'player-' + user.uid;
                player.className = "aspect-video rounded-md overflow-hidden relative shadow-md bg-black";
                videoContainer.appendChild(player);
            }
            if (mediaType === 'video') user.videoTrack.play(player);
            if (mediaType === 'audio') user.audioTrack.play();
        });

        client.on('user-unpublished', (user) => {
            const player = document.getElementById('player-' + user.uid);
            if (player) player.remove();
        });

        setStatus(`Joining room: ${code}...`);
        const uid = await client.join(APP_ID, code, null, null);

        localTracks.audioTrack = await AgoraRTC.createMicrophoneAudioTrack();
        localTracks.videoTrack = await AgoraRTC.createCameraVideoTrack();

        localPlayer = document.createElement('div');
        localPlayer.id = 'local-player';
        localPlayer.className = "aspect-video rounded-md overflow-hidden relative shadow-md bg-black";
        videoContainer.appendChild(localPlayer);

        await localTracks.videoTrack.play(localPlayer);
        await client.publish(Object.values(localTracks));

        setStatus(`Joined meeting: ${code}`, "success");

    } catch (err) {
        console.error(err);
        Object.values(localTracks).forEach(track => { if(track){ track.stop(); track.close(); }});
        setStatus("❌ Failed to join meeting: " + err.message, "error");
    }
}

// Toggle Cam
document.getElementById('btnToggleCam').addEventListener('click', async () => {
    if (!localTracks.videoTrack) return;
    const icon = document.getElementById('iconCam');
    const text = document.getElementById('textCam');

    if (localTracks.videoTrack.enabled) {
        localTracks.videoTrack.setEnabled(false);
        icon.textContent = 'videocam_off';
        text.textContent = 'Turn On Cam';
    } else {
        localTracks.videoTrack.setEnabled(true);
        icon.textContent = 'videocam';
        text.textContent = 'Turn Off Cam';
    }
});

// Toggle Mic
document.getElementById('btnToggleMic').addEventListener('click', async () => {
    if (!localTracks.audioTrack) return;
    const icon = document.getElementById('iconMic');
    const text = document.getElementById('textMic');

    // Correct property: `enabled` (not isEnabled())
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


// Automatically join on page load
joinMeeting(roomCode);

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