document.addEventListener('DOMContentLoaded', () => {
    const openCameraBtn = document.getElementById('openCameraBtn');
    const homeTitle = document.getElementById('homeTitle');
    const cameraContainer = document.getElementById('cameraContainer');
    const cameraFeed = document.getElementById('cameraFeed');
    const captureBtn = document.getElementById('captureBtn');
    const confirmationContainer = document.getElementById('confirmationContainer');
    const capturedImage = document.getElementById('capturedImage');
    const retakeBtn = document.getElementById('retakeBtn');
    const proceedBtn = document.getElementById('proceedBtn');
    const reportForm = document.getElementById('reportForm');
    const capturedImageContainer = document.getElementById('capturedImageContainer');
    const retakeImageBtn = document.getElementById('retakeImageBtn');
    const additionalInfo = document.getElementById('additionalInfo');
    const damageDuration = document.getElementById('damageDuration');
    const damageSeverity = document.getElementById('damageSeverity');
    const landmark = document.getElementById('landmark');
    const submitReportBtn = document.getElementById('submitReportBtn');
    const submitConfirmation = document.getElementById('submitConfirmation');
    const viewReportPage = document.getElementById('viewReportPage');
    const reportDetails = document.getElementById('reportDetails');
    const date = document.getElementById('date');
    const location = document.getElementById('location');
    const backBtn = document.getElementById('backBtn');

    // Function to get today's date
    function getCurrentDate() {
        const currentDate = new Date();
        return currentDate.toDateString();
    }

    openCameraBtn.addEventListener('click', () => {
        homeTitle.classList.add('hidden');
        openCameraBtn.classList.add('hidden');
        cameraContainer.classList.remove('hidden');
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(stream => {
                cameraFeed.srcObject = stream;
                cameraFeed.play();
                captureBtn.classList.remove('hidden');
            })
            .catch(err => {
                console.error('Error accessing camera:', err);
            });
    });

    captureBtn.addEventListener('click', () => {
        const canvas = document.createElement('canvas');
        canvas.width = cameraFeed.videoWidth;
        canvas.height = cameraFeed.videoHeight;
        canvas.getContext('2d').drawImage(cameraFeed, 0, 0, canvas.width, canvas.height);
        capturedImage.src = canvas.toDataURL('image/png');
        cameraFeed.srcObject.getTracks().forEach(track => track.stop()); // Stop camera feed
        cameraContainer.classList.add('hidden');
        confirmationContainer.classList.remove('hidden');
    });

  // Assuming retakeBtn is the retake button element
retakeBtn.addEventListener('click', () => {
    // Hide the confirmation section
    confirmationContainer.classList.add('hidden');
    // Show the capture image section
    cameraContainer.classList.remove('hidden');
    // Reset the captured image src to clear the previous image
    capturedImage.src = '';

    // Request access to the camera again
    navigator.mediaDevices.getUserMedia({ video: true })
        .then(stream => {
            cameraFeed.srcObject = stream;
            cameraFeed.play();
            captureBtn.classList.remove('hidden');
        })
        .catch(err => {
            console.error('Error accessing camera:', err);
        });
});
    proceedBtn.addEventListener('click', () => {
        confirmationContainer.classList.add('hidden');
        reportForm.classList.remove('hidden');
        date.textContent = getCurrentDate();
        navigator.geolocation.getCurrentPosition(position => {
            const latitude = position.coords.latitude;
            const longitude = position.coords.longitude;
            const apiUrl = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${latitude}&lon=${longitude}`;
        
            fetch(apiUrl)
                .then(response => response.json())
                .then(data => {
                    const address = data.display_name;
                    location.textContent = `${address}`;
                })
                .catch(error => {
                    console.error('Error:', error);
                    location.textContent = 'Error fetching address';
                });
        }, error => {
            console.error('Error getting location:', error);
            location.textContent = 'Error getting location';
        });
        
    });

    retakeImageBtn.addEventListener('click', () => {
        confirmationContainer.classList.remove('hidden');
        reportForm.classList.add('hidden');
    });
    
    // Function to show the notification box with message
function showNotification(message) {
    const notificationBox = document.getElementById('notificationBox');
    const notificationContent = document.getElementById('notificationContent');
    const notificationButton = document.getElementById('notificationButton');

    notificationContent.textContent = message;
    notificationBox.classList.remove('hidden');

    // Add event listener to the notification button to hide the box when clicked
    notificationButton.addEventListener('click', () => {
        notificationBox.classList.add('hidden');
    });
}


submitReportBtn.addEventListener('click', (event) => {
    // Prevent the default form submission action
    event.preventDefault();

    // Retrieve data from the form fields
    const additionalInfo = document.getElementById('additionalInfo').value.trim();
    const damageDuration = document.getElementById('damageDuration').value.trim();
    const damageSeverity = document.getElementById('damageSeverity').value.trim();
    const image = document.getElementById('capturedImage').src;
    const location = document.getElementById('location').textContent;
    const landmark = document.getElementById('landmark').value.trim();

    // Check if all required fields are filled
    if (!additionalInfo) {
        displayErrorMessage('Please fill in the additional information field.');
        return;
    }

    if (!damageDuration) {
        displayErrorMessage('Please fill in the duration of damage field.');
        return;
    }

    if (!damageSeverity) {
        displayErrorMessage('Please fill in the severity of damage field.');
        return;
    }

    if (!landmark) {
        displayErrorMessage('Please fill in the landmark field.');
        return;
    }

    // Check if location details are fetched
    if (!location) {
        displayErrorMessage('Location details are not fetched. Please try again.');
        return;
    }

    // Hide error message if all fields are filled
    hideErrorMessage();

    // Prepare the report object
    const report = {
        info: additionalInfo,
        duration: damageDuration,
        severity: damageSeverity,
        image: image,
        location: location,
        landmark: landmark
    };

    // Send the report data to the server
    fetch('submit_report.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(report)
    })
    .then(response => {
        if (response.ok) {
            // Report submitted successfully
            showModal('Report submitted successfully!', 'view_report.php');
        } else {
            // Error handling
            showModal('Error submitting report. Please try again.', 'home.php');
        }
    })
    .catch(error => {
        console.error('Error submitting report:', error.message);
        // Display an error message to the user
        showModal('Error submitting report. Please try again.');
    });
});

function displayErrorMessage(message) {
    const errorMessage = document.getElementById('errorMessage');
    errorMessage.textContent = message;
    errorMessage.classList.remove('hidden');
}

function hideErrorMessage() {
    const errorMessage = document.getElementById('errorMessage');
    errorMessage.classList.add('hidden');
}

    
    // Function to display modal with message
    function showModal(message, redirectTo) {
        const modal = document.getElementById('modal');
        const modalContent = document.getElementById('modal-content');
        const modalMessage = document.getElementById('modal-message');
    
        // Set message content
        modalMessage.textContent = message;
    
        // Show modal
        modal.style.display = 'block';
    
        // Close modal after 3 seconds
        setTimeout(() => {
            modal.style.display = 'none';
            window.location.href = redirectTo;
        }, 3000);
    }
    
    // Close modal if clicked outside
    window.onclick = function(event) {
        const modal = document.getElementById('modal');
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }

    


    const homeBtn = document.getElementById('homeBtn');

homeBtn.addEventListener('click', () => {
    viewReportPage.classList.add('hidden');
    homeTitle.classList.remove('hidden');
    openCameraBtn.classList.remove('hidden');
});


    backBtn.addEventListener('click', () => {
        if (!cameraContainer.classList.contains('hidden')) {
            cameraContainer.classList.add('hidden');
            homeTitle.classList.remove('hidden');
            openCameraBtn.classList.remove('hidden');
        } else if (!confirmationContainer.classList.contains('hidden')) {
            confirmationContainer.classList.add('hidden');
            cameraContainer.classList.remove('hidden');
        } else if (!reportForm.classList.contains('hidden')) {
            reportForm.classList.add('hidden');
            confirmationContainer.classList.remove('hidden');
        } else if (!viewReportPage.classList.contains('hidden')) {
            viewReportPage.classList.add('hidden');
            reportForm.classList.remove('hidden');
        }
    });
});
