function handleCredentialResponse(response) {
    const idToken = response.credential;

    // Send token to your server
    fetch('/api/user/login-google', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ token: idToken })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Login successful!');
            window.location.href = '/user-dashboard.html';
        } else {
            alert('Login failed: ' + data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}

// Add event listener for form login (optional)
document.getElementById('user-login-form').addEventListener('submit', function (event) {
    event.preventDefault();
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;

    // Implement your custom login logic here
    fetch('/api/user/login', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ username, password })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Login successful!');
            window.location.href = '/user-dashboard.html';
        } else {
            alert('Login failed!');
        }
    })
    .catch(error => console.error('Error:', error));
});
