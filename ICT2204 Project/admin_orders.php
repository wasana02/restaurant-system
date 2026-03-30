<?php
// ============================================================
// admin_orders.php — Restaurant Staff Panel
//                    View all takeaway & dine-in orders
// ============================================================
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

requireLogin(); // staff must be logged in

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $oid    = intval($_POST['order_id']);
    $status = in_array($_POST['status'], ['pending','confirmed','cancelled']) ? $_POST['status'] : 'pending';
    $upd = $conn->prepare("UPDATE orders SET status=? WHERE id=?");
    $upd->bind_param('si', $status, $oid);
    $upd->execute();
    $upd->close();
    header('Location: admin_orders.php?updated=1');
    exit();
}

// Fetch all orders newest first
$orders = $conn->query(
    "SELECT o.*, u.username, u.email
     FROM orders o
     JOIN users u ON u.id = o.user_id
     ORDER BY o.created_at DESC"
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Staff – Orders Panel | Una Beach Restaurant</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="css/style.css"/>
  <style>
    body { background:#1a0e07; padding-top:30px; }
    .wrap { max-width:1100px; margin:0 auto; padding:20px 16px 60px; }
    h2 { font-family:'Playfair Display',serif; color:#C0622A; }
    .order-card { background:#2C1A0E; border-radius:14px; padding:22px 26px; margin-bottom:20px; border-left:4px solid #C0622A; }
    .order-card.confirmed { border-left-color:#28a745; }
    .order-card.cancelled { border-left-color:#dc3545; opacity:0.7; }
    .order-title { font-family:'Playfair Display',serif; color:#fff; font-size:1.1rem; margin-bottom:4px; }
    .order-meta  { color:rgba(255,255,255,0.55); font-size:0.83rem; margin-bottom:14px; }
    .info-label  { color:#C0622A; font-weight:700; font-size:0.82rem; text-transform:uppercase; }
    .info-value  { color:rgba(255,255,255,0.85); font-size:0.9rem; }
    .items-table { width:100%; border-collapse:collapse; margin-top:10px; }
    .items-table th { color:#C0622A; font-size:0.8rem; text-transform:uppercase; border-bottom:1px solid rgba(255,255,255,0.1); padding:6px 8px; text-align:left; }
    .items-table td { color:rgba(255,255,255,0.8); font-size:0.88rem; padding:6px 8px; border-bottom:1px solid rgba(255,255,255,0.05); }
    .items-table tfoot td { color:#C0622A; font-weight:700; border-top:1px solid rgba(255,255,255,0.15); }
    .badge-type { font-size:0.75rem; padding:3px 12px; border-radius:20px; font-weight:600; }
    .badge-takeaway { background:#C0622A; color:#fff; }
    .badge-dinein   { background:#1a5c8a; color:#fff; }
    .badge-status   { font-size:0.75rem; padding:3px 12px; border-radius:20px; font-weight:600; }
    .badge-pending   { background:#ffc107; color:#000; }
    .badge-confirmed { background:#28a745; color:#fff; }
    .badge-cancelled { background:#dc3545; color:#fff; }
    .btn-status { border:none; border-radius:6px; padding:5px 14px; font-size:0.82rem; font-weight:600; cursor:pointer; margin-right:6px; }
    .btn-confirm  { background:#28a745; color:#fff; }
    .btn-cancel   { background:#dc3545; color:#fff; }
    .btn-pending  { background:#ffc107; color:#000; }
    .empty-msg { color:rgba(255,255,255,0.4); text-align:center; padding:60px 0; font-size:1.1rem; }
    .top-bar { display:flex; justify-content:space-between; align-items:center; margin-bottom:28px; flex-wrap:wrap; gap:12px; }
    .btn-back { background:transparent; border:1px solid rgba(255,255,255,0.3); color:rgba(255,255,255,0.7);
                border-radius:8px; padding:7px 18px; font-size:0.88rem; text-decoration:none; }
    .btn-back:hover { background:#C0622A; border-color:#C0622A; color:#fff; }
    .stat-row { display:flex; gap:16px; flex-wrap:wrap; margin-bottom:28px; }
    .stat-box { background:#2C1A0E; border-radius:10px; padding:14px 22px; flex:1; min-width:120px; text-align:center; }
    .stat-num  { font-size:1.8rem; font-weight:700; color:#C0622A; }
    .stat-lbl  { font-size:0.8rem; color:rgba(255,255,255,0.5); }
  </style>
</head>
<body>
<div class="wrap">

  <div class="top-bar">
    <div>
      <h2><i class="bi bi-clipboard2-check me-2"></i>Restaurant Orders Panel</h2>
      <p style="color:rgba(255,255,255,0.5); margin:0; font-size:0.88rem;">
        All customer orders — takeaway &amp; dine-in
      </p>
    </div>
    <a href="index.php" class="btn-back"><i class="bi bi-arrow-left me-1"></i>Back to Site</a>
  </div>

  <?php if (isset($_GET['updated'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
      <i class="bi bi-check-circle-fill me-2"></i>Order status updated!
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>

  <?php
  // Stats
  $total_orders    = $orders->num_rows;
  $pending_count   = 0;
  $confirmed_count = 0;
  $cancelled_count = 0;
  $all_orders      = [];
  while ($row = $orders->fetch_assoc()) {
      $all_orders[] = $row;
      if ($row['status'] === 'pending')   $pending_count++;
      if ($row['status'] === 'confirmed') $confirmed_count++;
      if ($row['status'] === 'cancelled') $cancelled_count++;
  }
  ?>

  <!-- Stats -->
  <div class="stat-row">
    <div class="stat-box">
      <div class="stat-num"><?= $total_orders ?></div>
      <div class="stat-lbl">Total Orders</div>
    </div>
    <div class="stat-box">
      <div class="stat-num" style="color:#ffc107;"><?= $pending_count ?></div>
      <div class="stat-lbl">Pending</div>
    </div>
    <div class="stat-box">
      <div class="stat-num" style="color:#28a745;"><?= $confirmed_count ?></div>
      <div class="stat-lbl">Confirmed</div>
    </div>
    <div class="stat-box">
      <div class="stat-num" style="color:#dc3545;"><?= $cancelled_count ?></div>
      <div class="stat-lbl">Cancelled</div>
    </div>
  </div>

  <?php if (empty($all_orders)): ?>
    <div class="empty-msg">
      <i class="bi bi-inbox" style="font-size:2.5rem; display:block; margin-bottom:12px;"></i>
      No orders yet. Orders will appear here when customers place them.
    </div>
  <?php endif; ?>

  <?php foreach ($all_orders as $order): ?>
    <?php
      // Fetch items for this order
      $istmt = $conn->prepare("SELECT * FROM order_items WHERE order_id = ?");
      $istmt->bind_param('i', $order['id']);
      $istmt->execute();
      $items = $istmt->get_result();
      $istmt->close();
    ?>
    <div class="order-card <?= $order['status'] ?>">

      <!-- Order header -->
      <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
        <div>
          <div class="order-title">
            Order #<?= $order['id'] ?>
            &nbsp;
            <span class="badge-type <?= $order['order_type'] === 'takeaway' ? 'badge-takeaway' : 'badge-dinein' ?>">
              <?= $order['order_type'] === 'takeaway' ? '🛍 Takeaway' : '🍽 Dine-In' ?>
            </span>
            &nbsp;
            <span class="badge-status badge-<?= $order['status'] ?>">
              <?= ucfirst($order['status']) ?>
            </span>
          </div>
          <div class="order-meta">
            Placed: <?= date('d M Y, h:i A', strtotime($order['created_at'])) ?>
            &nbsp;·&nbsp; Customer account: <strong style="color:rgba(255,255,255,0.7);"><?= htmlspecialchars($order['username']) ?></strong>
            (<?= htmlspecialchars($order['email']) ?>)
          </div>
        </div>

        <!-- Status change buttons -->
        <div>
          <?php if ($order['status'] !== 'confirmed'): ?>
            <form method="POST" style="display:inline;">
              <input type="hidden" name="order_id" value="<?= $order['id'] ?>"/>
              <input type="hidden" name="status" value="confirmed"/>
              <button class="btn-status btn-confirm">✓ Confirm</button>
            </form>
          <?php endif; ?>
          <?php if ($order['status'] !== 'cancelled'): ?>
            <form method="POST" style="display:inline;">
              <input type="hidden" name="order_id" value="<?= $order['id'] ?>"/>
              <input type="hidden" name="status" value="cancelled"/>
              <button class="btn-status btn-cancel">✕ Cancel</button>
            </form>
          <?php endif; ?>
          <?php if ($order['status'] !== 'pending'): ?>
            <form method="POST" style="display:inline;">
              <input type="hidden" name="order_id" value="<?= $order['id'] ?>"/>
              <input type="hidden" name="status" value="pending"/>
              <button class="btn-status btn-pending">↺ Pending</button>
            </form>
          <?php endif; ?>
        </div>
      </div>

      <div class="row g-3">

        <!-- Customer info -->
        <div class="col-md-4">
          <div class="info-label">Customer Info</div>
          <div class="info-value"><i class="bi bi-person me-1"></i><?= htmlspecialchars($order['customer_name']) ?></div>
          <div class="info-value"><i class="bi bi-telephone me-1"></i><?= htmlspecialchars($order['phone']) ?></div>
          <?php if ($order['order_type'] === 'takeaway' && $order['address']): ?>
            <div class="info-value mt-1"><i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($order['address']) ?></div>
          <?php endif; ?>
        </div>

        <!-- Booking info (dine-in only) -->
        <?php if ($order['order_type'] === 'dinein'): ?>
        <div class="col-md-4">
          <div class="info-label">Booking Details</div>
          <div class="info-value"><i class="bi bi-people me-1"></i>Party: <?= htmlspecialchars($order['party_size']) ?></div>
          <div class="info-value"><i class="bi bi-house me-1"></i>Seating: <?= ucfirst($order['seating']) ?></div>
          <div class="info-value"><i class="bi bi-calendar me-1"></i><?= $order['booking_date'] ? date('d M Y', strtotime($order['booking_date'])) : '—' ?></div>
          <div class="info-value"><i class="bi bi-clock me-1"></i><?= $order['booking_time'] ? date('h:i A', strtotime($order['booking_time'])) : '—' ?></div>
        </div>
        <?php endif; ?>

        <!-- Special note -->
        <?php if ($order['special_note']): ?>
        <div class="col-md-4">
          <div class="info-label">Special Note</div>
          <div class="info-value" style="font-style:italic;"><?= htmlspecialchars($order['special_note']) ?></div>
        </div>
        <?php endif; ?>

      </div>

      <!-- Food items -->
      <div class="mt-3">
        <div class="info-label mb-1"><i class="bi bi-basket me-1"></i>Ordered Items</div>
        <table class="items-table">
          <thead>
            <tr>
              <th>Item</th>
              <th>Price</th>
              <th>Qty</th>
              <th>Subtotal</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($item = $items->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($item['item_name']) ?></td>
              <td>LKR <?= number_format($item['price'], 2) ?></td>
              <td><?= $item['qty'] ?></td>
              <td>LKR <?= number_format($item['subtotal'], 2) ?></td>
            </tr>
            <?php endwhile; ?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="3">Total<?= $order['order_type'] === 'takeaway' ? ' (incl. LKR 200 delivery)' : '' ?></td>
              <td>LKR <?= number_format($order['total_amount'], 2) ?></td>
            </tr>
          </tfoot>
        </table>
      </div>

    </div>
  <?php endforeach; ?>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
