<?php
session_start();
require_once '../../config/database.php';
$base_assets_path = "../../assets";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tickets on the go !!!</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300..400&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
        .main {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.8rem 4rem;
            background: linear-gradient(90deg, #00b9f5 0%, #0088cc 100%);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .main-logo {
            font-size: 2.2rem;
            font-weight: 700;
            color: white;
            letter-spacing: -0.5px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .main-nav-list {
            display: flex;
            align-items: center;
            gap: 2rem;
            margin: 0;
            padding: 0;
        }

        .main-nav-item {
            list-style: none;
        }

        /* Common styles for nav icons */
        .nav-icon-btn {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            color: white;
            transition: all 0.2s ease;
        }

        .nav-icon-btn:hover {
            background: rgba(0, 0, 0, 0.3);
            border-color: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }

        /* City Selector Container */
        .city-selector {
            position: relative;
            display: flex;
            align-items: center;
        }

        /* Location Dropdown Button */
        .location-dropdown {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 100px;
            padding: 0.6rem 1.2rem;
            color: white;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            min-width: 160px;
        }

        .location-dropdown:hover {
            background: rgba(0, 0, 0, 0.3);
            border-color: rgba(255, 255, 255, 0.3);
            text-decoration: none;

        }

        /* Location Icon */
        .location-dropdown i.fa-map-marker-alt {
            font-size: 1.1rem;
            color: white;
        }

        /* Dropdown Arrow Icon */
        .location-dropdown i.fa-chevron-down {
            font-size: 0.8rem;
            margin-left: auto;
            transition: transform 0.2s ease;
        }

        /* City Name */
        .city-name {
            font-weight: 500;
            letter-spacing: 0.2px;
        }

        .city-dropdown-content {
            position: absolute;
            top: calc(100% + 0.5rem);
            left: 0;
            width: 100%;
            background: #1a1a1a;
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 0.5rem;
            display: none;
            z-index: 1000;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        /* Dropdown Items */
        .city-dropdown-content a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .city-dropdown-content a:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #0088cc;
            text-decoration: none;
        }

        /* Show dropdown when active */
        .city-selector.active .city-dropdown-content {
            display: block;
        }

        .city-selector.active .location-dropdown i.fa-chevron-down {
            transform: rotate(180deg);
        }

        .notification-icon {
            position: relative;
        }

        .notification-badge {
            position: absolute;
            top: -2px;
            right: -2px;
            width: 20px;
            height: 20px;
            background: #ff006e;
            border: 2px solid #00b9f5;
            border-radius: 50%;
            font-size: 0.7rem;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        /* Notification Dropdown */
        .notification-dropdown {
            position: absolute;
            top: calc(100% + 10px);
            right: -10px;
            width: 320px;
            background: #1a1a1a;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 1rem;
            display: none;
            z-index: 1000;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        .notification-dropdown.active {
            display: block;
        }

        .notification-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 0.8rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 0.8rem;
        }

        .notification-header h3 {
            color: white;
            font-size: 1rem;
            font-weight: 600;
            margin: 0;
        }

        .notification-header .mark-all-read {
            color: #00b9f5;
            font-size: 0.8rem;
            cursor: pointer;
        }

        .notification-list {
            max-height: 400px;
            overflow-y: auto;
        }

        .notification-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            padding: 0.8rem;
            border-radius: 8px;
            transition: background 0.2s ease;
            cursor: pointer;
        }

        .notification-item:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .notification-item.unread {
            background: rgba(0, 185, 245, 0.05);
        }

        .notification-content {
            flex: 1;
        }

        .notification-title {
            color: white;
            font-size: 0.9rem;
            margin-bottom: 0.3rem;
        }

        .notification-time {
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.8rem;
        }

        /* User Icon Styles */
        .user-icon {
            position: relative;
        }

        .user-dropdown {
            position: absolute;
            top: calc(100% + 10px);
            right: -10px;
            width: 240px;
            background: #1a1a1a;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 0.8rem;
            display: none;
            z-index: 1000;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        .user-dropdown.active {
            display: block;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.8rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 0.8rem;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .user-details {
            flex: 1;
        }

        .user-name {
            color: white;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 0.2rem;
        }

        .user-email {
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.8rem;
        }

        .user-menu-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .user-menu-item {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            padding: 0.8rem;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: background 0.2s ease;
            cursor: pointer;
        }

        .user-menu-item:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .user-menu-item i {
            font-size: 1.1rem;
            color: rgba(255, 255, 255, 0.7);
        }

        /* Dropdown arrow */
        .notification-dropdown::before,
        .user-dropdown::before {
            content: "";
            position: absolute;
            top: -6px;
            right: 20px;
            width: 12px;
            height: 12px;
            background: #1a1a1a;
            border-left: 1px solid rgba(255, 255, 255, 0.1);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            transform: rotate(45deg);
        }

        /* Notification indicator */
        .main-nav-link.has-notifications::after {
            content: "";
            position: absolute;
            top: 5px;
            right: 5px;
            width: 8px;
            height: 8px;
            background: #ff006e;
            border-radius: 50%;
            border: 2px solid #00b9f5;
        }

        /* Search Bar Styles */
        .search-bar {
            margin: 2rem auto;
            max-width: 800px;
            display: flex;
            gap: 0.8rem;
            padding: 0 2rem;
            position: relative;
        }

        .search-bar input {
            flex: 1;
            padding: 1rem 1.5rem;
            border: 2px solid #e0e0e0;
            border-radius: 50px;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .search-bar input:focus {
            outline: none;
            border-color: #00b9f5;
            box-shadow: 0 4px 20px rgba(0, 185, 245, 0.15);
        }

        .search-bar input::placeholder {
            color: #9e9e9e;
        }

        .search-bar button {
            padding: 1rem 2rem;
            border: none;
            background: #ff006e;
            color: white;
            border-radius: 50px;
            cursor: pointer;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(255, 0, 110, 0.2);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .search-bar button:hover {
            background: #e0005f;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(255, 0, 110, 0.3);
        }

        .search-bar button i {
            font-size: 1.2rem;
        }

        /* Category Navigation */
        .category-nav {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            padding: 1.2rem;
            background: #f8f9fa;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .category-nav a {
            text-decoration: none;
            color: #005d7a;
            font-weight: 500;
            padding: 0.6rem 1.2rem;
            border-radius: 25px;
            transition: all 0.3s ease;
            background: white;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }

        .category-nav a:hover {
            background: #ff006e;
            color: white;
            transform: translateY(-2px);
            text-decoration: none;
            box-shadow: 0 4px 12px rgba(255, 0, 110, 0.2);
        }
    </style>
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
                                <div class="user-name"><?php echo isset($_SESSION['name']) ? htmlspecialchars($_SESSION['name']) : 'Guest'; ?></div>
                                <div class="user-email"><?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : 'No Email'; ?></div>

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
                                <a href="./logout.php" class="user-menu-item">
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const citySelector = document.querySelector('.city-selector');
            const locationDropdown = document.querySelector('.location-dropdown');

            // Toggle dropdown
            locationDropdown.addEventListener('click', function(e) {
                e.stopPropagation();
                citySelector.classList.toggle('active');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function() {
                citySelector.classList.remove('active');
            });

            // Prevent dropdown from closing when clicking inside it
            document.querySelector('.city-dropdown-content').addEventListener('click', function(e) {
                e.stopPropagation();
            });

            // Update selected city
            document.querySelectorAll('.city-dropdown-content a').forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    const cityName = this.textContent;
                    document.querySelector('.city-name').textContent = cityName;
                    citySelector.classList.remove('active');
                });
            });
        });
        // Search functionality
        document.getElementById('search-input').addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                const searchQuery = this.value.trim();
                if (searchQuery) {
                    window.location.href = `search.php?q=${encodeURIComponent(searchQuery)}`;
                }
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Handle notification dropdown
            const notificationBtn = document.querySelector('.notification-icon .nav-icon-btn');
            const notificationDropdown = document.querySelector('.notification-dropdown');

            notificationBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                notificationDropdown.classList.toggle('active');
                userDropdown.classList.remove('active'); // Close user dropdown
            });

            // Handle user dropdown
            const userBtn = document.querySelector('.user-icon .nav-icon-btn');
            const userDropdown = document.querySelector('.user-dropdown');

            userBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                userDropdown.classList.toggle('active');
                notificationDropdown.classList.remove('active'); // Close notification dropdown
            });

            // Close dropdowns when clicking outside
            document.addEventListener('click', function() {
                notificationDropdown.classList.remove('active');
                userDropdown.classList.remove('active');
            });

            // Prevent dropdowns from closing when clicking inside them
            [notificationDropdown, userDropdown].forEach(dropdown => {
                dropdown.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            });

            // Handle mark all as read
            document.querySelector('.mark-all-read').addEventListener('click', function() {
                document.querySelectorAll('.notification-item.unread')
                    .forEach(item => item.classList.remove('unread'));
            });
        });
    </script>
</body>

</html>