document.addEventListener('DOMContentLoaded', function() {
    // Check if user is already logged in
    const token = localStorage.getItem('token');
    if (token && window.location.pathname.includes('login.html')) {
        window.location.href = 'index.html';
    }

    // Handle login form submission
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            login(email, password);
        });
    }

    // Handle register form submission
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const username = document.getElementById('username').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            
            // Validate passwords match
            if (password !== confirmPassword) {
                showError('Passwords do not match');
                return;
            }
            
            register(username, email, password);
        });
    }
});

// Login function
function login(email, password) {
    fetch('api/login.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            email: email,
            password: password
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.token) {
            // Save token to localStorage
            localStorage.setItem('token', data.token);
            localStorage.setItem('user_id', data.user_id);
            localStorage.setItem('username', data.username);
            
            // Redirect to dashboard
            window.location.href = 'index.html';
        } else {
            showError(data.message || 'Login failed');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError('An error occurred during login');
    });
}

// Register function
function register(username, email, password) {
    fetch('api/register.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            username: username,
            email: email,
            password: password
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.user_id) {
            // Registration successful, redirect to login
            window.location.href = 'login.html';
        } else {
            showError(data.message || 'Registration failed');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError('An error occurred during registration');
    });
}

// Logout function
function logout() {
    localStorage.removeItem('token');
    localStorage.removeItem('user_id');
    localStorage.removeItem('username');
    window.location.href = 'login.html';
}

// Show error message
function showError(message) {
    const errorElement = document.getElementById('errorMessage');
    errorElement.textContent = message;
    errorElement.classList.remove('d-none');
} 