// Initialize HTML5 QR Code Scanner
const html5QrCode = new Html5Qrcode('scanner');

// Function to handle successful QR code scan
function onScanSuccess(qrCodeMessage) {
    const resultDiv = document.getElementById('result');

    // Send the scanned QR code to the server for verification
    fetch('verify.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ qrCode: qrCodeMessage })
    })
    .then(response => response.json())
    .then(data => {
        if (data.isValid) {
            resultDiv.innerText = 'Success: Ticket verified.';
        } else if (data.isScannedBefore) {
            resultDiv.innerText = 'Scanned before: Ticket already used.';
        } else {
            resultDiv.innerText = 'Not found: Ticket not valid.';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        resultDiv.innerText = 'Error: Something went wrong.';
    });
}

// Start scanning QR codes
html5QrCode.start(
    { facingMode: 'environment' },  // Scan using the rear camera
    { fps: 10, qrbox: 250 },        // Frame rate and QR code scanning box size
    onScanSuccess
);
