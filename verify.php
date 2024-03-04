<?php
session_start();

// Valid QR codes array
$validQRCodes = array("1", "5556", "12345678");

// Initialize scanned QR codes array in session if not already set
if (!isset($_SESSION['scannedQRCodes'])) {
    $_SESSION['scannedQRCodes'] = array();
}

// Read JSON input from the client-side
$data = json_decode(file_get_contents('php://input'), true);

// Extract the QR code from the input data
$qrCode = $data['qrCode'];

// Check if the QR code is in the list of valid QR codes
$isValid = in_array($qrCode, $validQRCodes);
$isScannedBefore = false;

if ($isValid) {
    // If QR code is valid, check if it has been scanned before
    if (in_array($qrCode, $_SESSION['scannedQRCodes'])) {
        $isScannedBefore = true;
    } else {
        // If QR code is valid and not scanned before, mark it as scanned
        $_SESSION['scannedQRCodes'][] = $qrCode;
    }
}

// Return verification result as JSON
header('Content-Type: application/json');
echo json_encode(['isValid' => $isValid, 'isScannedBefore' => $isScannedBefore]);
?>
