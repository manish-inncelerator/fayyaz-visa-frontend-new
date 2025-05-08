let cachedCountryData = null;
let pendingRequest = null; // Store the promise of the API call

function fetchCountryCode(callback) {
  if (cachedCountryData) {
    console.log("Using cached country data:", cachedCountryData);
    callback(cachedCountryData.countryCode);
    window.userCallingCode = cachedCountryData.callingCode;
    window.userCountryCode = cachedCountryData.countryCode;
    window.userCountryName = cachedCountryData.countryName;
    return;
  }

  if (pendingRequest) {
    // If a request is already in progress, wait for it to resolve
    pendingRequest.then(({ countryCode, callingCode, countryName }) => {
      callback(countryCode);
      window.userCallingCode = callingCode;
      window.userCountryCode = countryCode;
      window.userCountryName = countryName;
    });
    return;
  }

  // Create the API request and store it in pendingRequest
  pendingRequest = fetch(
    "https://api.ipgeolocation.io/ipgeo?apiKey=138fd07fd87d4637a1f61b85454bbb73"
  )
    .then((res) => {
      if (!res.ok) {
        throw new Error("Network response was not ok");
      }
      return res.json();
    })
    .then((data) => {
      const countryCode =
        typeof data.country_code2 === "string"
          ? data.country_code2.toLowerCase()
          : "sg"; // Ensure lowercase string

      const callingCode =
        data.calling_code && !isNaN(data.calling_code)
          ? `${data.calling_code}`
          : "+65"; // Ensure valid calling code with "+"

      const countryName =
        typeof data.country_name === "string" ? data.country_name : "Singapore"; // Default country name

      cachedCountryData = { countryCode, callingCode, countryName }; // Cache response
      pendingRequest = null; // Clear pending request

      callback(countryCode);
      window.userCallingCode = callingCode;
      window.userCountryCode = countryCode;
      window.userCountryName = countryName;

      return cachedCountryData;
    })
    .catch((error) => {
      console.error("Error fetching country and calling code:", error);
      cachedCountryData = {
        countryCode: "sg",
        callingCode: "+65",
        countryName: "Singapore",
      }; // Cache default values
      pendingRequest = null; // Clear pending request

      callback("sg");
      window.userCallingCode = "+65";
      window.userCountryCode = "sg";
      window.userCountryName = "Singapore";

      return cachedCountryData;
    });
}

document.addEventListener("DOMContentLoaded", function () {
  const mobileField = document.getElementById("mobile");
  const whatsappNumberField = document.getElementById("whatsappNumber");

  if (!mobileField || !whatsappNumberField) {
    console.error("One or more input fields not found in the DOM.");
    return;
  }

  // Initialize international phone input for mobile field
  const mobileIntl = intlTelInput(mobileField, {
    initialCountry: "auto",
    geoIpLookup: fetchCountryCode,
    separateDialCode: true,
    utilsScript:
      "https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.0/build/js/utils.js",
  });

  // Initialize international phone input for WhatsApp number field
  const whatsappIntl = intlTelInput(whatsappNumberField, {
    initialCountry: "auto",
    geoIpLookup: fetchCountryCode,
    separateDialCode: true,
    utilsScript:
      "https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.0/build/js/utils.js",
  });

  // Use calling code after fetch completes
  setTimeout(() => {
    console.log("Calling Code:", window.userCallingCode); // Example: "+91"
    console.log("Country Code:", window.userCountryCode); // Example: "in"
    console.log("Country Name:", window.userCountryName); // Example: "India"
  }, 1000);
});
