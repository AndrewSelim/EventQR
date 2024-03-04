<?php
session_start();

// Define valid QR codes
$validQRCodes = ["1", "5556", "12345678"];

// Initialize scanned QR codes array in session if not already set
if (!isset($_SESSION['scannedQRCodes'])) {
    $_SESSION['scannedQRCodes'] = [];
}

// Read JSON input from the client-side
$data = json_decode(file_get_contents('php://input'), true);

// Initialize response data
$response = [
    'isValid' => false,
    'isScannedBefore' => false,
    'errorMessage' => ''
];

try {
    // Verify if the QR code is in the list of valid QR codes
    $qrCode = isset($data['qrCode']) ? $data['qrCode'] : null;
    if (!$qrCode) {
        throw new Exception('QR code not provided.');
    }

    // Check if the QR code is valid
    if (!in_array($qrCode, $validQRCodes)) {
        throw new Exception('QR code is not valid.');
    }

    // Check if the QR code has been scanned before
    if (in_array($qrCode, $_SESSION['scannedQRCodes'])) {
        $response['isScannedBefore'] = true;
    } else {
        // Mark the QR code as scanned
        $_SESSION['scannedQRCodes'][] = $qrCode;
        $response['isValid'] = true;
    }
} catch (Exception $e) {
    $response['errorMessage'] = $e->getMessage();
}

// Return verification result as JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
