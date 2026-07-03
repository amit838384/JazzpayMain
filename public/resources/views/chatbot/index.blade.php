<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voice AI Assistant - JazzPay</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 40px;
            max-width: 600px;
            width: 100%;
        }

        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 28px;
        }

        .subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }

        .voice-widget {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            margin-bottom: 30px;
        }

        .voice-button {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: white;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        .voice-button:hover {
            transform: scale(1.05);
            box-shadow: 0 15px 40px rgba(0,0,0,0.3);
        }

        .voice-button.active {
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .voice-icon {
            width: 50px;
            height: 50px;
        }

        .status-text {
            color: white;
            font-size: 18px;
            font-weight: 500;
        }

        .info-card {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
        }

        .info-card h3 {
            color: #333;
            margin-bottom: 10px;
            font-size: 16px;
        }

        .info-card p {
            color: #666;
            font-size: 14px;
            line-height: 1.6;
        }

        .response-area {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            min-height: 150px;
            margin-top: 20px;
            display: none;
        }

        .response-area.active {
            display: block;
        }

        .response-text {
            color: #333;
            font-size: 15px;
            line-height: 1.8;
        }

        .quick-actions {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-top: 20px;
        }

        .quick-action-btn {
            background: white;
            border: 2px solid #667eea;
            color: #667eea;
            padding: 12px;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .quick-action-btn:hover {
            background: #667eea;
            color: white;
        }

        .connection-status {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #10b981;
            margin-right: 8px;
            animation: blink 2s infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }

        .loader {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #667eea;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
            display: none;
        }

        .loader.active {
            display: block;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎙️ Voice AI Assistant</h1>
        <p class="subtitle">
            <span class="connection-status"></span>
            Connected to JazzPay System
        </p>

        <div class="voice-widget">
            <button class="voice-button" id="voiceBtn">
                <svg class="voice-icon" viewBox="0 0 24 24" fill="none" stroke="#667eea" stroke-width="2">
                    <path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"></path>
                    <path d="M19 10v2a7 7 0 0 1-14 0v-2"></path>
                    <line x1="12" y1="19" x2="12" y2="23"></line>
                    <line x1="8" y1="23" x2="16" y2="23"></line>
                </svg>
            </button>
            <p class="status-text" id="statusText">Click to speak</p>
        </div>

        <div class="info-card">
            <h3>💬 What can I help you with?</h3>
            <p>
                Ask me about your child's wallet balance, order history, today's menu, 
                dietary restrictions, or place new orders. Just speak naturally!
            </p>
        </div>

        <div class="quick-actions">
            <button class="quick-action-btn" onclick="quickQuery('balance')">
                💰 Check Balance
            </button>
            <button class="quick-action-btn" onclick="quickQuery('menu')">
                🍽️ View Menu
            </button>
            <button class="quick-action-btn" onclick="quickQuery('orders')">
                📋 Order History
            </button>
            <button class="quick-action-btn" onclick="quickQuery('restrictions')">
                🚫 Restrictions
            </button>
        </div>

        <div class="loader" id="loader"></div>

        <div class="response-area" id="responseArea">
            <div class="response-text" id="responseText"></div>
        </div>
    </div>

    <script>
        // Configuration
        const API_BASE_URL = 'https://jazzpay.dkddev.com/api/';
        const ELEVENLABS_AGENT_ID = 'agent_2901ke185bk3e1evcb5xzw2n6rfg';
        
        let authToken = localStorage.getItem('auth_token'); 
        let isListening = false;
        let recognition;

        if ('webkitSpeechRecognition' in window) {
            recognition = new webkitSpeechRecognition();
            recognition.continuous = false;
            recognition.interimResults = false;
            recognition.lang = 'en-US';

            recognition.onstart = function() {
                isListening = true;
                document.getElementById('voiceBtn').classList.add('active');
                document.getElementById('statusText').textContent = 'Listening...';
            };

            recognition.onresult = function(event) {
                const transcript = event.results[0][0].transcript;
                processVoiceQuery(transcript);
            };

            recognition.onerror = function(event) {
                console.error('Speech recognition error', event.error);
                updateStatus('Error: ' + event.error);
                resetButton();
            };

            recognition.onend = function() {
                resetButton();
            };
        }

        // Voice button click handler
        document.getElementById('voiceBtn').addEventListener('click', function() {
            if (!isListening && recognition) {
                recognition.start();
            } else if (isListening && recognition) {
                recognition.stop();
            }
        });

        // Process voice query
        async function processVoiceQuery(query) {
            updateStatus('Processing...');
            showLoader();

            try {
                const response = await fetch(`${API_BASE_URL}/query`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${authToken}`,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ query: query })
                });

                const data = await response.json();
                displayResponse(data);
                speakResponse(data);
            } catch (error) {
                console.error('Error processing query:', error);
                displayResponse({ error: 'Failed to process your request' });
            } finally {
                hideLoader();
                updateStatus('Click to speak');
            }
        }

        // Quick action queries
        async function quickQuery(type) {
            showLoader();
            
            const queries = {
                'balance': '/students',
                'menu': '/dishes',
                'orders': '/transactions',
                'restrictions': '/students'
            };

            try {
                const response = await fetch(`${API_BASE_URL}${queries[type]}`, {
                    headers: {
                        'Authorization': `Bearer ${authToken}`,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();
                displayResponse(data);
            } catch (error) {
                console.error('Error fetching data:', error);
                displayResponse({ error: 'Failed to fetch data' });
            } finally {
                hideLoader();
            }
        }

        // Display response
        function displayResponse(data) {
            const responseArea = document.getElementById('responseArea');
            const responseText = document.getElementById('responseText');
            
            responseArea.classList.add('active');
            
            if (data.error) {
                responseText.innerHTML = `<strong>Error:</strong> ${data.error}`;
                return;
            }

            // Format response based on data type
            if (data.students) {
                const studentsHtml = data.students.map(s => 
                    `<div style="margin-bottom: 10px;">
                        <strong>${s.student_name}</strong> (${s.grade})<br>
                        Wallet Balance: ₹${s.wallet_balance || 0}<br>
                        Spend Limit: ₹${s.spend_limit || 0}
                    </div>`
                ).join('');
                responseText.innerHTML = studentsHtml;
            } else if (data.dishes) {
                const dishesHtml = data.dishes.slice(0, 5).map(d => 
                    `<div style="margin-bottom: 10px;">
                        <strong>${d.dish_name}</strong> - ₹${d.price}<br>
                        <small>${d.description || ''}</small>
                    </div>`
                ).join('');
                responseText.innerHTML = dishesHtml;
            } else if (data.transactions) {
                const transHtml = data.transactions.slice(0, 5).map(t => 
                    `<div style="margin-bottom: 10px;">
                        ${t.student_name} - ${t.dish_name}<br>
                        Date: ${t.date} | Amount: ₹${t.total_price}
                    </div>`
                ).join('');
                responseText.innerHTML = transHtml;
            } else {
                responseText.innerHTML = JSON.stringify(data, null, 2);
            }
        }

        // Text-to-speech
        function speakResponse(data) {
            if ('speechSynthesis' in window) {
                let text = 'Here is the information you requested.';
                
                if (data.students && data.students.length > 0) {
                    text = `You have ${data.students.length} student${data.students.length > 1 ? 's' : ''}. `;
                    data.students.forEach(s => {
                        text += `${s.student_name} has ${s.wallet_balance || 0} rupees in their wallet. `;
                    });
                }

                const utterance = new SpeechSynthesisUtterance(text);
                utterance.rate = 0.9;
                speechSynthesis.speak(utterance);
            }
        }

        // Helper functions
        function updateStatus(text) {
            document.getElementById('statusText').textContent = text;
        }

        function resetButton() {
            isListening = false;
            document.getElementById('voiceBtn').classList.remove('active');
        }

        function showLoader() {
            document.getElementById('loader').classList.add('active');
        }

        function hideLoader() {
            document.getElementById('loader').classList.remove('active');
        }

        // Initialize on page load
        window.addEventListener('load', function() {
            // Check if user is authenticated
            if (!authToken) {
                alert('Please log in to use the voice assistant');
                // Redirect to login
                // window.location.href = '/login';
            }
        });
    </script>
</body>
</html>