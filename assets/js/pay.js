// Flywire Payment Configuration
function pay() {
  const amount = "123";
  const currency = "SGD";

  // Flywire configuration object
  var config = {
    // Set the target environment (demo, prod)
    env: "prod",

    // Set your unique code (may differ between demo and prod)
    recipientCode: "FTZ",

    // Set the amount of the payment to be processed
    amount: 1234.56,

    // Recommended (not required) validation error handler
    onInvalidInput: function (errors) {
      errors.forEach(function (error) {
        alert(error.msg);
      });
    },

    // Display payer and custom field input boxes
    requestPayerInfo: true,
    requestRecipientInfo: true,

    // Set the return URL where payers are redirected to on completion
    returnUrl: "https://httpbin.org/get",
  };

  // Initialize Flywire Payment
  const modal = window.FlywirePayment.initiate(config);
  modal.render();
}
