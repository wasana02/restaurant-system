<?php
// ============================================================
// auth/register.php — User Registration
// ============================================================
session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';

redirectIfLoggedIn(); // already logged in? go to dashboard

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = sanitize($_POST['username'] ?? '');
    $email    = sanitize($_POST['email']    ?? '');
    $password =          $_POST['password'] ?? '';
    $confirm  =          $_POST['confirm']  ?? '';

    // --- Validation ---
    if (!$username || !$email || !$password || !$confirm) {
        $error = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        // Check if username or email already taken
        $check = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $check->bind_param('ss', $username, $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = 'That username or email is already registered.';
        } else {
            // Hash password (never store plain text!)
            $hashed = password_hash($password, PASSWORD_DEFAULT);

            $insert = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $insert->bind_param('sss', $username, $email, $hashed);

            if ($insert->execute()) {
                $success = 'Account created successfully! <a href="login.php">Click here to login →</a>';
            } else {
                $error = 'Registration failed. Please try again.';
            }
            $insert->close();
        }
        $check->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Register – Una Beach Restaurant</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="../css/style.css"/>
  <style>
    body { background:#1a0e07; min-height:100vh; display:flex; align-items:center; justify-content:center; padding:40px 16px; }
    .auth-card { background:#2C1A0E; border-radius:16px; padding:40px 36px; max-width:460px; width:100%; box-shadow:0 8px 40px rgba(0,0,0,0.5); }
    .auth-title { font-family:'Playfair Display',serif; color:#C0622A; margin-bottom:4px; }
    .auth-sub { color:rgba(255,255,255,0.6); font-size:0.9rem; margin-bottom:24px; }
    .form-label { color:rgba(255,255,255,0.85); font-size:0.88rem; }
    .form-control { background:rgba(255,255,255,0.07); border:1px solid rgba(255,255,255,0.15); color:#fff; border-radius:8px; }
    .form-control:focus { background:rgba(255,255,255,0.12); border-color:#C0622A; color:#fff; box-shadow:none; }
    .form-control::placeholder { color:rgba(255,255,255,0.3); }
    .btn-submit { background:#C0622A; color:#fff; border:none; border-radius:8px; padding:12px; width:100%; font-weight:700; font-size:1rem; transition:background 0.2s; }
    .btn-submit:hover { background:#a0521f; }
    .auth-footer { color:rgba(255,255,255,0.6); font-size:0.9rem; text-align:center; margin-top:18px; }
    .auth-footer a { color:#C0622A; font-weight:600; text-decoration:none; }
    .brand-logo-wrap { text-align:center; margin-bottom:28px; }
    .brand-logo-wrap img { width:62px; border-radius:50%; }
    .brand-logo-wrap h1 { font-family:'Playfair Display',serif; color:#fff; font-size:1.25rem; margin-top:10px; }
  </style>
</head>
<body>
<div class="auth-card">

  <div class="brand-logo-wrap">
    <img src="../images/logo.png" alt="Logo"
         onerror="this.src='https://placehold.co/62x62/C0622A/FFF?text=UB'"/>
    <h1>Una Beach Restaurant</h1>
  </div>

  <h2 class="auth-title">Create Account</h2>
  <p class="auth-sub">Register to make reservations &amp; more.</p>

  <?php if ($error)   echo showAlert($error, 'danger');  ?>
  <?php if ($success) echo showAlert($success, 'success'); ?>

  <form method="POST" action="" id="registerForm" novalidate>

    <div class="mb-3">
      <label class="form-label">Username <span class="text-danger">*</span></label>
      <input type="text" class="form-control" name="username"
             placeholder="e.g. john_doe"
             value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required/>
    </div>

    <div class="mb-3">
      <label class="form-label">Email Address <span class="text-danger">*</span></label>
      <input type="email" class="form-control" name="email"
             placeholder="your@email.com"
             value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required/>
    </div>

    <div class="mb-3">
      <label class="form-label">Password <span class="text-danger">*</span></label>
      <input type="password" class="form-control" name="password"
             placeholder="Minimum 6 characters" required/>
    </div>

    <div class="mb-4">
      <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
      <input type="password" class="form-control" name="confirm"
             placeholder="Repeat your password" required/>
    </div>

    <button type="submit" class="btn-submit">
      <i class="bi bi-person-plus-fill me-2"></i>Register
    </button>

  </form>

  <p class="auth-footer">Already have an account? <a href="login.php">Login here</a></p>
  <p class="auth-footer"><a href="../index.php"><i class="bi bi-arrow-left me-1"></i>Back to Home</a></p>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Client-side validation before submitting
  document.getElementById('registerForm').addEventListener('submit', function(e) {
    var u = this.username.value.trim();
    var em = this.email.value.trim();
    var p = this.password.value;
    var c = this.confirm.value;
    if (!u || !em || !p || !c) { e.preventDefault(); alert('Please fill in all fields.'); return; }
    if (p.length < 6)          { e.preventDefault(); alert('Password must be at least 6 characters.'); return; }
    if (p !== c)               { e.preventDefault(); alert('Passwords do not match.'); return; }
  });
</script>
</body>
</html>
