const setupFormValidation = (formID, submitUrl, successMessage, failureMessage) => {
    console.log("Setting up validation for form:", formID);

    const attachListener = () => {
        const form = document.getElementById(formID);
        if (!form) {
            console.warn(`Form with ID '${formID}' not found.`);
            return;
        }

        console.log("Form found. Attaching event listener.");

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            console.log("Form submitted.");

            if (!form.checkValidity()) {
                console.warn("Form is invalid.");
                form.classList.add('was-validated');
                return;
            }

            console.log("Form is valid. Preparing to send request.");

            const formDataObj = Object.fromEntries(new FormData(form));
            console.log("Form data:", formDataObj);

            try {
                const response = await fetch(submitUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'HU': hu // Ensure this variable is defined in your script
                    },
                    body: JSON.stringify(formDataObj)
                });

                console.log("Request made. Waiting for response...");

                if (!response.ok) {
                    throw new Error(`Server responded with status ${response.status}`);
                }

                const { success, error } = await response.json();
                console.log("Response received:", { success, error });

                Notiflix.Notify[success ? 'success' : 'failure'](success ? successMessage : error || failureMessage);
            } catch (err) {
                console.error("Fetch error:", err);
                Notiflix.Notify.failure(failureMessage);
            }
        });
    };

    if (document.readyState === 'loading') {
        console.log("Document still loading, waiting for DOMContentLoaded...");
        document.addEventListener('DOMContentLoaded', attachListener);
    } else {
        console.log("Document ready. Attaching listener immediately.");
        attachListener();
    }
};
