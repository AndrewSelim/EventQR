<?php
// Valid QR codes array
$validQRCodes = array("1", "5556", "12345678");

// Read JSON input from the client-side
$data = json_decode(file_get_contents('php://input'), true);

// Extract the QR code from the input data
$qrCode = $data['qrCode'];

// Check if the QR code is in the list of valid QR codes
$isScannedBefore = false;
if (in_array($qrCode, $validQRCodes)) {
    // If QR code is valid, check if it has been scanned before
    if (isset($_SESSION['scannedQRCodes']) && in_array($qrCode, $_SESSION['scannedQRCodes'])) {
        $isScannedBefore = true;
    } else {
        // If QR code is valid and not scanned before, mark it as scanned
        $_SESSION['scannedQRCodes'][] = $qrCode;
    }
}

// Return verification result as JSON
header('Content-Type: application/json');
echo json_encode(['isValid' => in_array($qrCode, $validQRCodes), 'isScannedBefore' => $isScannedBefore]);
?>
