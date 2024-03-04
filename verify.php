<?php
session_start();

// Define valid QR codes
$validQRCodes = ["1", "5556", "12345678"];

// Initialize scanned QR codes array in session if not already set
function initializeSession() {
    if (!isset($_SESSION['scannedQRCodes'])) {
        $_SESSION['scannedQRCodes'] = [];
    }
}

// Verify if the QR code is valid
function isValidQRCode($qrCode) {
    global $validQRCodes;
    return in_array($qrCode, $validQRCodes);
}

// Check if the QR code has been scanned before
function isScannedBefore($qrCode) {
    return in_array($qrCode, $_SESSION['scannedQRCodes']);
}

// Read JSON input from the client-side
function getQRCodeFromRequest() {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    return isset($data['qrCode']) ? $data['qrCode'] : null;
}

// Main function to verify the QR code
function verifyQRCode() {
    initializeSession();

    $response = [
        'isValid' => false,
        'isScannedBefore' => false,
        'errorMessage' => ''
    ];

    try {
        // Get QR code from request
        $qrCode = getQRCodeFromRequest();
        if (!$qrCode) {
            throw new Exception('QR code not provided.');
        }

        // Check if QR code is valid
        if (!isValidQRCode($qrCode)) {
            throw new Exception('QR code is not valid.');
        }

        // Check if QR code has been scanned before
        if (isScannedBefore($qrCode)) {
            $response['isScannedBefore'] = true;
        } else {
            // Mark QR code as scanned
            $_SESSION['scannedQRCodes'][] = $qrCode;
            $response['isValid'] = true;
        }
    } catch (Exception $e) {
        $response['errorMessage'] = $e->getMessage();
        error_log('Error in verifyQRCode function: ' . $e->getMessage());
    }

    // Return verification result as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
}

// Call the main function
verifyQRCode();
?>
