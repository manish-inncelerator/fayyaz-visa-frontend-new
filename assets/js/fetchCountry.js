let cachedCountryData = null;
let pendingRequest = null;

function myCountryCode(callback) {
  if (cachedCountryData) {
    // If data is already cached, return it immediately
    return callback(
      cachedCountryData.countryCode,
      cachedCountryData.callingCode
    );
  }

  if (!pendingRequest) {
    pendingRequest = fetch(
      "https://api.ipgeolocation.io/ipgeo?apiKey=138fd07fd87d4637a1f61b85454bbb73"
    )
      .then((res) => res.json())
      .then((data) => {
        const countryCode =
          typeof data.country_code2 === "string"
            ? data.country_code2.toLowerCase()
            : "sg";
        const callingCode =
          data.calling_code && !isNaN(data.calling_code)
            ? `${data.calling_code}`
            : "+65";

        cachedCountryData = { countryCode, callingCode }; // Cache response
        window.userCallingCode = callingCode;

        return cachedCountryData;
      })
      .catch(() => {
        cachedCountryData = { countryCode: "us", callingCode: "+65" };
        return cachedCountryData;
      });
  }

  pendingRequest.then((data) => callback(data.countryCode, data.callingCode));
}
