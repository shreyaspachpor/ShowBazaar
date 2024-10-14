const cityDropdown = document.getElementById("city-dropdown");

// Fetch city data from an external API or local JSON file
document.addEventListener("DOMContentLoaded", () => {
  fetch("/assets/data/cities.json") // Adjust the path to where your data is stored
    .then((response) => response.json())
    .then((cities) => {
      // Sort the cities before rendering them into the dropdown
      cities = cities.sort((a, b) => a.name.localeCompare(b.name)); // Sorting by city name

      cities.forEach((city) => {
        const option = document.createElement("option");
        option.value = city.value; // Assuming `city.value` is the city code or name
        option.textContent = city.name; // Assuming `city.name` is the display name
        cityDropdown.appendChild(option);
      });
    })
    .catch((error) => console.error("Error fetching city data:", error));
});


// Carousel logic
const carousel = document.querySelector(".carousel");
const items = document.querySelectorAll(".carousel-item");
const buttonLeft = document.querySelector(".carousel-button-left");
const buttonRight = document.querySelector(".carousel-button-right");
const dotsContainer = document.querySelector(".carousel-dots");

let currentIndex = 0;
let intervalId;

items.forEach((_, index) => {
  const dot = document.createElement("div");
  dot.classList.add("carousel-dot");
  dot.addEventListener("click", () => {
    currentIndex = index;
    updateCarousel();
  });
  dotsContainer.appendChild(dot);
});

const dots = document.querySelectorAll(".carousel-dot");

buttonLeft.addEventListener("click", () => {
  currentIndex = (currentIndex - 1 + items.length) % items.length;
  updateCarousel();
});

buttonRight.addEventListener("click", () => {
  currentIndex = (currentIndex + 1) % items.length;
  updateCarousel();
});

function updateCarousel() {
  carousel.style.transform = `translateX(-${currentIndex * 100}%)`;
  dots.forEach((dot, index) => {
    dot.classList.toggle("active", index === currentIndex);
  });
  resetAutoplay();
}

function autoplay() {
  currentIndex = (currentIndex + 1) % items.length;
  updateCarousel();
}

function resetAutoplay() {
  clearInterval(intervalId);
  intervalId = setInterval(autoplay, 5000);
}

resetAutoplay();

carousel.addEventListener("mouseenter", () => clearInterval(intervalId));
carousel.addEventListener("mouseleave", resetAutoplay);
