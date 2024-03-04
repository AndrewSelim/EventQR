<?php
session_start();

// Define valid QR codes
$validQRCodes = ["1", "5556", "12345678"];
// Initialize list of people who have arrived
if (!isset($_SESSION['arrivedPeople'])) {
    $_SESSION['arrivedPeople'] = [];
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

        if (in_array($qrCode, $_SESSION['arrivedPeople'])) {
            $response['status'] = 'Scanned before';
            $response['message'] = 'This QR code has already been scanned before.';
        } elseif (in_array($qrCode, $GLOBALS['validQRCodes'])) {
            $response['status'] = 'Success';
            $response['message'] = 'QR code verified. Welcome!';
            $_SESSION['arrivedPeople'][] = $qrCode; // Add QR code to list of arrived people
        } else {
            $response['status'] = 'Not found';
            $response['message'] = 'QR code not found in the list of valid codes.';
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
