<?php
// ============================================================
// dashboard.php — User Dashboard (reservations)
// Only accessible when logged in
// ============================================================
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

requireLogin(); // redirect to login if not logged in

$username = htmlspecialchars($_SESSION['username']);
$resErr   = '';

// Handle new reservation submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $guest_name  = sanitize($_POST['guest_name']  ?? '');
    $guest_count = sanitize($_POST['guest_count'] ?? '');
    $seating     = sanitize($_POST['seating']     ?? 'indoor');
    $date        = sanitize($_POST['date']        ?? '');
    $time        = sanitize($_POST['time']        ?? '');
    $note        = sanitize($_POST['special_note']?? '');

    if (!$guest_name || !$guest_count || !$date || !$time) {
        $resErr = 'Please fill in all required fields.';
    } else {
        $ins = $conn->prepare(
            "INSERT INTO reservations (user_id, guest_name, guest_count, seating, date, time, special_note)
             VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        $ins->bind_param('issssss',
            $_SESSION['user_id'], $guest_name, $guest_count,
            $seating, $date, $time, $note
        );
        if ($ins->execute()) {
            header('Location: dashboard.php?saved=1');
            exit();
        } else {
            $resErr = 'Failed to save reservation. Please try again.';
        }
        $ins->close();
    }
}

// Fetch this user's reservations
$stmt = $conn->prepare(
    "SELECT * FROM reservations WHERE user_id = ? ORDER BY date DESC, time DESC"
);
$stmt->bind_param('i', $_SESSION['user_id']);
$stmt->execute();
$reservations = $stmt->get_result();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard – Una Beach Restaurant</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="css/style.css"/>
  <style>
    body { background:#1a0e07; padding-top:80px; }
    .dash-wrap { max-width:900px; margin:40px auto; padding:0 16px 60px; }
    .dash-header { background:#2C1A0E; border-radius:14px; padding:28px 32px; margin-bottom:24px;
                   display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px; }
    .dash-header h2 { font-family:'Playfair Display',serif; color:#C0622A; margin:0; }
    .dash-header p  { color:rgba(255,255,255,0.65); margin:4px 0 0; }
    .dash-card { background:#2C1A0E; border-radius:14px; padding:28px 32px; margin-bottom:24px; }
    .dash-card h4 { font-family:'Playfair Display',serif; color:#C0622A; margin-bottom:20px; }
    .form-label { color:rgba(255,255,255,0.85); font-size:0.88rem; }
    .form-control, .form-select { background:rgba(255,255,255,0.07); border:1px solid rgba(255,255,255,0.15); color:#fff; border-radius:8px; }
    .form-control:focus, .form-select:focus { background:rgba(255,255,255,0.12); border-color:#C0622A; color:#fff; box-shadow:none; }
    .form-control::placeholder { color:rgba(255,255,255,0.3); }
    .form-select option { background:#2C1A0E; }
    .btn-res { background:#C0622A; color:#fff; border:none; border-radius:8px; padding:10px 28px; font-weight:700; transition:background 0.2s; }
    .btn-res:hover { background:#a0521f; color:#fff; }
    table { color:rgba(255,255,255,0.85); }
    th { color:#C0622A; border-color:rgba(255,255,255,0.12) !important; }
    td { border-color:rgba(255,255,255,0.07) !important; vertical-align:middle; }
    .badge-seat { font-size:0.75rem; padding:3px 10px; border-radius:20px; background:rgba(255,255,255,0.1); color:rgba(255,255,255,0.75); }
    .badge-party { background:#C0622A; color:#fff; font-size:0.75rem; padding:3px 10px; border-radius:20px; }
    .btn-logout { background:transparent; border:1px solid rgba(255,255,255,0.3); color:rgba(255,255,255,0.7);
                  border-radius:8px; padding:7px 18px; font-size:0.88rem; text-decoration:none; transition:all 0.2s; }
    .btn-logout:hover { background:#C0622A; border-color:#C0622A; color:#fff; }
  </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg fixed-top scrolled" id="mainNavbar">
  <div class="container">
    <a class="navbar-brand" href="index.php">
      <img src="images/logo.png" alt="Logo" class="brand-logo"
           onerror="this.src='https://placehold.co/62x62/C0622A/FFF?text=UB'"/>
      <div class="brand-text-block">
        <span class="brand-name">Una Beach Restaurant</span>
        <span class="brand-tagline">Authentic Sri Lankan Cuisine</span>
      </div>
    </a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto align-items-center gap-1">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="menu.php">Menu</a></li>
        <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
        <li class="nav-item">
          <a class="nav-link" href="dashboard.php">
            <i class="bi bi-person-circle me-1"></i><?= $username ?>
          </a>
        </li>
        <li class="nav-item"><a class="nav-link text-danger" href="auth/logout.php">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="dash-wrap">

  <!-- Header -->
  <div class="dash-header">
    <div>
      <h2>Welcome, <?= $username ?>! 👋</h2>
      <p>Manage your table reservations below.</p>
    </div>
    <a href="auth/logout.php" class="btn-logout">
      <i class="bi bi-box-arrow-right me-1"></i>Logout
    </a>
  </div>

  <!-- Alerts -->
  <?php if (isset($_GET['saved'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
      <i class="bi bi-check-circle-fill me-2"></i>Reservation saved successfully!
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>

  <?php if ($resErr): ?>
    <div class="alert alert-danger alert-dismissible fade show">
      <i class="bi bi-exclamation-circle-fill me-2"></i><?= $resErr ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>

  <!-- Make Reservation Form -->
  <div class="dash-card">
    <h4><i class="bi bi-calendar-plus me-2"></i>Make a Reservation</h4>
    <form method="POST" action="" id="resForm">
      <div class="row g-3">

        <div class="col-md-6">
          <label class="form-label">Guest Name <span class="text-danger">*</span></label>
          <input type="text" class="form-control" name="guest_name"
                 placeholder="Name on the reservation" required/>
        </div>

        <div class="col-md-6">
          <label class="form-label">Party Size <span class="text-danger">*</span></label>
          <select class="form-select" name="guest_count" required>
            <option value="">Select party size</option>
            <option value="1">1 Person</option>
            <option value="couple">Couple (2 people)</option>
            <option value="family">Family (3–5 people)</option>
            <option value="group">Group (6+ people)</option>
          </select>
        </div>

        <div class="col-md-4">
          <label class="form-label">Seating</label>
          <select class="form-select" name="seating">
            <option value="indoor">🏠 Indoor</option>
            <option value="outdoor">🌳 Outdoor</option>
          </select>
        </div>

        <div class="col-md-4">
          <label class="form-label">Date <span class="text-danger">*</span></label>
          <input type="date" class="form-control" name="date"
                 min="<?= date('Y-m-d') ?>" required/>
        </div>

        <div class="col-md-4">
          <label class="form-label">Time <span class="text-danger">*</span></label>
          <input type="time" class="form-control" name="time"
                 min="10:00" max="23:00" required/>
        </div>

        <div class="col-12">
          <label class="form-label">Special Note <span class="text-muted" style="font-size:0.82rem;">(optional)</span></label>
          <textarea class="form-control" name="special_note" rows="2"
                    placeholder="Allergies, birthday, wheelchair access..."></textarea>
        </div>

        <div class="col-12">
          <button type="submit" class="btn-res">
            <i class="bi bi-check-lg me-2"></i>Confirm Reservation
          </button>
        </div>

      </div>
    </form>
  </div>

  <!-- My Reservations Table -->
  <div class="dash-card">
    <h4><i class="bi bi-list-ul me-2"></i>My Reservations</h4>

    <?php if ($reservations->num_rows === 0): ?>
      <p style="color:rgba(255,255,255,0.5); margin:0;">
        No reservations yet. Use the form above to make your first booking!
      </p>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-borderless align-middle">
          <thead>
            <tr>
              <th>#</th>
              <th>Guest Name</th>
              <th>Party</th>
              <th>Seating</th>
              <th>Date</th>
              <th>Time</th>
              <th>Note</th>
            </tr>
          </thead>
          <tbody>
            <?php $i = 1; while ($row = $reservations->fetch_assoc()): ?>
            <tr>
              <td><?= $i++ ?></td>
              <td><?= htmlspecialchars($row['guest_name']) ?></td>
              <td><span class="badge-party"><?= htmlspecialchars($row['guest_count']) ?></span></td>
              <td><span class="badge-seat"><?= ucfirst($row['seating']) ?></span></td>
              <td><?= date('d M Y', strtotime($row['date'])) ?></td>
              <td><?= date('h:i A', strtotime($row['time'])) ?></td>
              <td style="color:rgba(255,255,255,0.5); font-size:0.85rem;">
                <?= htmlspecialchars($row['special_note'] ?: '—') ?>
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/main.js"></script>
<script>
  // JS validation for reservation form
  document.getElementById('resForm').addEventListener('submit', function(e) {
    var name  = this.guest_name.value.trim();
    var size  = this.guest_count.value;
    var date  = this.date.value;
    var time  = this.time.value;
    if (!name || !size || !date || !time) {
      e.preventDefault();
      alert('Please fill in all required fields.');
    }
  });
</script>
</body>
</html>
