document.addEventListener("DOMContentLoaded", function () {
  function initializeIntlTelInput() {
    const mobileField = document.getElementById("mobile_home");

    if (!mobileField) {
      console.warn("Element #mobile_home not found. Retrying...");
      return;
    }

    function fetchCountryCode(callback) {
      fetch(
        "https://api.ipgeolocation.io/ipgeo?apiKey=138fd07fd87d4637a1f61b85454bbb73"
      )
        .then((res) => res.json())
        .then((data) => {
          const countryCode =
            typeof data.country_code2 === "string"
              ? data.country_code2.toLowerCase()
              : "sg"; // Ensure lowercase string

          const callingCode =
            data.calling_code && !isNaN(data.calling_code)
              ? `${data.calling_code}`
              : "+65"; // Ensure valid calling code with "+"

          cachedCountryData = { countryCode, callingCode }; // Cache response
          pendingRequest = null; // Clear pending request

          callback(countryCode);
          window.userCallingCode = callingCode;

          return cachedCountryData;
        })
        .catch(() => callback("us"));
    }

    intlTelInput(mobileField, {
      initialCountry: "auto",
      geoIpLookup: fetchCountryCode,
      separateDialCode: true,
      utilsScript:
        "https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.0/build/js/utils.js",
    });
  }

  // Bootstrap Popover Event Listener
  document.body.addEventListener("shown.bs.popover", function () {
    setTimeout(initializeIntlTelInput, 300); // Delay to allow the popover to render
  });
});
