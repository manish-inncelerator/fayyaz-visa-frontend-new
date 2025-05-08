// Fetch user's IP address and get country code
async function fetchUserCountryCode() {
  try {
    const response = await fetch("https://ipapi.co/json/");
    const data = await response.json();
    document.getElementById("userCountryCode").value =
      data.country_calling_code; // Set the country code in the hidden field
  } catch (error) {
    console.error("Error fetching IP info:", error);
  }
}

fetchUserCountryCode();
// Contact Form Submission Handler
document
  .getElementById("submitContactForm")
  .addEventListener("click", async function () {
    const form = document.getElementById("contactForm");
    const formData = new FormData(form); // Automatically get all fields from the form

    // Convert FormData to a plain object
    const dataObject = {};
    formData.forEach((value, key) => {
      dataObject[key] = value;
    });

    // Basic validation
    if (
      !dataObject.name ||
      !dataObject.email ||
      !dataObject.phone ||
      !dataObject.message
    ) {
      Notiflix.Notify.failure("Please fill in all required fields");
      return;
    }

    // Email validation
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(dataObject.email)) {
      Notiflix.Notify.failure("Please enter a valid email address");
      return;
    }

    // Phone validation
    if (!/^[0-9+\-\s()]{8,20}$/.test(dataObject.phone)) {
      Notiflix.Notify.failure("Please enter a valid phone number");
      return;
    }

    // Disable the button and show submitting message
    const submitButton = document.getElementById("submitContactForm");
    submitButton.disabled = true;
    submitButton.textContent = "Submitting...";

    try {
      const response = await fetch("api/v1/contact-for-schengen.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          HU: hu,
        },
        body: JSON.stringify(dataObject),
      });

      const data = await response.json();

      // Enable the button and reset text after response
      submitButton.disabled = false;
      submitButton.textContent = "Submit";

      if (response.ok) {
        Notiflix.Notify.success(data.success);
        // Close the modal
        const modal = bootstrap.Modal.getInstance(
          document.getElementById("contactUsModal")
        );
        modal.hide();
        // Reset the form
        form.reset();
      } else {
        Notiflix.Notify.failure(
          data.error || "An error occurred while submitting the form"
        );
      }
    } catch (error) {
      console.error("Error:", error);
      Notiflix.Notify.failure("An error occurred while submitting the form");
      // Enable the button and reset text on error
      submitButton.disabled = false;
      submitButton.textContent = "Submit";
    }
  });
