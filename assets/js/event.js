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
