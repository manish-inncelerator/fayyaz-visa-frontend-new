const requestOptions = {
  method: "GET",
  redirect: "follow",
};

fetch(
  "https://api.ipgeolocation.io/ipgeo?apiKey=138fd07fd87d4637a1f61b85454bbb73",
  requestOptions
)
  .then((response) => response.json()) // Parse the response as JSON
  .then((result) => {
    // Extract the required fields from the JSON response
    const country = result.country_name; // Country name
    const callingCode = result.calling_code; // Calling code
    const state = result.state_prov; // State/Province
    const city = result.city; // City
    const ip = result.ip; // City

    // Get the elements to display the values
    const callingCodeElement = document.getElementById("countryCode");
    const userIP = document.getElementById("userIP");
    const stateName = document.getElementById("stateName");
    const cityName = document.getElementById("cityName");
    const countryName = document.getElementById("countryName");

    //   Display the values
    callingCodeElement.innerHTML = callingCode;
    stateName.value = state;
    cityName.value = city;
    countryName.value = country;
    userIP.value = ip;
  })
  .catch((error) => console.error("Error:", error));
