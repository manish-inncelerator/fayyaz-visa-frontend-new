document.addEventListener("DOMContentLoaded", function () {
  // API Configuration
  const apiKey = "e3eca44724e54247a67101652252104";
  const country = "<?= htmlspecialchars($country_name); ?>";

  // Temperature unit state
  let isCelsius = true;

  // City mapping for major destinations
  const cityMap = {
    Philippines: "Manila",
    India: "New Delhi",
    "United Kingdom": "London",
    Singapore: "Singapore",
    Australia: "Sydney",
    "United Arab Emirates": "Dubai",
    "United States": "New York",
  };

  const city = cityMap[country] || "Sydney";

  // Temperature conversion functions
  const celsiusToFahrenheit = (c) => (c * 9) / 5 + 32;
  const fahrenheitToCelsius = (f) => ((f - 32) * 5) / 9;

  // Fetch weather data
  fetch(
    `https://api.weatherapi.com/v1/forecast.json?key=${apiKey}&q=${encodeURIComponent(
      city
    )}&days=1&aqi=no&alerts=no`
  )
    .then((response) => {
      if (!response.ok) throw new Error(`API error: ${response.status}`);
      return response.json();
    })
    .then((data) => {
      // Extract weather data
      const current = data.current;
      const forecast = data.forecast.forecastday[0].astro;
      const location = data.location;

      const weatherData = {
        temp_c: current.temp_c,
        temp_f: current.temp_f,
        condition: current.condition.text,
        icon: current.condition.icon,
        wind: current.wind_kph,
        humidity: current.humidity,
        sunrise: forecast.sunrise,
        sunset: forecast.sunset,
        city: location.name,
        country: location.country,
      };

      // Get DOM elements
      const elements = {
        info: document.getElementById("weather-info"),
        icon: document.getElementById("weather-icon"),
        extra: document.getElementById("weather-extra"),
      };

      // Function to update temperature display
      function updateTempDisplay() {
        const temp = isCelsius ? weatherData.temp_c : weatherData.temp_f;
        const unit = isCelsius ? "°C" : "°F";
        return `${Math.round(temp)}${unit}`;
      }

      // Function to toggle temperature unit
      function toggleTemperature(unit) {
        isCelsius = unit === "C";
        const tempElement = document.querySelector(".temp");
        if (tempElement) {
          tempElement.textContent = updateTempDisplay();
        }

        // Update button states
        const buttons = document.querySelectorAll(".unit-toggle");
        buttons.forEach((btn) => {
          btn.classList.toggle("active", btn.textContent.includes(unit));
        });
      }

      // Update weather display
      if (elements.info) {
        elements.info.innerHTML = `
          <div class="weather-card">
            <div class="weather-header">
              <div class="location-info">
                <i class="bi bi-pin-map-fill"></i>
                <div class="location-text">
                  <div class="city">${weatherData.city}</div>
                  <div class="country">${weatherData.country}</div>
                </div>
              </div>
              <div class="temp-switch">
                <button class="unit-toggle ${
                  isCelsius ? "active" : ""
                }" onclick="toggleTemperature('C')">°C</button>
                <button class="unit-toggle ${
                  !isCelsius ? "active" : ""
                }" onclick="toggleTemperature('F')">°F</button>
              </div>
            </div>
            <div class="weather-content">
              <div class="weather-primary">
                <img src="${weatherData.icon}" alt="${
          weatherData.condition
        }" class="weather-icon">
                <div class="weather-info">
                  <div class="temp">${updateTempDisplay()}</div>
                  <div class="condition">${weatherData.condition}</div>
                </div>
              </div>
            </div>
          </div>
        `;
      }

      if (elements.extra) {
        elements.extra.innerHTML = `
          <div class="weather-details">
            <div class="weather-stats">
              <div class="stat-item">
                <div class="stat-icon"><i class="bi bi-sunrise-fill"></i> <i class="bi bi-arrow-up"></i></div>
                <div class="stat-info">
                  <div class="stat-label">Sunrise</div>
                  <div class="stat-value">${weatherData.sunrise}</div>
                </div>
              </div>
              <div class="stat-item">
                <div class="stat-icon"><i class="bi bi-sunset-fill"></i> <i class="bi bi-arrow-down"></i></div>
                <div class="stat-info">
                  <div class="stat-label">Sunset</div>
                  <div class="stat-value">${weatherData.sunset}</div>
                </div>
              </div>
              <div class="stat-item">
                <div class="stat-icon"><i class="bi bi-wind"></i></div>
                <div class="stat-info">
                  <div class="stat-label">Wind Speed</div>
                  <div class="stat-value">${weatherData.wind} km/h</div>
                </div>
              </div>
              <div class="stat-item">
                <div class="stat-icon"><i class="bi bi-droplet-fill"></i></div>
                <div class="stat-info">
                  <div class="stat-label">Humidity</div>
                  <div class="stat-value">${weatherData.humidity}%</div>
                </div>
              </div>
            </div>
          </div>
        `;

        // Add enhanced CSS styles
        const style = document.createElement("style");
        style.textContent = `
          .weather-card {
            background: linear-gradient(120deg, #2193b0, #6dd5ed);
            border-radius: 25px;
            padding: 2rem;
            color: white;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.1);
          }

          .weather-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
          }

          .location-info {
            display: flex;
            align-items: center;
            gap: 1rem;
          }

          .location-info i {
            font-size: 1.8rem;
            color: rgba(255,255,255,0.9);
          }

          .location-text .city {
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 0.2rem;
          }

          .location-text .country {
            font-size: 0.9rem;
            opacity: 0.8;
          }

          .temp-switch {
            background: rgba(255,255,255,0.1);
            padding: 0.3rem;
            border-radius: 30px;
            display: flex;
            gap: 0.3rem;
          }

          .unit-toggle {
            background: transparent;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
          }

          .unit-toggle.active {
            background: white;
            color: #2193b0;
          }

          .weather-primary {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 3rem;
            padding: 2rem 0;
          }

          .weather-icon {
            width: 120px;
            height: 120px;
            filter: drop-shadow(0 0 15px rgba(255,255,255,0.4));
          }

          .weather-info .temp {
            font-size: 4rem;
            font-weight: 700;
            line-height: 1;
            margin-bottom: 0.5rem;
            text-shadow: 2px 2px 10px rgba(0,0,0,0.1);
          }

          .weather-info .condition {
            font-size: 1.2rem;
            opacity: 0.9;
          }

          .weather-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 2rem;
            margin-top: 2rem;
            background: rgba(255,255,255,0.1);
            padding: 2rem;
            border-radius: 20px;
            backdrop-filter: blur(5px);
          }

          .stat-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            border-radius: 15px;
            transition: all 0.3s ease;
          }

          .stat-item:hover {
            background: rgba(255,255,255,0.1);
            transform: translateY(-5px);
          }

          .stat-icon {
            background: rgba(255,255,255,0.2);
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
          }

          .stat-icon i {
            font-size: 1.5rem;
            color: #fff;
          }

          .stat-info .stat-label {
            font-size: 0.9rem;
            opacity: 0.8;
            margin-bottom: 0.3rem;
          }

          .stat-info .stat-value {
            font-size: 1.1rem;
            font-weight: 600;
          }

          @media (max-width: 768px) {
            .weather-stats {
              grid-template-columns: repeat(2, 1fr);
              gap: 1rem;
            }

            .weather-primary {
              flex-direction: column;
              gap: 1.5rem;
              text-align: center;
            }

            .weather-header {
              flex-direction: column;
              gap: 1rem;
              text-align: center;
            }

            .location-info {
              flex-direction: column;
              gap: 0.5rem;
            }
          }
        `;
        document.head.appendChild(style);
      }

      // Make toggleTemperature function globally accessible
      window.toggleTemperature = toggleTemperature;
    })
    .catch((error) => {
      console.error("Weather fetch error:", error);
      const weatherInfo = document.getElementById("weather-info");
      if (weatherInfo) {
        weatherInfo.innerHTML = `
          <div class="weather-error">
            <i class="bi bi-exclamation-triangle"></i>
            <p>Weather information unavailable</p>
          </div>
        `;
      }
    });
});
