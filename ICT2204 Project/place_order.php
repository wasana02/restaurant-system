<?php
// ============================================================
// place_order.php — Receives order data from menu.js (AJAX)
//                   and saves it to the database
// ============================================================
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

header('Content-Type: application/json');

// Must be logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in.']);
    exit();
}

// Must be POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
if (!$data) {
    echo json_encode(['success' => false, 'message' => 'No data received.']);
    exit();
}

$user_id    = $_SESSION['user_id'];
$order_type = sanitize($data['order_type'] ?? '');
$cust_name  = sanitize($data['customer_name'] ?? '');
$phone      = sanitize($data['phone'] ?? '');
$items      = $data['items'] ?? [];
$total      = floatval($data['total'] ?? 0);

if (!$order_type || !$cust_name || !$phone || empty($items) || $total <= 0) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
    exit();
}

// Extra fields depending on order type
$address      = sanitize($data['address']      ?? '');
$party_size   = sanitize($data['party_size']   ?? '');
$seating      = sanitize($data['seating']      ?? '');
$booking_date = sanitize($data['booking_date'] ?? '');
$booking_time = sanitize($data['booking_time'] ?? '');
$special_note = sanitize($data['special_note'] ?? '');

// Insert into orders table
$stmt = $conn->prepare(
    "INSERT INTO orders
        (user_id, order_type, customer_name, phone, address,
         party_size, seating, booking_date, booking_time, special_note, total_amount)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
);
$stmt->bind_param(
    'isssssssssd',
    $user_id, $order_type, $cust_name, $phone, $address,
    $party_size, $seating, $booking_date, $booking_time, $special_note, $total
);

if (!$stmt->execute()) {
    echo json_encode(['success' => false, 'message' => 'Failed to save order.']);
    $stmt->close();
    exit();
}

$order_id = $stmt->insert_id;
$stmt->close();

// Insert each item into order_items table
$istmt = $conn->prepare(
    "INSERT INTO order_items (order_id, item_name, price, qty, subtotal)
     VALUES (?, ?, ?, ?, ?)"
);

foreach ($items as $item) {
    $item_name = sanitize($item['name'] ?? '');
    $price     = floatval($item['price'] ?? 0);
    $qty       = intval($item['qty']   ?? 1);
    $subtotal  = $price * $qty;
    $istmt->bind_param('isdid', $order_id, $item_name, $price, $qty, $subtotal);
    $istmt->execute();
}
$istmt->close();

echo json_encode(['success' => true, 'order_id' => $order_id]);
exit();
?>
