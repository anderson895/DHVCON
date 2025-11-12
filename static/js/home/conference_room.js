$(document).ready(function() {

    class Chat {
        constructor(roomCode) {
            this.roomCode = roomCode;
            this.chatContainer = $('#chat-messages');
            this.chatForm = $('#chat-form');
            this.chatInput = $('#chat-input');
            this.status = $('#status');

            this.init();
        }

        init() {
            this.bindEvents();
            this.loadMessages(); // initial load
            this.startPolling(); // refresh every 3s
        }

        bindEvents() {
            const self = this;

            // Send message
            this.chatForm.on('submit', function(e) {
                e.preventDefault();
                const message = self.chatInput.val().trim();
                if (message !== '') {
                    self.sendMessage(message);
                    self.chatInput.val('');
                }
            });

            // Leave Room button
            $('#btnLeaveRoom').on('click', function(e) {
                e.preventDefault();
                self.leaveRoom();
            });
        }

        sendMessage(message) {
            const self = this;
            $.ajax({
                url: "../controller/end-points/controller.php",
                type: 'POST',
                data: {
                    requestType: 'sendchats',
                    roomCode: self.roomCode,
                    message: message
                },
                dataType: 'json',
                success: function(res) {
                    if(res.success) {
                        self.addMessage({
                            message: message,
                            sender_self: true,
                            sender_name: 'You', // optional display for self
                            sender_position: ''
                        });
                    } else {
                        alert('Failed to send message');
                    }
                },
                error: function() {
                    // alert('Error sending message');
                }
            });
        }

        loadMessages() {
            const self = this;
            $.ajax({
                url: "../controller/end-points/controller.php",
                type: 'POST',
                data: {
                    requestType: 'loadchats',
                    roomCode: self.roomCode
                },
                dataType: 'json',
                success: function(res) {
                    if(res.success) {
                        self.chatContainer.html(''); // clear previous
                        res.data.forEach(msg => self.addMessage(msg));
                        self.scrollBottom();
                    }
                }
            });
        }

        addMessage(msg) {
            const messageEl = $(`
              <div class="flex ${msg.sender_self ? 'justify-end' : 'justify-start'} mb-2">
                <div class="flex flex-col ${msg.sender_self ? 'items-end' : 'items-start'} max-w-[70%]">
                    <!-- Message bubble -->
                    <div class="px-3 py-2 rounded-lg ${msg.sender_self ? 'bg-gray-700 text-white' : 'bg-gray-800 text-gray-200'} break-words">
                        ${msg.message}

                        <!-- Sender name and position below the bubble -->
                        <div class="text-xs font-medium ${msg.sender_self ? 'text-gray-400' : 'text-gray-500'} mt-1">
                            ${msg.sender_self ? 'You' : msg.sender_name} 
                            <span class="text-[10px] text-gray-400">
                                ${msg.sender_position ? '(' + msg.sender_position + ')' : ''}
                            </span>
                        </div>
                    </div>
                    
                </div>
            </div>



            `);
            this.chatContainer.append(messageEl);
            this.scrollBottom();
        }

        scrollBottom() {
            this.chatContainer.scrollTop(this.chatContainer[0].scrollHeight);
        }

        startPolling() {
            const self = this;
            setInterval(() => self.loadMessages(), 3000);
        }

        leaveRoom() {
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
                cancelButtonColor: '#d33',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '../home/';
                }
            });
        }
    }

    // Initialize Chat with PHP room code
    const urlParams = new URLSearchParams(window.location.search);
    const roomCode = urlParams.get('code');

    const chatApp = new Chat(roomCode);

});















// REQUEST


// CHAT

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





