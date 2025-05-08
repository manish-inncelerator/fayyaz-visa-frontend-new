function initiateTruecallerLogin() {
  let req_nonce = Date.now().toString(); // Random nonce (timestamp-based)
  let partnerKey = "cpn7c972ab8b016854e01a7e3e1388d86a0ba"; // Replace with your Truecaller App Key
  let partnerName = "fayyaz_travels_auth"; // Replace with your app name
  let language = "en"; // Language locale (e.g., 'en', 'hi')
  let title = "Login with Truecaller"; // Optional title
  let skipOption = "Use another method"; // Footer text (optional)

  // Construct Truecaller SDK URL
  let truecallerURL = `truecallersdk://truesdk/web_verify?requestNonce=${req_nonce}&partnerKey=${partnerKey}&partnerName=${partnerName}&lang=${language}&title=${title}&skipOption=${skipOption}`;

  // Redirect user to Truecaller login
  window.location.href = truecallerURL;
}
