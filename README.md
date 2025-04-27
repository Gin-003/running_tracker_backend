# Running Tracker Backend

This is the backend application for the Running Tracker Android app. It provides APIs for storing and retrieving workout data, and a web interface for viewing workout history.

## Setup Instructions

1. **Database Setup**
   - Create a MySQL database
   - Import the `database.sql` file to create the necessary tables
   - Update the database credentials in `config/database.php`

2. **Server Requirements**
   - PHP 7.4 or higher
   - MySQL 5.7 or higher
   - Apache/Nginx web server
   - mod_rewrite enabled (for Apache)

3. **Installation**
   - Place all files in your web server directory
   - Ensure proper file permissions (usually 755 for directories, 644 for files)
   - Configure your web server to point to the project directory

## API Endpoints

### Authentication

#### Register
- **URL**: `/api/register.php`
- **Method**: POST
- **Data Format**:
```json
{
    "username": "johndoe",
    "email": "john@example.com",
    "password": "password123"
}
```

#### Login
- **URL**: `/api/login.php`
- **Method**: POST
- **Data Format**:
```json
{
    "email": "john@example.com",
    "password": "password123"
}
```
- **Response Format**:
```json
{
    "message": "Login successful.",
    "user_id": 1,
    "username": "johndoe",
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
}
```

### Workout Data

#### Save Workout
- **URL**: `/api/save_workout.php`
- **Method**: POST
- **Headers**: `Authorization: Bearer {token}`
- **Data Format**:
```json
{
    "distance": 5.2,
    "duration": 1800,
    "average_speed": 10.4,
    "calories_burned": 312,
    "start_location": "16.8209,96.1553",
    "end_location": "16.8210,96.1554"
}
```

#### Get Workouts
- **URL**: `/api/get_workouts.php`
- **Method**: GET
- **Headers**: `Authorization: Bearer {token}`
- **Response Format**:
```json
{
    "records": [
        {
            "id": 1,
            "user_id": 1,
            "distance": 5.2,
            "duration": 1800,
            "average_speed": 10.4,
            "calories_burned": 312,
            "start_location": "16.8209,96.1553",
            "end_location": "16.8210,96.1554",
            "created_at": "2024-04-16 10:30:00"
        }
    ]
}
```

## Web Interface

The web interface provides a dashboard to view workout history. Access it by opening `index.html` in your web browser.

Features:
- User registration and login
- View all workouts in a table format
- See workout details including distance, duration, and calories burned
- View workout routes on Google Maps
- Responsive design for mobile devices

## Security Considerations

1. Always use HTTPS in production
2. Implement proper user authentication (JWT tokens)
3. Validate and sanitize all input data
4. Use prepared statements for database queries
5. Keep your PHP version updated
6. Set proper file permissions
7. Change the JWT secret key in production

## Android App Integration

To integrate with the Android app:

1. Update the API base URL in your Android app
2. Implement the API calls using Retrofit or similar library
3. Handle the responses appropriately
4. Implement proper error handling
5. Store the JWT token securely in the app
6. Include the token in the Authorization header for authenticated requests

## Support

For any issues or questions, please create an issue in the repository. 