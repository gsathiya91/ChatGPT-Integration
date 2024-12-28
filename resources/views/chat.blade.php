<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ChatGPT Integration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        #chat-container {
            width: 1400px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        #chat-box {
            height: 600px;
            overflow-y: auto;
            padding: 20px;
            border-bottom: 1px solid #ccc;
        }

        #chat-box div {
            margin: 5px 0;
        }

        .user-message {
            color: #007bff;
        }

        #chat-input {
            display: flex;
            padding: 10px;
            gap: 10px;
        }

        .user-message {
            align-self: flex-start;
            background-color: #e8f5e9;
            color: #333;
            padding: 10px;
            border-radius: 10px;
            word-wrap: break-word;
            margin-right: auto;
        }

        .chatgpt-message {
            align-self: flex-end;
            background-color: #f3f7f6;
            color: #080808;
            padding: 10px;
            border-radius: 10px;
            word-wrap: break-word;
            margin-left: auto;
        }

        #user-message {
            flex: 1;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        #send-message {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
        }

        #send-message:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div id="chat-container">
        <div id="chat-box"></div>
        <div id="chat-input">
            <input type="text" id="user-message" placeholder="Type your message...">
            <button id="send-message">Send</button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/showdown/dist/showdown.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            const chatBox = document.getElementById('chat-box');
            const sendMessageButton = document.getElementById('send-message');
            const userMessageInput = document.getElementById('user-message');

            const converter = new showdown.Converter();

            const getTimestamp = () => {
                const now = new Date();
                const date = now.toLocaleDateString([], {
                    year: 'numeric',
                    month: 'short',
                    day: '2-digit'
                });
                const time = now.toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true
                });
                return `${date} ${time}`;
            };
            const appendMessage = (message, type, timestamp) => {
                const messageDiv = document.createElement('div');
                messageDiv.className = type === 'user' ? 'user-message' : 'chatgpt-message';

                const formattedMessage =
                    type === 'chatgpt' ? converter.makeHtml(message) : message.replace(/\n/g, '<br>');

                messageDiv.innerHTML = `
                <span style="font-size: 0.8em; color: gray;">[${timestamp}]</span>
                <br>
                ${formattedMessage}
            `;

                chatBox.appendChild(messageDiv);
                chatBox.scrollTop = chatBox.scrollHeight;
            };

            try {
                const response = await fetch('/api/get-conversations', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                });

                if (!response.ok) {
                    throw new Error('Failed to fetch previous conversations.');
                }

                const data = await response.json();
                if (data.status === 'success') {
                    const conversations = data.data;

                    conversations.forEach(conversation => {
                        const timestamp = new Date(conversation.created_at).toLocaleString([], {
                            year: 'numeric',
                            month: 'short',
                            day: '2-digit',
                            hour: '2-digit',
                            minute: '2-digit',
                            hour12: true,
                        });
                        appendMessage(`User: ${conversation.user_message}`, 'user', timestamp);
                        appendMessage(`ChatGPT Response: ${conversation.chatgpt_response}`, 'chatgpt',
                            timestamp);
                    });
                } else {
                    console.error('Error fetching conversations:', data.message);
                }
            } catch (error) {
                console.error('Error fetching conversations:', error.message);
                appendMessage('Error: Could not load previous conversations.', 'chatgpt', getTimestamp());
            }

            sendMessageButton.addEventListener('click', async () => {
                const userMessage = userMessageInput.value.trim();

                if (!userMessage) {
                    alert("Please type a message!");
                    return;
                }

                appendMessage(`User: ${userMessage}`, 'user', getTimestamp());
                sendMessageButton.disabled = true;

                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')
                        .getAttribute('content');
                    const response = await fetch('/api/send-message', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify({
                            message: userMessage
                        }),
                    });

                    if (!response.ok) {
                        throw new Error('Failed to get a response from ChatGPT.');
                    }

                    const data = await response.json();

                    if (data.chatgpt_response) {
                        appendMessage(data.chatgpt_response, 'chatgpt', getTimestamp());
                    } else {
                        console.error("Unexpected response format:", data);
                        appendMessage('ChatGPT: Error in generating response.', 'chatgpt',
                            getTimestamp());
                    }
                } catch (error) {
                    console.error('Error:', error);
                    appendMessage('ChatGPT: Failed to communicate with the server.', 'chatgpt',
                        getTimestamp());
                    appendMessage(`Error: ${error.message}`, 'chatgpt', getTimestamp());
                } finally {
                    sendMessageButton.disabled = false;
                    userMessageInput.value = '';
                }
            });

        });
    </script>
</body>

</html>
