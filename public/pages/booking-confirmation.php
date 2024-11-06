<?php
require_once '../../config/database.php';
require_once '../../config/PHPMailer.php';
require_once '../../config/SMTP.php';
require_once '../../config/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
session_start();

$user_email = isset($_SESSION['email']) ? $_SESSION['email'] : null;

if (!$user_email) {
    header("Location: login.html");
    exit();
}

$event_id = isset($_GET['event_id']) ? (int)$_GET['event_id'] : 0;
$seats = isset($_GET['seats']) ? json_decode($_GET['seats'], true) : [];
$amount = isset($_GET['amount']) ? (float)$_GET['amount'] : 0;

$event_sql = "SELECT e.*, c.name as category_name 
              FROM events e 
              LEFT JOIN categories c ON e.category_id = c.id 
              WHERE e.id = ?";

try {
    if ($stmt = $conn->prepare($event_sql)) {
        $stmt->bind_param("i", $event_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $event = $result->fetch_assoc();
        $stmt->close();
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    header("Location: index.php");
    exit();
}

function sendConfirmationEmail($to_email, $booking_details, $event_details)
{
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'shreyaspachporr@gmail.com';
        $mail->Password = 'wchnkrgxaduupysv'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('shreyaspachporr@gmail.com', 'ShowBazaar');
        $mail->addAddress($to_email);

        
        $mail->isHTML(true);
        $mail->Subject = 'Booking Confirmation - ' . $event_details['title'];

        $emailContent = "
            <h2>Booking Confirmation</h2>
            <p>Thank you for booking tickets for {$event_details['title']}!</p>
            
            <h3>Event Details:</h3>
            <p>Date: {$event_details['event_datetime']}</p>
            <p>Venue: {$event_details['venue']}</p>
            
            <h3>Your Seats:</h3>
            <ul>";

        foreach ($booking_details['seats'] as $type => $seats) {
            if (!empty($seats)) {
                $emailContent .= "<li>" . ucfirst($type) . ": " . implode(', ', $seats) . "</li>";
            }
        }

        $emailContent .= "</ul>
            <p><strong>Total Amount Paid: ₹{$booking_details['amount']}</strong></p>
            
            <p>Please show this email at the venue for entry.</p>
            <p>Booking ID: " . uniqid() . "</p>";

        $mail->Body = $emailContent;

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email could not be sent. Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}

if ($user_email) {
    sendConfirmationEmail($user_email, ['seats' => $seats, 'amount' => $amount], $event);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Confirmation - ShowBazaar</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .payment-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .booking-summary {
            margin-bottom: 30px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        .total-row {
            font-size: 1.2em;
            font-weight: bold;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #333;
        }

        .payment-methods {
            margin: 30px 0;
        }

        .payment-method {
            padding: 15px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            cursor: pointer;
        }

        .payment-method.selected {
            border-color: #2196F3;
            background: #e3f2fd;
        }

        .proceed-btn {
            width: 100%;
            padding: 15px;
            background: #2196F3;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1.1em;
            cursor: pointer;
            transition: background 0.3s;
        }

        .proceed-btn:hover {
            background: #1976D2;
        }

        /* Success Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: white;
            padding: 40px;
            border-radius: 10px;
            text-align: center;
        }

        .checkmark {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: block;
            stroke-width: 2;
            stroke: #4bb71b;
            stroke-miterlimit: 10;
            margin: 10% auto;
            box-shadow: inset 0px 0px 0px #4bb71b;
            animation: fill .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both;
        }

        .checkmark__circle {
            stroke-dasharray: 166;
            stroke-dashoffset: 166;
            stroke-width: 2;
            stroke-miterlimit: 10;
            stroke: #4bb71b;
            fill: none;
            animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
        }

        .checkmark__check {
            transform-origin: 50% 50%;
            stroke-dasharray: 48;
            stroke-dashoffset: 48;
            animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
        }

        @keyframes stroke {
            100% {
                stroke-dashoffset: 0;
            }
        }

        @keyframes scale {

            0%,
            100% {
                transform: none;
            }

            50% {
                transform: scale3d(1.1, 1.1, 1);
            }
        }

        @keyframes fill {
            100% {
                box-shadow: inset 0px 0px 0px 30px #4bb71b;
            }
        }
    </style>
</head>

<body>
    <?php include_once './header.php'; ?>

    <div class="payment-container">
        <h2>Booking Summary</h2>

        <div class="booking-summary">
            <div class="summary-row">
                <span>Event</span>
                <span><?php echo htmlspecialchars($event['title']); ?></span>
            </div>
            <div class="summary-row">
                <span>Date & Time</span>
                <span><?php echo (new DateTime($event['event_datetime']))->format('D, d M Y • g:i A'); ?></span>
            </div>
            <div class="summary-row">
                <span>Venue</span>
                <span><?php echo htmlspecialchars($event['venue']); ?></span>
            </div>

            <?php foreach ($seats as $type => $seatList): ?>
                <?php if (!empty($seatList)): ?>
                    <div class="summary-row">
                        <span><?php echo ucfirst($type); ?> Seats</span>
                        <span><?php echo implode(', ', $seatList); ?></span>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>

            <div class="summary-row total-row">
                <span>Total Amount</span>
                <span>₹<?php echo number_format($amount, 2); ?></span>
            </div>
        </div>

        <div class="payment-methods">
            <h3>Select Payment Method</h3>
            <div class="payment-method" onclick="selectPaymentMethod(this)">
                <i class="fas fa-credit-card"></i> Credit/Debit Card
            </div>
            <div class="payment-method" onclick="selectPaymentMethod(this)">
                <i class="fas fa-wallet"></i> UPI
            </div>
            <div class="payment-method" onclick="selectPaymentMethod(this)">
                <i class="fas fa-university"></i> Net Banking
            </div>
        </div>

        <button class="proceed-btn" onclick="processPayment()">Pay ₹<?php echo number_format($amount, 2); ?></button>
    </div>

    <!-- Success Modal -->
    <div class="modal" id="successModal">
        <div class="modal-content">
            <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none" />
                <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8" />
            </svg>
            <h2>Booking Successful!</h2>
            <p>Your tickets have been booked successfully.</p>
            <p>A confirmation email has been sent to your registered email address.</p>
            <button class="proceed-btn" onclick="window.location.href='index.php'">Back to Home</button>
        </div>
    </div>

    <script>
        function selectPaymentMethod(element) {
            document.querySelectorAll('.payment-method').forEach(method => {
                method.classList.remove('selected');
            });
            element.classList.add('selected');
        }

        function processPayment() {
            // Simulate a successful payment
            document.getElementById('successModal').style.display = 'flex';
        }
    </script>
</body>

</html>