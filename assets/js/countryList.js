// Function to dynamically add the div and initialize the country select
function loadPassportCountryDiv() {
  const container = document.querySelector("#travelerFieldsContainer"); // Parent container

  // Check if the div already exists
  let passportDiv = document.querySelector(".passportCountrydiv");

  // Create the div only if it doesn't exist
  if (!passportDiv) {
    passportDiv = document.createElement("div");
    passportDiv.id = "passportCountrydiv";
    container.appendChild(passportDiv); // Append without replacing existing content
  } else {
    console.log("hello");
  }

  // Add the select field inside the div
  addPassportCountrySelect();
}

function addPassportCountrySelect() {
  const form = document.querySelector(".passportCountrydiv");
  if (form) {
    form.innerHTML = `<select class="form-select" id="passportCountry"><option>Select a country</option></select>`;
    populateCountries();
  } else {
    console.error("#passportCountrydiv not found in the DOM.");
  }
}

function populateCountries() {
  const select = document.querySelector("#passportCountry");
  if (select) {
    import("https://cdn.jsdelivr.net/npm/country-list-json@1.1.0/index.js")
      .then(({ countries }) => {
        countries.forEach((country) => {
          const option = document.createElement("option");
          option.value = country.code;
          option.textContent = `${country.flag} ${country.name}`;
          select.appendChild(option);
        });
      })
      .catch((error) => console.error("Failed to load country list:", error));
  } else {
    console.error("#passportCountry not found.");
  }
}

// Simulate dynamic div load
loadPassportCountryDiv();
