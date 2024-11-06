<?php
session_start();
require_once '../../config/database.php';
$base_assets_path = "../../assets";

$category_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($category_id <= 0) {
    header("Location: index.php");
    exit();
}

$category = null;
$events = [];

try {
    $category_sql = "SELECT * FROM categories WHERE id = ?";
    if ($stmt = $conn->prepare($category_sql)) {
        $stmt->bind_param("i", $category_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $category = $result->fetch_assoc();
            $category_name = $category['name'];
            $category_desc = $category['description'];
        } else {
            // Category not found
            header("Location: index.php");
            exit();
        }
        $stmt->close();
    } else {
        throw new Exception("Failed to prepare category query");
    }

    if ($category) {
        $events_sql = "SELECT e.*, c.name as category_name 
                      FROM events e 
                      JOIN categories c ON e.category_id = c.id 
                      WHERE e.category_id = ? 
                      ORDER BY e.event_datetime ASC";

        if ($stmt = $conn->prepare($events_sql)) {
            $stmt->bind_param("i", $category_id);
            $stmt->execute();
            $events_result = $stmt->get_result();
            $stmt->close();
        } else {
            throw new Exception("Failed to prepare events query");
        }
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    header("Location: ./index.php");
    exit();
}

if (!$category) {
    header("Location: ./index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo htmlspecialchars($category['name']); ?> - ShowBazaar</title>

    <link rel="stylesheet" href="./style.css" />
    <link rel="stylesheet" href="../../assets/css/category-events.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300..400&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
</head>

<body>
    <header class="main">
        <div class="main-logo">ShowBazaar</div>
        <nav>
            <ul class="main-nav-list">
                <li class="main-nav-item city-selector">
                    <button class="location-dropdown">
                        <i class="fas fa-map-marker-alt"></i>
                        <span class="city-name">Mumbai</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="city-dropdown-content">
                        <a href="#"><i class="fas fa-map-marker-alt"></i>Mumbai</a>
                        <a href="#"><i class="fas fa-map-marker-alt"></i>Delhi</a>
                        <a href="#"><i class="fas fa-map-marker-alt"></i>Bangalore</a>
                        <a href="#"><i class="fas fa-map-marker-alt"></i>Chennai</a>
                        <a href="#"><i class="fas fa-map-marker-alt"></i>Nagpur</a>
                        <a href="#"><i class="fas fa-map-marker-alt"></i>Pune</a>
                        <a href="#"><i class="fas fa-map-marker-alt"></i>Hyderabad</a>
                        <a href="#"><i class="fas fa-map-marker-alt"></i>Kochi</a>
                        <a href="#"><i class="fas fa-map-marker-alt"></i>Kolkata</a>
                        <a href="#"><i class="fas fa-map-marker-alt"></i>Jaipur</a>
                    </div>
                </li>
                <!-- Notification Icon -->
                <li class="main-nav-item notification-icon">
                    <button class="nav-icon-btn">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge">3</span>
                    </button>
                    <div class="notification-dropdown">
                        <div class="notification-header">
                            <h3>Notifications</h3>
                            <span class="mark-all-read">Mark all as read</span>
                        </div>
                        <div class="notification-list">
                            <div class="notification-item unread">
                                <div class="notification-content">
                                    <div class="notification-title">New event added in Mumbai</div>
                                    <div class="notification-time">2 hours ago</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>

                <!-- User Icon -->
                <li class="main-nav-item user-icon">
                    <button class="nav-icon-btn">
                        <i class="fas fa-user"></i>
                    </button>
                    <div class="user-dropdown">
                        <div class="user-info">
                            <div class="user-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="user-details">
                                <div class="user-name">John Doe</div>
                                <div class="user-email">john@example.com</div>
                            </div>
                        </div>
                        <ul class="user-menu-list">
                            <li>
                                <a href="#" class="user-menu-item">
                                    <i class="fas fa-ticket"></i>
                                    My Bookings
                                </a>
                            </li>
                            <li>
                                <a href="#" class="user-menu-item">
                                    <i class="fas fa-gear"></i>
                                    Settings
                                </a>
                            </li>
                            <li>
                                <a href="#" class="user-menu-item">
                                    <i class="fas fa-sign-out"></i>
                                    Logout
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </nav>
    </header>

    <div class="search-bar">
        <input type="text" id="search-input" placeholder="Search for movies, sports events, or plays..." />
        <button><i class="fa-solid fa-magnifying-glass"></i></button>
    </div>

    <nav class="category-nav">
        <?php
        $categories_sql = "SELECT * FROM categories ORDER BY name";
        $categories_result = $conn->query($categories_sql);
        while ($category = $categories_result->fetch_assoc()) {
            echo '<a href="category-events.php?id=' . $category['id'] . '">' . htmlspecialchars($category['name']) . '</a>';
        }
        ?>
    </nav>


    <div class="category-banner">
        <h1><?php echo htmlspecialchars($category_name); ?></h1>
        <p><?php echo htmlspecialchars($category_desc ?? 'Explore amazing events in this category'); ?></p>
    </div>

    <section class="category-events">
        <div class="container">
            <?php if (isset($events_result) && $events_result->num_rows > 0): ?>
                <div class="event-grid">
                    <?php while ($event = $events_result->fetch_assoc()): ?>
                        <div class="event-card">
                            <img src="<?php echo htmlspecialchars($base_assets_path . "/" . $event['image_url'], ENT_QUOTES, 'UTF-8'); ?>"
                                alt="<?php echo htmlspecialchars($event['title'], ENT_QUOTES, 'UTF-8'); ?>" />
                            <div class="event-details">
                                <h3><?php echo htmlspecialchars($event['title']); ?></h3>
                                <p class="category"><?php echo htmlspecialchars($event['category_name']); ?></p>
                                <p class="datetime">
                                    <?php
                                    $event_date = new DateTime($event['event_datetime']);
                                    echo $event_date->format('D, M j, Y - g:i A');
                                    ?>
                                </p>
                                <p class="venue"><?php echo htmlspecialchars($event['venue']); ?></p>
                                <div class="price-range">
                                    <span>₹<?php echo number_format($event['price_silver']); ?></span> -
                                    <span>₹<?php echo number_format($event['price_platinum']); ?></span>
                                </div>
                                <a href="tickets.php?id=<?php echo $category_id; ?>&event_id=<?php echo $event['id']; ?>" class="book-now">Book Now</a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="no-events">
                    <i class="fas fa-calendar-times"></i>
                    <h2>No upcoming events</h2>
                    <p>There are currently no upcoming events in <?php echo htmlspecialchars($category_name); ?>. Please check back later!</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <?php include './footer.php'; ?>

    <script src="../../assets/js/app.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
</body>

</html>