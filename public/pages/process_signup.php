<?php
require_once '../../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($conn) || $conn->connect_error) {
        die("Database connection failed. Please check your configuration.");
    }

    // Sanitize inputs
    $user_type = $conn->real_escape_string($_POST['user_type']);
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $created_at = date('Y-m-d H:i:s');

    try {
        // Start transaction
        $conn->begin_transaction();

        // Check for existing email in both tables
        $check_admin = $conn->query("SELECT email FROM admins WHERE email = '$email'");
        $check_user = $conn->query("SELECT email FROM users WHERE email = '$email'");

        if ($check_admin->num_rows > 0 || $check_user->num_rows > 0) {
            $conn->rollback();
            echo "<script>
                    alert('Email already exists. Please use a different email.');
                    window.location.href='signup.html';
                  </script>";
            exit();
        }

        // Debug: Print password info (remove in production)
        error_log("Password: " . $password);

        // Insert based on user type
        if ($user_type === 'admin') {
            $sql = "INSERT INTO admins (username, password, email) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $name, $password, $email);
            $stmt->execute();
            $stmt->close();
        } else {
            $sql = "INSERT INTO users (name, email, password, phone, created_at) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $name, $email, $password, $phone, $created_at);
            $stmt->execute();
            $stmt->close();
        }

        // Commit transaction
        $conn->commit();

        echo "<script>
                alert('Registration successful! Please login.');
                window.location.href='login.html';
              </script>";
        exit();

    } catch (Exception $e) {
        $conn->rollback();
        echo "<script>
                alert('Registration failed: " . addslashes($e->getMessage()) . "');
                window.location.href='signup.html';
              </script>";
        exit();
    }
}
?>