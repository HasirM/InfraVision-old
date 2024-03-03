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

    retakeBtn.addEventListener('click', () => {
        confirmationContainer.classList.add('hidden');
        homeTitle.classList.remove('hidden');
        openCameraBtn.classList.remove('hidden');
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
                    location.textContent = `Your current address: ${address}`;
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

    submitReportBtn.addEventListener('click', () => {
        const report = {
            image: capturedImage.src,
            location: location.textContent,
            info: additionalInfo.value,
            duration: damageDuration.value,
            severity: damageSeverity.value,
            landmark: landmark.value
        };
        // Submit report to server or localStorage
        console.log('Report submitted:', report);
        // Reset form
        capturedImage.src = '';
        additionalInfo.value = '';
        damageDuration.value = '';
        damageSeverity.value = '';
        landmark.value = '';
        reportForm.classList.add('hidden');
        submitConfirmation.classList.remove('hidden'); // Show confirmation message
        setTimeout(() => {
            submitConfirmation.classList.add('hidden'); // Hide confirmation message after a delay
            viewReportPage.classList.remove('hidden'); // Show detailed view of submitted report
            reportDetails.innerHTML = `
                <img src="${report.image}" alt="Report Image">
                <p>Location: ${report.location}</p>
                <p>Additional Information: ${report.info}</p>
                <p>Duration of Damage: ${report.duration}</p>
                <p>Severity of Damage: ${report.severity}</p>
                <p>Landmark: ${report.landmark}</p>
            `;
        }, 2000); // Adjust delay as needed (in milliseconds)
    });

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
