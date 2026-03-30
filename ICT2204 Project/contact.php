<?php
// ============================================================
// contact.php — Contact Page (saves form to database)
// ============================================================
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

$dbSuccess = false;
$error     = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = sanitize($_POST['name']    ?? '');
    $email   = sanitize($_POST['email']   ?? '');
    $phone   = sanitize($_POST['phone']   ?? '');
    $subject = sanitize($_POST['subject'] ?? '');
    $message = sanitize($_POST['message'] ?? '');

    if (!$name || !$email || !$message) {
        $error = 'Please fill in all required fields (Name, Email, Message).';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($message) > 500) {
        $error = 'Message cannot exceed 500 characters.';
    } else {
        $stmt = $conn->prepare(
            "INSERT INTO messages (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->bind_param('sssss', $name, $email, $phone, $subject, $message);
        if ($stmt->execute()) {
            $dbSuccess = true; // triggers the success toast via JS below
        } else {
            $error = 'Could not send your message. Please try again.';
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Una Beach Restaurant – Contact</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="css/style.css"/>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg fixed-top" id="mainNavbar">
  <div class="container">
    <a class="navbar-brand" href="index.php">
      <img src="images/logo.png" alt="Logo" class="brand-logo"
           onerror="this.src='https://placehold.co/62x62/C0622A/FFF?text=UB'"/>
      <div class="brand-text-block">
        <span class="brand-name">Una Beach Restaurant</span>
        <span class="brand-tagline">Authentic Sri Lankan Cuisine</span>
      </div>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu"
            aria-controls="navMenu" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto align-items-center gap-1">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="menu.php">Menu</a></li>
        <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
        <li class="nav-item"><a class="nav-link active" href="contact.php">Contact</a></li>
        <?php if (isset($_SESSION['user_id'])): ?>
          <li class="nav-item">
            <a class="nav-link" href="dashboard.php">
              <i class="bi bi-person-circle me-1"></i><?= htmlspecialchars($_SESSION['username']) ?>
            </a>
          </li>
          <li class="nav-item"><a class="nav-link text-danger" href="auth/logout.php">Logout</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="auth/login.php">Login</a></li>
          <li class="nav-item ms-1">
            <a href="auth/register.php" class="btn btn-sm"
               style="background:#C0622A;color:#fff;border-radius:8px;padding:6px 16px;font-weight:600;">Register</a>
          </li>
        <?php endif; ?>
        <li class="nav-item ms-2">
          <button class="btn-cart position-relative" onclick="window.location.href='menu.php'" aria-label="View cart">
            <i class="bi bi-cart3"></i>
            <span class="cart-badge" id="cartBadge">0</span>
          </button>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- PAGE HEADER -->
<div class="page-header">
  <h1>Contact Us</h1>
  <p>We'd love to hear from you</p>
</div>

<!-- INFO CARDS -->
<section class="info-section">
  <div class="container">
    <h2 class="section-title text-center d-block mb-5">Get In Touch</h2>
    <div class="row g-4 justify-content-center">
      <div class="col-md-4 fade-up">
        <div class="info-card">
          <i class="bi bi-geo-alt-fill"></i>
          <h5>Our Location</h5>
          <p>Una Beach, Galle,<br/>Southern Province, Sri Lanka</p>
        </div>
      </div>
      <div class="col-md-4 fade-up" style="transition-delay:0.15s">
        <div class="info-card">
          <i class="bi bi-telephone-fill"></i>
          <h5>Phone</h5>
          <p><a href="tel:+94123456789">+94 123 456 789</a><br/><a href="tel:+94987654321">+94 987 654 321</a></p>
        </div>
      </div>
      <div class="col-md-4 fade-up" style="transition-delay:0.3s">
        <div class="info-card">
          <i class="bi bi-envelope-fill"></i>
          <h5>Email</h5>
          <p><a href="mailto:info@unabeach.lk">info@unabeach.lk</a><br/><a href="mailto:reservations@unabeach.lk">reservations@unabeach.lk</a></p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CONTACT FORM -->
<section class="contact-section">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8 fade-up">
        <div class="form-card">
          <h3>Send Us a Message</h3>

          <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show">
              <i class="bi bi-exclamation-circle-fill me-2"></i><?= $error ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          <?php endif; ?>

          <!-- KEY CHANGE: added method="POST", name="" on each field -->
          <form method="POST" action="" id="contactForm" novalidate>
            <div class="row g-3">

              <div class="col-md-6">
                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="name" id="cName"
                       placeholder="Your full name"
                       value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"/>
              </div>

              <div class="col-md-6">
                <label class="form-label">Phone Number</label>
                <input type="tel" class="form-control" name="phone" id="cPhone"
                       placeholder="Your phone number"
                       value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>"/>
              </div>

              <div class="col-12">
                <label class="form-label">Email Address <span class="text-danger">*</span></label>
                <input type="email" class="form-control" name="email" id="cEmail"
                       placeholder="your@email.com"
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"/>
              </div>

              <div class="col-12">
                <label class="form-label">Subject</label>
                <select class="form-select" name="subject" id="cSubject">
                  <option value="">Select a subject</option>
                  <option <?= ($_POST['subject'] ?? '') === 'Table Reservation' ? 'selected' : '' ?>>Table Reservation</option>
                  <option <?= ($_POST['subject'] ?? '') === 'Catering Inquiry'  ? 'selected' : '' ?>>Catering Inquiry</option>
                  <option <?= ($_POST['subject'] ?? '') === 'Feedback'          ? 'selected' : '' ?>>Feedback</option>
                  <option <?= ($_POST['subject'] ?? '') === 'General Question'  ? 'selected' : '' ?>>General Question</option>
                  <option <?= ($_POST['subject'] ?? '') === 'Other'             ? 'selected' : '' ?>>Other</option>
                </select>
              </div>

              <div class="col-12">
                <label class="form-label">Message <span class="text-danger">*</span></label>
                <textarea class="form-control" name="message" id="cMessage" rows="5"
                          placeholder="Write your message here..."><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
              </div>

              <div class="col-12">
                <small class="text-muted" id="charCount">0 / 500 characters</small>
              </div>

              <div class="col-12">
                <button type="submit" class="btn-send" onclick="return validateContact()">
                  <i class="bi bi-send-fill me-2"></i>Send Message
                </button>
              </div>

            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- OPENING HOURS + SOCIAL LINKS -->
<section class="hours-section">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-6 fade-up">
        <div class="hours-table">
          <h4><i class="bi bi-clock-fill me-2"></i>Opening Hours</h4>
          <div class="hours-row"><span>Everyday</span><span>10:00 AM – 11:00 PM</span></div>
        </div>
      </div>
      <div class="col-lg-6 fade-up" style="transition-delay:0.2s">
        <div class="hours-table">
          <h4><i class="bi bi-share-fill me-2"></i>Follow Us</h4>
          <div style="display:flex;gap:16px;margin-top:8px;flex-wrap:wrap;">
            <a href="#" class="social-icon" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
            <a href="#" class="social-icon" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
            <a href="#" class="social-icon" aria-label="WhatsApp"><i class="bi bi-whatsapp"></i></a>
            <a href="#" class="social-icon" aria-label="TikTok"><i class="bi bi-tiktok"></i></a>
          </div>
          <p style="color:rgba(255,255,255,0.75);font-size:0.92rem;margin-top:20px;line-height:1.7;">
            Follow us on social media for daily specials, behind-the-scenes cooking, and updates from Una Beach!
          </p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer style="background:#2C1A0E;color:rgba(255,255,255,0.7);text-align:center;padding:22px 0;font-size:0.88rem;">
  <p style="color:rgba(255,255,255,0.82);margin-bottom:2px;">© 2026 Una Beach Restaurant. All rights reserved.</p>
  <small>Authentic Sri Lankan Cuisine by the Beach</small>
</footer>

<!-- SUCCESS TOAST (same as original) -->
<div class="toast-success" id="successToast">
  <i class="bi bi-check-circle-fill"></i> Message sent successfully!
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/main.js"></script>
<script src="js/contact.js"></script>
<script>
  // Show toast if PHP saved successfully
  <?php if ($dbSuccess): ?>
  window.addEventListener('DOMContentLoaded', function() {
    var toast = document.getElementById('successToast');
    if (toast) {
      toast.classList.add('show');
      setTimeout(function() { toast.classList.remove('show'); }, 3500);
    }
  });
  <?php endif; ?>

  // JS validation — runs before PHP receives the form
  function validateContact() {
    var name    = document.getElementById('cName').value.trim();
    var email   = document.getElementById('cEmail').value.trim();
    var message = document.getElementById('cMessage').value.trim();
    if (!name || !email || !message) {
      alert('Please fill in all required fields.');
      return false; // stops form submitting
    }
    var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
      alert('Please enter a valid email address.');
      return false;
    }
    return true; // allow PHP to handle it
  }
</script>
</body>
</html>
