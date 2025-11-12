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
// ========================
// ========================
// CHAT
// ========================

const chatToggle = document.getElementById('chat-toggle');
const chatIcon = document.getElementById('chat-toggle-icon');
const chatCloseContainer = document.getElementById('chat-close-container');
const chatMessagesWrapper = document.getElementById('chat-messages-wrapper');
const chatInput = document.getElementById('chat-input');
const chatForm = document.getElementById('chat-form');
const chatSection = document.getElementById('chat-section');
const mobileChatBtn = document.getElementById('mobile-chat-btn');

// ------------------------
// Create mobile close icon
// ------------------------
const chatCloseIcon = document.createElement('span');
chatCloseIcon.className = 'material-icons text-gray-300 hover:text-white cursor-pointer sm:hidden';
chatCloseIcon.textContent = 'close';
chatCloseContainer.appendChild(chatCloseIcon);

// ------------------------
// State variables
// ------------------------
let isCollapsed = window.innerWidth < 640;
let submitting = false;

// ------------------------
// Slide helpers
// ------------------------
function slideIn(element) {
    element.style.display = 'flex';
    requestAnimationFrame(() => {
        element.style.transform = 'translateX(0)';
        element.style.opacity = '1';
    });
}

function slideOut(element) {
    element.style.transform = 'translateX(100%)';
    element.style.opacity = '0';
    setTimeout(() => (element.style.display = 'none'), 300);
}

// ------------------------
// Initialize chat state
// ------------------------
function initChat() {
    isCollapsed = window.innerWidth < 640;

    chatCloseIcon.style.display = isCollapsed ? 'inline-flex' : 'none';

    // Keep chat open if user is typing
    const inputActive = document.activeElement === chatInput;

    chatMessagesWrapper.style.maxHeight = inputActive ? '60vh' : (isCollapsed ? '0' : '60vh');
    chatIcon.textContent = 'expand_less';

    if (isCollapsed) {
        if (!inputActive) {
            chatSection.style.display = 'none';
            chatSection.style.transform = 'translateX(100%)';
            chatSection.style.opacity = '0';
        } else {
            // Ensure chat is visible if input is active
            chatSection.style.display = 'flex';
            chatSection.style.transform = 'translateX(0)';
            chatSection.style.opacity = '1';
        }

        // Only show mobile button if input is NOT active
        if (mobileChatBtn) mobileChatBtn.style.display = inputActive ? 'none' : 'flex';
    } else {
        chatSection.style.display = 'flex';
        chatSection.style.transform = 'translateX(0)';
        chatSection.style.opacity = '1';
        if (mobileChatBtn) mobileChatBtn.style.display = 'none';
    }
}
initChat();

// ------------------------
// Arrow toggle (desktop + mobile)
// ------------------------
chatIcon.addEventListener('click', () => {
    if (chatMessagesWrapper.style.maxHeight === '0px' || chatMessagesWrapper.style.maxHeight === '0') {
        chatMessagesWrapper.style.maxHeight = '60vh';
        chatIcon.textContent = 'expand_less';
    } else {
        chatMessagesWrapper.style.maxHeight = '0';
        chatIcon.textContent = 'expand_more';
    }
});

// ------------------------
// Close chat (mobile only)
// ------------------------
chatCloseIcon.addEventListener('click', () => {
    if (isCollapsed) {
        slideOut(chatSection);

        // Show mobile chat button only if input is NOT active
        if (mobileChatBtn && document.activeElement !== chatInput) mobileChatBtn.style.display = 'flex';
    }
});

// ------------------------
// Hide mobile chat button while typing
// ------------------------
function hideMobileBtnWhileTyping() {
    if (mobileChatBtn) mobileChatBtn.style.display = 'none';
    chatMessagesWrapper.style.maxHeight = '60vh';
}

chatInput.addEventListener('focus', hideMobileBtnWhileTyping);
chatInput.addEventListener('input', hideMobileBtnWhileTyping);

// ------------------------
// Prevent collapse while submitting
// ------------------------
chatForm.addEventListener('submit', (e) => {
    e.preventDefault();
    submitting = true;
    chatMessagesWrapper.style.maxHeight = '60vh';
    setTimeout(() => (submitting = false), 100);
});

// ------------------------
// Mobile chat button
// ------------------------
if (mobileChatBtn) {
    mobileChatBtn.addEventListener('click', () => {
        slideIn(chatSection);
        mobileChatBtn.style.display = 'none';
    });
}

// ------------------------
// Update on window resize
// ------------------------
window.addEventListener('resize', initChat);
