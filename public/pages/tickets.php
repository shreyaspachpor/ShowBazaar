<?php
require_once '../../config/database.php';
$base_assets_path = "../../assets";

$category_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$event_id = isset($_GET['event_id']) ? (int)$_GET['event_id'] : 0;

error_log("Category ID: " . $category_id);
error_log("Event ID: " . $event_id);

if ($event_id <= 0 || $category_id <= 0) {
    error_log("Redirecting: Invalid event ID or category ID");
    header("Location: index.php");
    exit();
}

$event_sql = "SELECT e.*, c.name as category_name 
              FROM events e 
              LEFT JOIN categories c ON e.category_id = c.id 
              WHERE e.id = ? AND c.id = ?";

try {
    if ($stmt = $conn->prepare($event_sql)) {
        $stmt->bind_param("ii", $event_id, $category_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $event = $result->fetch_assoc();
        } else {
            error_log("No event found with ID: " . $event_id . " and category ID: " . $category_id);
            header("Location: index.php");
            exit();
        }
        $stmt->close();
    } else {
        throw new Exception("Failed to prepare event query");
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    header("Location: index.php");
    exit();
}

$event_date = new DateTime($event['event_datetime']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($event['title']); ?> Tickets - ShowBazaar</title>
    <link rel="stylesheet" href="../../assets/css/tickets.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300..400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <?php include_once './header.php'; ?>


    <div class="event-title">
        <h1><?php echo htmlspecialchars($event['title']); ?></h1>
        <p class="event-info">
            <?php echo $event_date->format('D, d M • g:i A'); ?> •
            <?php echo htmlspecialchars($event['venue']); ?>
        </p>
        <p class="event-category">Category: <?php echo htmlspecialchars($event['category_name']); ?></p>
    </div>

    <div class="ticket-container">
        <h2>Select Your Seats</h2>

        <div class="seating-legend">
            <div class="legend-item">
                <div class="seat available"></div>
                <span>Available</span>
            </div>
            <div class="legend-item">
                <div class="seat selected"></div>
                <span>Selected</span>
            </div>
            <div class="legend-item">
                <div class="seat occupied"></div>
                <span>Occupied</span>
            </div>
        </div>



        <div class="seating-section platinum-section">
            <h3>Platinum - ₹<?php echo number_format($event['price_platinum']); ?></h3>
            <div class="seats-container" data-price="<?php echo $event['price_platinum']; ?>" data-type="platinum">
                <?php
                for ($row = 1; $row <= 4; $row++) {
                    echo '<div class="seat-row">';
                    echo '<div class="row-label">' . chr(64 + $row) . '</div>';
                    for ($seat = 1; $seat <= 10; $seat++) {
                        echo '<div class="seat" data-row="' . chr(64 + $row) . '" data-seat="' . $seat . '"></div>';
                    }
                    echo '</div>';
                }
                ?>
            </div>
        </div>

        <div class="seating-section gold-section">
            <h3>Gold - ₹<?php echo number_format($event['price_gold']); ?></h3>
            <div class="seats-container" data-price="<?php echo $event['price_gold']; ?>" data-type="gold">
                <?php
                for ($row = 1; $row <= 5; $row++) {
                    echo '<div class="seat-row">';
                    echo '<div class="row-label">' . chr(68 + $row) . '</div>';
                    for ($seat = 1; $seat <= 12; $seat++) {
                        echo '<div class="seat" data-row="' . chr(68 + $row) . '" data-seat="' . $seat . '"></div>';
                    }
                    echo '</div>';
                }
                ?>
            </div>
            <div class="seating-section silver-section">
                <h3>Silver - ₹<?php echo number_format($event['price_silver']); ?></h3>
                <div class="seats-container" data-price="<?php echo $event['price_silver']; ?>" data-type="silver">
                    <?php
                    for ($row = 1; $row <= 6; $row++) {
                        echo '<div class="seat-row">';
                        echo '<div class="row-label">' . chr(72 + $row) . '</div>';
                        for ($seat = 1; $seat <= 14; $seat++) {
                            echo '<div class="seat" data-row="' . chr(72 + $row) . '" data-seat="' . $seat . '"></div>';
                        }
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
            <div class="screen">
                <div class="screen-text">SCREEN</div>
            </div>
        </div>


        <div class="selected-seats-summary">
            <h3>Selected Seats</h3>
            <div class="summary-content">
                <div class="seat-type platinum">
                    <span class="type-label">Platinum:</span>
                    <span class="seats-list"></span>
                    <span class="type-total"></span>
                </div>
                <div class="seat-type gold">
                    <span class="type-label">Gold:</span>
                    <span class="seats-list"></span>
                    <span class="type-total"></span>
                </div>
                <div class="seat-type silver">
                    <span class="type-label">Silver:</span>
                    <span class="seats-list"></span>
                    <span class="type-total"></span>
                </div>
            </div>
            <div class="total-amount">
                Total: ₹<span id="total-price">0</span>
            </div>
        </div>
    </div>

    <div class="final-button">
        <button class="final-btn" onclick="proceedToPayment(<?php echo $event_id; ?>)">Proceed</button>
    </div>

    <style>
        .ticket-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .seating-legend {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 20px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .screen {
            background: #e4e4e4;
            height: 40px;
            width: 80%;
            margin: 20px auto;
            transform: perspective(300px) rotateX(-10deg);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .screen-text {
            color: #666;
            font-size: 14px;
        }

        .seating-section {
            margin: 30px 0;
        }

        .seating-section h3 {
            text-align: center;
            margin-bottom: 15px;
            color: #333;
        }

        .seats-container {
            display: flex;
            flex-direction: column;
            gap: 10px;
            align-items: center;
        }

        .seat-row {
            display: flex;
            gap: 5px;
            align-items: center;
        }

        .row-label {
            width: 30px;
            text-align: right;
            margin-right: 10px;
            font-weight: bold;
        }

        .seat {
            width: 25px;
            height: 25px;
            border-radius: 5px;
            background-color: #a6a6a6;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .seat.selected {
            background-color: #2196F3;
        }

        .seat.occupied {
            background-color: #e63946;
            cursor: not-allowed;
        }

        .selected-seats-summary {
            margin-top: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .seat-type {
            display: flex;
            gap: 10px;
            margin: 10px 0;
        }

        .type-label {
            font-weight: bold;
            width: 80px;
        }

        .seats-list {
            flex-grow: 1;
        }

        .type-total {
            min-width: 100px;
            text-align: right;
        }

        .total-amount {
            margin-top: 20px;
            text-align: right;
            font-size: 1.2em;
            font-weight: bold;
        }

        .platinum-section .seats-container {
            background-color: #f8f9fa;
        }

        .gold-section .seats-container {
            background-color: #fff3e0;
        }

        .silver-section .seats-container {
            background-color: #f5f5f5;
        }
    </style>

    <script>
        const selectedSeats = {
            platinum: new Set(),
            gold: new Set(),
            silver: new Set()
        };

        let totalAmount = 0;

        document.querySelectorAll('.seat').forEach(seat => {
            seat.addEventListener('click', function() {
                if (this.classList.contains('occupied')) return;

                const section = this.closest('.seats-container');
                const seatType = section.dataset.type;
                const price = parseFloat(section.dataset.price);
                const seatId = `${this.dataset.row}${this.dataset.seat}`;

                if (this.classList.toggle('selected')) {
                    selectedSeats[seatType].add(seatId);
                    totalAmount += price;
                } else {
                    selectedSeats[seatType].delete(seatId);
                    totalAmount -= price;
                }

                updateSummary();
            });
        });

        function updateSummary() {
            ['platinum', 'gold', 'silver'].forEach(type => {
                const seatsSet = selectedSeats[type];
                const container = document.querySelector(`.seat-type.${type}`);
                const price = parseFloat(document.querySelector(`.${type}-section .seats-container`).dataset.price);

                container.querySelector('.seats-list').textContent =
                    seatsSet.size ? Array.from(seatsSet).sort().join(', ') : 'No seats selected';
                container.querySelector('.type-total').textContent =
                    seatsSet.size ? `₹${(seatsSet.size * price).toFixed(2)}` : '';
            });

            document.getElementById('total-price').textContent = totalAmount.toFixed(2);
            document.querySelector('.final-btn').textContent =
                totalAmount > 0 ? `Proceed - ₹${totalAmount.toFixed(2)}` : 'Proceed';
        }

        function proceedToPayment(eventId) {
            if (totalAmount === 0) {
                alert('Please select at least one seat to proceed.');
                return;
            }

            const selectedSeatsData = {
                platinum: Array.from(selectedSeats.platinum),
                gold: Array.from(selectedSeats.gold),
                silver: Array.from(selectedSeats.silver)
            };

            window.location.href = `booking-confirmation.php?event_id=${eventId}&seats=${encodeURIComponent(JSON.stringify(selectedSeatsData))}&amount=${totalAmount}`;
        }

        function simulateOccupiedSeats() {
            const occupyRandomSeats = (section, count) => {
                const seats = section.querySelectorAll('.seat');
                const availableIndices = Array.from({
                    length: seats.length
                }, (_, i) => i);

                for (let i = 0; i < count; i++) {
                    const randomIndex = Math.floor(Math.random() * availableIndices.length);
                    const seatIndex = availableIndices.splice(randomIndex, 1)[0];
                    seats[seatIndex].classList.add('occupied');
                }
            };

            occupyRandomSeats(document.querySelector('.platinum-section'), 10);
            occupyRandomSeats(document.querySelector('.gold-section'), 15);
            occupyRandomSeats(document.querySelector('.silver-section'), 20);
        }

        simulateOccupiedSeats();
    </script>
</body>

</html>