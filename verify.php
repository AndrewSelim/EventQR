<?php
// Valid QR codes array
$validQRCodes = array("1234567", "18887773");

// Read JSON input from the client-side
$data = json_decode(file_get_contents('php://input'), true);

// Extract the QR code from the input data
$qrCode = $data['qrCode'];

// Check if the QR code is valid
$isValid = in_array($qrCode, $validQRCodes);

// Return verification result as JSON
header('Content-Type: application/json');
echo json_encode(['isValid' => $isValid]);
?>
