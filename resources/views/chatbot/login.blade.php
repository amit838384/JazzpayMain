<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - JazzPay Voice AI</title>
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

        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 50px 40px;
            max-width: 450px;
            width: 100%;
        }

        .logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo h1 {
            color: #667eea;
            font-size: 32px;
            margin-bottom: 5px;
        }

        .logo p {
            color: #666;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            color: #333;
            font-weight: 500;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: #667eea;
        }

        .password-toggle {
            position: relative;
        }

        .password-toggle button {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #666;
            cursor: pointer;
            font-size: 14px;
        }

        .login-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }

        .login-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .error-message {
            background: #fee;
            color: #c33;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            display: none;
        }

        .error-message.active {
            display: block;
        }

        .success-message {
            background: #d1fae5;
            color: #065f46;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            display: none;
        }

        .success-message.active {
            display: block;
        }

        .forgot-password {
            text-align: center;
            margin-top: 15px;
        }

        .forgot-password a {
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
        }

        .forgot-password a:hover {
            text-decoration: underline;
        }

        .loader {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #667eea;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            animation: spin 1s linear infinite;
            margin: 0 auto;
            display: none;
        }

        .loader.active {
            display: block;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .divider {
            text-align: center;
            margin: 25px 0;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e5e7eb;
        }

        .divider span {
            background: white;
            padding: 0 15px;
            color: #666;
            font-size: 14px;
            position: relative;
            z-index: 1;
        }

        .demo-credentials {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .demo-credentials h3 {
            font-size: 14px;
            color: #333;
            margin-bottom: 8px;
        }

        .demo-credentials p {
            font-size: 13px;
            color: #666;
            margin: 3px 0;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <h1>🎙️ JazzPay</h1>
            <p>Voice AI Assistant Portal</p>
        </div>

        <div class="demo-credentials">
            <h3>📋 Demo Credentials</h3>
            <p><strong>Email:</strong> self.ashraf1416@gmail.com</p>
            <p><strong>Password:</strong> Your registered password</p>
        </div>

        <div class="error-message" id="errorMessage"></div>
        <div class="success-message" id="successMessage"></div>

        <form id="loginForm">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    placeholder="Enter your email"
                    required
                    value="self.ashraf1416@gmail.com"
                >
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="password-toggle">
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="Enter your password"
                        required
                    >
                    <button type="button" onclick="togglePassword()">👁️</button>
                </div>
            </div>

            <div class="loader" id="loader"></div>

            <button type="submit" class="login-btn" id="loginBtn">
                Login to Voice Assistant
            </button>
        </form>

        <div class="forgot-password">
            <a href="#" onclick="showForgotPassword(); return false;">Forgot Password?</a>
        </div>
    </div>

    <script>
        const API_BASE_URL = '/api';

        // Check if already logged in
        window.addEventListener('load', function() {
            const token = localStorage.getItem('jwt_token');
            if (token) {
                // Verify token is still valid
                fetch(`${API_BASE_URL}/auth/me`, {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                }).then(response => {
                    if (response.ok) {
                        window.location.href = '/voice-assistant';
                    } else {
                        localStorage.removeItem('jwt_token');
                    }
                });
            }
        });

        // Login form submission
        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const loginBtn = document.getElementById('loginBtn');
            const loader = document.getElementById('loader');

            // Clear previous messages
            hideError();
            hideSuccess();

            // Validate inputs
            if (!email || !password) {
                showError('Please fill in all fields');
                return;
            }

            // Show loader
            loginBtn.disabled = true;
            loader.classList.add('active');

            try {
                const response = await fetch(`${API_BASE_URL}/auth/login`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        email: email,
                        password: password
                    })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    // Store JWT token
                    localStorage.setItem('jwt_token', data.access_token);
                    
                    // Store user info
                    localStorage.setItem('user_name', data.parent.name);
                    localStorage.setItem('user_email', data.parent.email);

                    showSuccess('Login successful! Redirecting...');

                    // Redirect to voice assistant
                    setTimeout(() => {
                        window.location.href = '/voice-assistant';
                    }, 1000);

                } else {
                    showError(data.message || 'Login failed. Please check your credentials.');
                }

            } catch (error) {
                console.error('Login error:', error);
                showError('Connection error. Please try again.');
            } finally {
                loginBtn.disabled = false;
                loader.classList.remove('active');
            }
        });

        // Toggle password visibility
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
            } else {
                passwordInput.type = 'password';
            }
        }

        // Show forgot password
        function showForgotPassword() {
            const email = prompt('Enter your email address:');
            if (email) {
                resetPassword(email);
            }
        }

        // Reset password
        async function resetPassword(email) {
            try {
                const response = await fetch(`${API_BASE_URL}/auth/forgot-password`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ email: email })
                });

                const data = await response.json();

                if (data.success) {
                    showSuccess('Password reset instructions sent to your email!');
                } else {
                    showError(data.message || 'Failed to send reset instructions');
                }
            } catch (error) {
                console.error('Reset error:', error);
                showError('Failed to process request');
            }
        }

        // Helper functions
        function showError(message) {
            const errorEl = document.getElementById('errorMessage');
            errorEl.textContent = message;
            errorEl.classList.add('active');
        }

        function hideError() {
            document.getElementById('errorMessage').classList.remove('active');
        }

        function showSuccess(message) {
            const successEl = document.getElementById('successMessage');
            successEl.textContent = message;
            successEl.classList.add('active');
        }

        function hideSuccess() {
            document.getElementById('successMessage').classList.remove('active');
        }
    </script>
</body>
</html>