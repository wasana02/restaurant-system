<?php
// ============================================================
// auth/login.php — User Login
// ============================================================
session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';

redirectIfLoggedIn(); // already logged in? go to dashboard

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email    = sanitize($_POST['email']    ?? '');
    $password =          $_POST['password'] ?? '';

    if (!$email || !$password) {
        $error = 'Please enter your email and password.';
    } else {
        // Find user by email
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->bind_result($id, $username, $hashed);
        $stmt->fetch();
        $stmt->close();

        // Verify password against stored hash
        if ($id && password_verify($password, $hashed)) {
            // ✅ Login success — create session
            $_SESSION['user_id']  = $id;
            $_SESSION['username'] = $username;
            redirect('../dashboard.php');
        } else {
            $error = 'Invalid email or password. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login – Una Beach Restaurant</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="../css/style.css"/>
  <style>
    body { background:#1a0e07; min-height:100vh; display:flex; align-items:center; justify-content:center; padding:40px 16px; }
    .auth-card { background:#2C1A0E; border-radius:16px; padding:40px 36px; max-width:420px; width:100%; box-shadow:0 8px 40px rgba(0,0,0,0.5); }
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

  <h2 class="auth-title">Welcome Back</h2>
  <p class="auth-sub">Login to manage your reservations.</p>

  <?php if ($error) echo showAlert($error, 'danger'); ?>

  <form method="POST" action="" id="loginForm" novalidate>

    <div class="mb-3">
      <label class="form-label">Email Address <span class="text-danger">*</span></label>
      <input type="email" class="form-control" name="email"
             placeholder="your@email.com"
             value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required/>
    </div>

    <div class="mb-4">
      <label class="form-label">Password <span class="text-danger">*</span></label>
      <input type="password" class="form-control" name="password"
             placeholder="Your password" required/>
    </div>

    <button type="submit" class="btn-submit">
      <i class="bi bi-box-arrow-in-right me-2"></i>Login
    </button>

  </form>

  <p class="auth-footer">Don't have an account? <a href="register.php">Register here</a></p>
  <p class="auth-footer"><a href="../index.php"><i class="bi bi-arrow-left me-1"></i>Back to Home</a></p>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  document.getElementById('loginForm').addEventListener('submit', function(e) {
    if (!this.email.value.trim() || !this.password.value) {
      e.preventDefault();
      alert('Please enter your email and password.');
    }
  });
</script>
</body>
</html>
