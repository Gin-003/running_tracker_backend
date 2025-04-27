document.addEventListener('DOMContentLoaded', function() {
    // Check if user is logged in
    const token = localStorage.getItem('token');
    if (!token) {
        window.location.href = 'login.html';
        return;
    }
    
    // Get user ID from localStorage
    const userId = localStorage.getItem('user_id');
    const username = localStorage.getItem('username');
    
    // Display username
    document.getElementById('usernameDisplay').textContent = username;
    
    // Fetch workouts
    fetchWorkouts();
});

function fetchWorkouts() {
    const token = localStorage.getItem('token');
    
    fetch('api/get_workouts.php', {
        method: 'GET',
        headers: {
            'Authorization': 'Bearer ' + token,
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        if (response.status === 401) {
            // Token expired or invalid, redirect to login
            localStorage.removeItem('token');
            localStorage.removeItem('user_id');
            localStorage.removeItem('username');
            window.location.href = 'login.html';
            return;
        }
        return response.json();
    })
    .then(data => {
        if (data && data.records) {
            displayWorkouts(data.records);
        } else {
            document.getElementById('workoutTableBody').innerHTML = 
                '<tr><td colspan="6" class="text-center">No workouts found</td></tr>';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('workoutTableBody').innerHTML = 
            '<tr><td colspan="6" class="text-center">Error loading workouts</td></tr>';
    });
}

function displayWorkouts(workouts) {
    const tableBody = document.getElementById('workoutTableBody');
    tableBody.innerHTML = '';

    workouts.forEach(workout => {
        const row = document.createElement('tr');
        
        // Format date
        const date = new Date(workout.created_at);
        const formattedDate = date.toLocaleDateString() + ' ' + date.toLocaleTimeString();
        
        // Format duration (assuming it's in seconds)
        const duration = formatDuration(workout.duration);
        
        row.innerHTML = `
            <td>${formattedDate}</td>
            <td>${parseFloat(workout.distance).toFixed(2)}</td>
            <td>${duration}</td>
            <td>${parseFloat(workout.average_speed).toFixed(1)}</td>
            <td>${Math.round(workout.calories_burned)}</td>
            <td>
                <button class="btn-view-route" onclick="viewRoute('${workout.start_location}', '${workout.end_location}')">
                    View Route
                </button>
            </td>
        `;
        
        tableBody.appendChild(row);
    });
}

function formatDuration(seconds) {
    const hours = Math.floor(seconds / 3600);
    const minutes = Math.floor((seconds % 3600) / 60);
    const remainingSeconds = seconds % 60;
    
    return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(remainingSeconds).padStart(2, '0')}`;
}

function viewRoute(startLocation, endLocation) {
    // Parse the location strings (assuming they're in format "latitude,longitude")
    const [startLat, startLng] = startLocation.split(',').map(Number);
    const [endLat, endLng] = endLocation.split(',').map(Number);
    
    // Open Google Maps with the route
    const url = `https://www.google.com/maps/dir/?api=1&origin=${startLat},${startLng}&destination=${endLat},${endLng}&travelmode=walking`;
    window.open(url, '_blank');
}

// Logout function
function logout() {
    localStorage.removeItem('token');
    localStorage.removeItem('user_id');
    localStorage.removeItem('username');
    window.location.href = 'login.html';
} 