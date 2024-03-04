// Function to handle QR code scanning
function scanQRCode() {
    // Check if the browser supports getUserMedia
    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
        alert('Your browser does not support accessing the camera for scanning QR codes.');
        return;
    }

    // Access the camera stream
    navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } })
        .then(function (stream) {
            // Get the video element
            var videoElement = document.getElementById('video');

            // Set the video stream as the source for the video element
            if ('srcObject' in videoElement) {
                videoElement.srcObject = stream;
            } else {
                videoElement.src = window.URL.createObjectURL(stream);
            }

            // Play the video to start the camera preview
            videoElement.play();

            // Initiate QR code scanning using a library like zxing-js
            // Replace this with your QR code scanning implementation
            // zxing-js is just an example, you can use any other library
            zxingQRScan(videoElement);
        })
        .catch(function (error) {
            console.error('Error accessing camera:', error);
            alert('Failed to access the camera for scanning QR codes. Please check your camera permissions and try again.');
        });
}

// Example function to initiate QR code scanning using zxing-js library
function zxingQRScan(videoElement) {
    // Initialize zxing barcode detector
    const codeReader = new ZXing.BrowserQRCodeReader();

    // Start scanning for QR codes
    codeReader.decodeFromVideoElement(videoElement, (result, error) => {
        if (result) {
            // QR code scanned successfully, handle the result
            handleQRCodeResult(result.text);
        } else if (error) {
            // Error occurred while scanning QR code
            console.error('Error scanning QR code:', error);
            alert('An error occurred while scanning the QR code. Please try again.');
        }
    });
}

// Function to handle the scanned QR code result
function handleQRCodeResult(qrCode) {
    // Process the scanned QR code
    alert('Scanned QR code: ' + qrCode);
}

// Call the function to start QR code scanning
scanQRCode();
