<?php
session_start();
require_once '../../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($conn) || $conn->connect_error) {
        die("Database connection failed. Please check your configuration.");
    }

    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);
    $user_type = $conn->real_escape_string($_POST['user_type']);

    try {
        // Debug log
        error_log("Login attempt - Email: $email, User Type: $user_type");

        if ($user_type === 'admin') {
            $sql = "SELECT * FROM admins WHERE email = ? AND password = ?";
        } else {
            $sql = "SELECT * FROM users WHERE email = ? AND password = ?";
        }

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        // Debug log
        error_log("User found: " . ($user ? 'Yes' : 'No'));

        if ($user) {
            // Login successful
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_type'] = $user_type;
            $_SESSION['email'] = $user['email'];
            $_SESSION['name'] = $user_type === 'admin' ? $user['username'] : $user['name'];

            error_log("Login successful - User ID: " . $_SESSION['user_id']);
            $_SESSION['user_id'] = $user['id'];
            echo "Session ID after login: " . session_id();

            if ($user_type === 'admin') {
                header("Location: ./admin.php");
            } else {
                header("Location: ./index.php");
            }
            exit();
        } else {
            // Invalid credentials
            error_log("Login failed - Invalid credentials");
            echo "<script>
                    alert('Invalid email or password.');
                    window.location.href='login.html';
                  </script>";
            exit();
        }
    } catch (Exception $e) {
        error_log("Login error: " . $e->getMessage());
        echo "<script>
                alert('Login failed: " . addslashes($e->getMessage()) . "');
                window.location.href='login.html';
              </script>";
        exit();
    }
}
