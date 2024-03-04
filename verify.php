<?php
session_start();

// Initialize list of scanned QR codes
if (!isset($_SESSION['scannedQRCodes'])) {
    $_SESSION['scannedQRCodes'] = [];
}

// Read JSON input from the client-side
function getQRCodeFromRequest() {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    return isset($data['qrCode']) ? $data['qrCode'] : null;
}

// Main function to verify the QR code
function verifyQRCode() {
    $qrCode = getQRCodeFromRequest();
    $response = [
        'status' => '',
        'message' => ''
    ];

    try {
        if (!$qrCode) {
            throw new Exception('QR code not provided.');
        }

        if (in_array($qrCode, $_SESSION['scannedQRCodes'])) {
            $response['status'] = 'Scanned before';
            $response['message'] = 'This QR code has already been scanned before.';
        } else {
            $response['status'] = 'Success';
            $response['message'] = 'QR code scanned successfully.';
            $_SESSION['scannedQRCodes'][] = $qrCode; // Add QR code to list of scanned codes
        }
    } catch (Exception $e) {
        $response['status'] = 'Error';
        $response['message'] = $e->getMessage();
    }

    // Return verification result as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
}

// Call the main function
verifyQRCode();
?>
