<?php
session_start();
require_once '../../config/database.php';
$base_assets_path = "../../assets";


function getCarouselItems($conn)
{
  $sql = "SELECT ci.*, e.title 
            FROM carousel_items ci 
            LEFT JOIN events e ON ci.id = e.category_id 
            ORDER BY ci.id ASC
            LIMIT 3";
  return $conn->query($sql);
}

function getUpcomingEvents($conn)
{
  $sql = "SELECT e.*, c.name as category_name 
            FROM events e 
            LEFT JOIN categories c ON e.id = c.id 
            WHERE e.status = 'upcoming' 
            ORDER BY e.event_datetime ASC 
            LIMIT 8";
  return $conn->query($sql);
}

$carousel_result = getCarouselItems($conn);
$events_result = getUpcomingEvents($conn);

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tickets on the go !!!</title>
  <link rel="stylesheet" href="./style.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300..400&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
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

  <div class="carousel-container">
    <div id="mainCarousel" class="carousel slide" data-ride="carousel">
      <div class="carousel-inner">
        <?php
        $index = 0;
        while ($carousel_items = $carousel_result->fetch_assoc()):
          $activeClass = $index === 0 ? ' active' : '';
        ?>
          <div class="carousel-item<?php echo $activeClass; ?>">
            <img src="<?php echo htmlspecialchars($base_assets_path . '/' . $carousel_items['image_url']); ?>" alt="Event Image" class="d-block w-100">
            <div class="carousel-caption d-none d-md-block">
              <h5><?php echo htmlspecialchars($carousel_items['title']); ?></h5>
            </div>
          </div>
          <?php $index++; ?>
        <?php endwhile; ?>
      </div>

      <!-- Carousel controls -->
      <a class="carousel-control-prev" href="#mainCarousel" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
      </a>
      <a class="carousel-control-next" href="#mainCarousel" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
      </a>

      <!-- Carousel indicators -->
      <div class="carousel-indicators">
        <?php
        for ($i = 0; $i < $index; $i++) {
          $activeClass = $i === 0 ? 'active' : '';
          echo '<li data-target="#mainCarousel" data-slide-to="' . $i . '" class="' . $activeClass . '"></li>';
        }
        ?>
      </div>
    </div>
  </div>

  <section class="upcoming-events">
    <h2>Upcoming Events</h2>
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
            <a href="event-details.php?id=<?php echo $event['id']; ?>" class="book-now">Book Now</a>
          </div>
        </div>

      <?php endwhile; ?>
    </div>
  </section>

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
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>