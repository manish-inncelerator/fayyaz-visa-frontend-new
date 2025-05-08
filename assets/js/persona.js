    const visaPax = <?php echo json_encode($visaPax['no_of_pax']); ?>; // Assuming you have no_of_pax available

    let travelerCount = 0;

    // Initialize form with initial traveler count based on visaPax['no_of_pax']
    document.addEventListener('DOMContentLoaded', function() {
        for (let i = 0; i < visaPax; i++) {
            addTravelerForm();
        }
    });

    // Add a new traveler form field
    document.getElementById('addTravelerBtn').addEventListener('click', function() {
        if (travelerCount < visaPax) {
            addTravelerForm();
        } else {
            alert("You've added all travelers.");
        }
    });

    // Function to add a traveler form field
    function addTravelerForm() {
        travelerCount++;
        const container = document.getElementById('travelerFieldsContainer');
        
        const travelerDiv = document.createElement('div');
        travelerDiv.classList.add('traveler');
        travelerDiv.classList.add('mb-3');
        
        travelerDiv.innerHTML = `
            <h5>Traveler ${travelerCount}</h5>
            <div class="mb-2">
                <label for="name_${travelerCount}" class="form-label">Name</label>
                <input type="text" class="form-control" id="name_${travelerCount}" name="name_${travelerCount}" required>
            </div>
            <div class="mb-2">
                <label for="passport_${travelerCount}" class="form-label">Passport Number</label>
                <input type="text" class="form-control" id="passport_${travelerCount}" name="passport_${travelerCount}" required>
            </div>
            <div class="mb-2">
                <label for="dob_${travelerCount}" class="form-label">Date of Birth</label>
                <input type="date" class="form-control" id="dob_${travelerCount}" name="dob_${travelerCount}" required>
            </div>
            <button type="button" class="btn btn-danger removeTravelerBtn" data-index="${travelerCount}">Remove Traveler</button>
        `;
        
        // Append the new traveler form to the container
        container.appendChild(travelerDiv);

        // Add remove traveler event
        document.querySelector(`[data-index="${travelerCount}"]`).addEventListener('click', function() {
            travelerDiv.remove();
            travelerCount--;
        });
    }