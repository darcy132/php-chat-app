$(document).ready(function() {
    let currentReceiverId = null;
    let lastMessageId = 0;
    let messageUpdateInterval;

    // Select user to chat with
    $('.user-item').click(function() {
        $('.user-item').removeClass('active');
        $(this).addClass('active');
        
        currentReceiverId = $(this).data('user-id');
        const username = $(this).data('username');
        
        $('.chat-header .username').text(username);
        $('.no-chat-selected').hide();
        $('.chat-area .chat-messages, .chat-area .chat-input-area').show();
        
        // Load messages for this user
        loadMessages();
        
        // Start auto-update
        startMessageUpdates();
    });

    // Send message
    $('.send-btn').click(sendMessage);
    $('.message-input').keypress(function(e) {
        if (e.which == 13 && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    function sendMessage() {
        const message = $('.message-input').val().trim();
        
        if (!message || !currentReceiverId) {
            return;
        }

        $('.send-btn').prop('disabled', true);

        $.ajax({
            url: '../ajax/send_message.php',
            type: 'POST',
            data: {
                receiver_id: currentReceiverId,
                message: message
            },
            success: function(response) {
                if (response.success) {
                    $('.message-input').val('');
                    loadMessages(); // Reload messages to show the new one
                } else {
                    alert('Error sending message: ' + response.error);
                }
            },
            error: function() {
                alert('Error sending message');
            },
            complete: function() {
                $('.send-btn').prop('disabled', false);
            }
        });
    }

    function loadMessages() {
        if (!currentReceiverId) return;

        $.ajax({
            url: '../ajax/get_messages.php',
            type: 'GET',
            data: {
                receiver_id: currentReceiverId,
                last_message_id: lastMessageId
            },
            success: function(response) {
                if (response.success) {
                    displayMessages(response.messages);
                    if (response.messages.length > 0) {
                        lastMessageId = response.messages[response.messages.length - 1].id;
                    }
                    scrollToBottom();
                }
            }
        });
    }

    function displayMessages(messages) {
        const messagesContainer = $('.chat-messages');
        
        messages.forEach(message => {
            // Check if message already exists
            if ($(`.message[data-message-id="${message.id}"]`).length === 0) {
                const messageElement = createMessageElement(message);
                messagesContainer.append(messageElement);
            }
        });
    }

    function createMessageElement(message) {
        const isSent = message.sender_id == currentUserId;
        const messageClass = isSent ? 'sent' : 'received';
        const time = new Date(message.created_at).toLocaleTimeString([], { 
            hour: '2-digit', minute: '2-digit' 
        });

        return `
            <div class="message ${messageClass}" data-message-id="${message.id}">
                <div class="message-bubble">
                    <div class="message-text">${escapeHtml(message.message)}</div>
                    <div class="message-time">${time}</div>
                </div>
            </div>
        `;
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function scrollToBottom() {
        const messagesContainer = $('.chat-messages');
        messagesContainer.scrollTop(messagesContainer[0].scrollHeight);
    }

    function startMessageUpdates() {
        // Clear existing interval
        if (messageUpdateInterval) {
            clearInterval(messageUpdateInterval);
        }
        
        // Update messages every 2 seconds
        messageUpdateInterval = setInterval(loadMessages, 2000);
    }

    // Load online users periodically
    function loadOnlineUsers() {
        $.ajax({
            url: '../ajax/get_online_users.php',
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    updateOnlineUsersList(response.users);
                }
            }
        });
    }

    function updateOnlineUsersList(users) {
        const usersContainer = $('.online-users');
        usersContainer.empty();

        if (users.length === 0) {
            usersContainer.html('<div class="no-users">No users online</div>');
            return;
        }

        users.forEach(user => {
            const userElement = `
                <div class="user-item" data-user-id="${user.id}" data-username="${escapeHtml(user.username)}">
                    <div class="avatar">${user.username.charAt(0).toUpperCase()}</div>
                    <div class="user-info">
                        <div class="username">${escapeHtml(user.username)}</div>
                        <div class="online-status"></div>
                    </div>
                </div>
            `;
            usersContainer.append(userElement);
        });

        // Reattach click events
        $('.user-item').click(function() {
            $('.user-item').removeClass('active');
            $(this).addClass('active');
            
            currentReceiverId = $(this).data('user-id');
            const username = $(this).data('username');
            
            $('.chat-header .username').text(username);
            $('.no-chat-selected').hide();
            $('.chat-area .chat-messages, .chat-area .chat-input-area').show();
            
            loadMessages();
            startMessageUpdates();
        });
    }

    // Initial load and periodic updates
    loadOnlineUsers();
    setInterval(loadOnlineUsers, 5000); // Update online users every 5 seconds

    // Handle page visibility change
    $(document).on('visibilitychange', function() {
        if (document.hidden) {
            clearInterval(messageUpdateInterval);
        } else {
            if (currentReceiverId) {
                startMessageUpdates();
            }
        }
    });
});