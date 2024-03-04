// Function to start QR code scanning
function startQRCodeScanner() {
    // Check if the browser supports getUserMedia
    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
        alert('Your browser does not support accessing the camera for scanning QR codes.');
        return;
    }

    // Access the camera stream
    navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } })
        .then(function (stream) {
            // Get the video element
            var videoElement = document.createElement('video');
            videoElement.setAttribute('playsinline', '');
            videoElement.srcObject = stream;

            // Add the video element to the DOM
            document.body.appendChild(videoElement);

            // Play the video to start the camera preview
            videoElement.play();

            // Start scanning for QR codes
            scanQRCode(videoElement);
        })
        .catch(function (error) {
            console.error('Error accessing camera:', error);
            alert('Failed to access the camera for scanning QR codes. Please check your camera permissions and try again.');
        });
}

// Function to stop QR code scanning
function stopQRCodeScanner() {
    // Stop the camera stream
    var videoElements = document.querySelectorAll('video');
    videoElements.forEach(function (videoElement) {
        var stream = videoElement.srcObject;
        var tracks = stream.getTracks();
        tracks.forEach(function (track) {
            track.stop();
        });
        videoElement.srcObject = null;
        videoElement.parentNode.removeChild(videoElement);
    });
}

// Function to scan QR code from video stream
function scanQRCode(videoElement) {
    // Create canvas element to draw video frames
    var canvasElement = document.createElement('canvas');
    var canvasContext = canvasElement.getContext('2d');
    canvasElement.width = videoElement.videoWidth;
    canvasElement.height = videoElement.videoHeight;

    // Draw video frames onto the canvas and decode QR code
    var scanFrame = function () {
        canvasContext.drawImage(videoElement, 0, 0, canvasElement.width, canvasElement.height);
        var imageData = canvasContext.getImageData(0, 0, canvasElement.width, canvasElement.height);
        var code = jsQR(imageData.data, imageData.width, imageData.height);
        if (code) {
            // QR code found, handle the result
            handleQRCodeResult(code.data);
        } else {
            // QR code not found in the current frame, continue scanning
            requestAnimationFrame(scanFrame);
        }
    };

    // Start scanning for QR codes
    requestAnimationFrame(scanFrame);
}

// Function to handle the scanned QR code result
function handleQRCodeResult(qrCodeData) {
    alert('Scanned QR code: ' + qrCodeData);
}

// Call the function to start QR code scanning
startQRCodeScanner();
