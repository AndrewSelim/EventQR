// Function to handle QR code scanning
function scanQRCode(qrCode) {
    // Send the QR code to the server for verification
    fetch('verify.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ qrCode: qrCode })
    })
    .then(response => response.json())
    .then(data => {
        // Process the response from the server
        displayScanResult(data);
    })
    .catch(error => {
        console.error('Error:', error);
        displayErrorMessage('Something went wrong. Please try again.');
    });
}

// Function to display the scan result
function displayScanResult(data) {
    if (data.status === 'Success') {
        if (data.message === 'Scanned before') {
            alert('QR code has already been scanned before.');
        } else {
            alert('QR code verified. Welcome!');
        }
    } else if (data.status === 'Not found') {
        alert('QR code not found in the list of valid codes.');
    } else {
        alert('An error occurred while processing the QR code.');
    }
}

// Function to display error message
function displayErrorMessage(message) {
    alert(message);
}

// Example usage: Call scanQRCode function with the scanned QR code
// Replace 'scannedQRCode' with the actual scanned QR code value
scanQRCode('scannedQRCode');
