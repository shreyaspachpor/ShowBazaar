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

function showCounter(btn) {
  btn.style.display = "none";
  btn.nextElementSibling.style.display = "flex";
}

function incrementCount(btn) {
  let countSpan = btn.previousElementSibling;
  let count = parseInt(countSpan.textContent);
  countSpan.textContent = count + 1;
}

function decrementCount(btn) {
  let countSpan = btn.nextElementSibling;
  let count = parseInt(countSpan.textContent);
  if (count > 1) {
    countSpan.textContent = count - 1;
  } else {
    let counterDiv = btn.parentElement;
    counterDiv.style.display = "none";
    counterDiv.previousElementSibling.style.display = "inline-block";
  }
}
