<?php
session_start();
require_once '../../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id']) && $_SESSION['user_type'] === 'admin') {
    try {
        $image_url = null;
        if (isset($_FILES['image_url']) && $_FILES['image_url']['error'] === 0) {
            $upload_dir = '../../assets/images/events';
            $file_extension = pathinfo($_FILES['image_url']['name'], PATHINFO_EXTENSION);
            $file_name = uniqid() . '.' . $file_extension;
            $target_path = $upload_dir . $file_name;
            
            if (move_uploaded_file($_FILES['image_url']['tmp_name'], $target_path)) {
                $image_url = 'images/events' . $file_name;
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
            $total_seats,
            $_POST['status']
        );
        
        if ($stmt->execute()) {
            echo "<script>
                    alert('Event created successfully!');
                    window.location.href='category-events.php';
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