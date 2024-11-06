<?php
// event-form.php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Event</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <form action="process-event.php" method="POST" enctype="multipart/form-data" class="form-container">
            <h2>Create New Event</h2>
            
            <div class="form-group">
                <label for="category_id">Category</label>
                <select name="category_id" id="category_id" required>
                    <option value="">Select Category</option>
                    <option value="">Movie</option>
                    <option value="">Comedy</option>
                    <option value="">Sports</option>
                    <option value="">COncerts</option>

                </select>
            </div>

            <div class="form-group">
                <label for="title">Event Title</label>
                <input type="text" name="title" id="title" required maxlength="200">
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" rows="4"></textarea>
            </div>

            <div class="form-group">
                <label for="image_url">Event Image</label>
                <input type="file" name="image_url" id="image_url" accept="image/*">
            </div>

            <div class="form-group">
                <label for="venue">Venue</label>
                <input type="text" name="venue" id="venue" maxlength="200">
            </div>

            <div class="form-group">
                <label for="event_datetime">Event Date & Time</label>
                <input type="datetime-local" name="event_datetime" id="event_datetime" required>
            </div>

            <div class="form-group">
                <label for="price_platinum">Platinum Price</label>
                <input type="number" name="price_platinum" id="price_platinum" step="0.01" min="0">
            </div>

            <div class="form-group">
                <label for="price_gold">Gold Price</label>
                <input type="number" name="price_gold" id="price_gold" step="0.01" min="0">
            </div>

            <div class="form-group">
                <label for="price_silver">Silver Price</label>
                <input type="number" name="price_silver" id="price_silver" step="0.01" min="0">
            </div>

            <div class="form-group">
                <label for="total_seats">Total Seats</label>
                <input type="number" name="total_seats" id="total_seats" required min="1">
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status" required>
                    <option value="upcoming">Upcoming</option>
                    <option value="ongoing">Ongoing</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>

            <button type="submit" class="submit-btn">Create Event</button>
        </form>
    </div>
</body>
</html>

<?php
// process-event.php
session_start();
require_once '../../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id']) && $_SESSION['user_type'] === 'admin') {
    try {
        // Handle file upload
        $image_url = null;
        if (isset($_FILES['image_url']) && $_FILES['image_url']['error'] === 0) {
            $upload_dir = '../../assets/images/events';
            $file_extension = pathinfo($_FILES['image_url']['name'], PATHINFO_EXTENSION);
            $file_name = uniqid() . '.' . $file_extension;
            $target_path = $upload_dir . $file_name;
            
            if (move_uploaded_file($_FILES['image_url']['tmp_name'], $target_path)) {
                $image_url = 'uploads/events/' . $file_name;
            }
        }

        $sql = "INSERT INTO events (category_id, title, description, image_url, venue, 
                event_datetime, price_platinum, price_gold, price_silver, 
                total_seats, available_seats, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                
        $stmt = $conn->prepare($sql);
        
        $category_id = $_POST['category_id'] ?: null;
        $total_seats = $_POST['total_seats'];
        
        $stmt->bind_param("isssssdddiis",
            $category_id,
            $_POST['title'],
            $_POST['description'],
            $image_url,
            $_POST['venue'],
            $_POST['event_datetime'],
            $_POST['price_platinum'],
            $_POST['price_gold'],
            $_POST['price_silver'],
            $total_seats,
            $total_seats, // Initially available seats equals total seats
            $_POST['status']
        );
        
        if ($stmt->execute()) {
            echo "<script>
                    alert('Event created successfully!');
                    window.location.href='events-list.php';
                  </script>";
        } else {
            throw new Exception("Failed to create event");
        }
        
    } catch (Exception $e) {
        echo "<script>
                alert('Error creating event: " . addslashes($e->getMessage()) . "');
                window.history.back();
              </script>";
    }
}
?>