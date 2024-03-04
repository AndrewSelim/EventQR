<?php
// Read JSON input from the client-side
$data = json_decode(file_get_contents('php://input'), true);

// Simulate database verification (replace this with your actual database logic)
$scannedCodes = []; // Array to store scanned QR codes
$qrCode = $data['qrCode'];
$exists = in_array($qrCode, $scannedCodes);

// Return verification result as JSON
header('Content-Type: application/json');
echo json_encode(['exists' => $exists]);
