// Attach to form submission
document
  .getElementById("subsForm")
  .addEventListener("submit", function (event) {
    event.preventDefault();
    const email = document.getElementById("email-newsletter").value;

    async function subscribeEmail(email) {
      try {
        const response = await fetch("api/v1/hitMailchimp.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({ email: email }),
        });

        const responseData = await response.json();

        if (response.ok && responseData.status === "subscribed") {
          alert("Subscription successful!");
          document.getElementById("subsForm").reset();
        } else if (responseData.title === "Member Exists") {
          alert("You are already subscribed.");
        } else {
          alert(
            "Status: " +
              responseData.status +
              "\nTitle: " +
              responseData.title +
              "\nDetail: " +
              (responseData.detail || "Unknown error")
          );
        }
      } catch (error) {
        alert("An error occurred: " + error);
      }
    }

    if (email) {
      subscribeEmail(email);
    } else {
      alert("Please enter a valid email address.");
    }
  });
