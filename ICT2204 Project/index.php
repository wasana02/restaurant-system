<?php
// ============================================================
// index.php — Home Page (converted from home.html)
// ============================================================
session_start();
require_once 'includes/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Una Beach Restaurant – Home</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="css/style.css"/>
</head>
<body>

<!-- ============================================================
     NAVBAR — PHP version (shows Login/Register OR username/Logout)
============================================================ -->
<nav class="navbar navbar-expand-lg fixed-top" id="mainNavbar">
  <div class="container">

    <a class="navbar-brand" href="index.php">
      <img src="images/logo.png" alt="Una Beach Logo" class="brand-logo"
           onerror="this.src='https://placehold.co/62x62/C0622A/FFF?text=UB'"/>
      <div class="brand-text-block">
        <span class="brand-name">Una Beach Restaurant</span>
        <span class="brand-tagline">Authentic Sri Lankan Cuisine</span>
      </div>
    </a>

    <button class="navbar-toggler" type="button"
            data-bs-toggle="collapse" data-bs-target="#navMenu"
            aria-controls="navMenu" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto align-items-center gap-1">
        <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="menu.php">Menu</a></li>
        <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>

        <?php if (isset($_SESSION['user_id'])): ?>
          <!-- LOGGED IN: show username + logout -->
          <li class="nav-item">
            <a class="nav-link" href="dashboard.php">
              <i class="bi bi-person-circle me-1"></i>
              <?= htmlspecialchars($_SESSION['username']) ?>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-danger" href="auth/logout.php">Logout</a>
          </li>
        <?php else: ?>
          <!-- NOT LOGGED IN: show login + register -->
          <li class="nav-item"><a class="nav-link" href="auth/login.php">Login</a></li>
          <li class="nav-item ms-1">
            <a href="auth/register.php" class="btn btn-sm"
               style="background:#C0622A;color:#fff;border-radius:8px;padding:6px 16px;font-weight:600;">
              Register
            </a>
          </li>
        <?php endif; ?>

        <li class="nav-item ms-2">
          <button class="btn-cart position-relative"
                  onclick="window.location.href='menu.php'" aria-label="View cart">
            <i class="bi bi-cart3"></i>
            <span class="cart-badge" id="cartBadge">0</span>
          </button>
        </li>
      </ul>
    </div>

  </div>
</nav>


<!-- HERO — full viewport carousel with overlay text -->
<section class="hero-section">
  <div id="heroCarousel" class="carousel slide hero-carousel" data-bs-ride="carousel" data-bs-interval="4000">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img src="images/home1.jpg" class="d-block w-100 hero-carousel-img" alt="Una Beach Restaurant"
             onerror="this.src='https://placehold.co/1920x900/2C1A0E/FFF?text=Una+Beach'"/>
      </div>
      <div class="carousel-item">
        <img src="images/gallery1.jpg" class="d-block w-100 hero-carousel-img" alt="Una Beach Restaurant"
             onerror="this.src='https://placehold.co/1920x900/2C1A0E/FFF?text=Una+Beach'"/>
      </div>
      <div class="carousel-item">
        <img src="images/gallery2.jpg" class="d-block w-100 hero-carousel-img" alt="Una Beach Restaurant"
             onerror="this.src='https://placehold.co/1920x900/2C1A0E/FFF?text=Una+Beach'"/>
      </div>
      <div class="carousel-item">
        <img src="images/gallery3.jpg" class="d-block w-100 hero-carousel-img" alt="Una Beach Restaurant"
             onerror="this.src='https://placehold.co/1920x900/2C1A0E/FFF?text=Una+Beach'"/>
      </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
      <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
      <span class="carousel-control-next-icon"></span>
    </button>
  </div>
  <div class="hero-overlay"></div>
  <div class="hero-content">
    <h1 class="hero-title">Una Beach Restaurant</h1>
    <p class="hero-desc">Experience Authentic Sri Lankan Flavors by the Ocean</p>
    <a href="menu.php" class="btn-hero">View Our Menu</a>
  </div>
</section>


<!-- WHY CHOOSE US -->
<section class="why-section">
  <div class="container">
    <h2 class="section-title text-center d-block mb-5">Why Choose Us</h2>
    <div class="row g-4 justify-content-center">
      <div class="col-md-4 fade-up">
        <div class="why-card">
          <div class="why-icon"><i class="bi bi-basket2-fill"></i></div>
          <h5>Fresh Ingredients</h5>
          <p>We use only the freshest, locally sourced ingredients to create authentic Sri Lankan dishes.</p>
        </div>
      </div>
      <div class="col-md-4 fade-up" style="transition-delay:0.15s">
        <div class="why-card">
          <div class="why-icon"><i class="bi bi-water"></i></div>
          <h5>Beachside Dining</h5>
          <p>Enjoy your meal with stunning ocean views and the sound of waves in the background.</p>
        </div>
      </div>
      <div class="col-md-4 fade-up" style="transition-delay:0.30s">
        <div class="why-card">
          <div class="why-icon"><i class="bi bi-heart-fill"></i></div>
          <h5>Traditional Recipes</h5>
          <p>Time-honored recipes passed down through generations, prepared with love and care.</p>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- TODAY'S SPECIALS CAROUSEL -->
<section class="specials-section py-5">
  <div class="container">
    <h2 class="section-title text-center d-block mb-5">Today's Specials</h2>
    <div id="specialsCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3500">
      <div class="carousel-indicators">
        <button type="button" data-bs-target="#specialsCarousel" data-bs-slide-to="0" class="active" aria-current="true"></button>
        <button type="button" data-bs-target="#specialsCarousel" data-bs-slide-to="1"></button>
        <button type="button" data-bs-target="#specialsCarousel" data-bs-slide-to="2"></button>
        <button type="button" data-bs-target="#specialsCarousel" data-bs-slide-to="3"></button>
      </div>
      <div class="carousel-inner rounded-4 overflow-hidden">
        <div class="carousel-item active">
          <img src="images/Main1.jpg" class="d-block w-100 carousel-img" alt="Steamed White Rice"
               onerror="this.src='https://placehold.co/1100x420/C0622A/FFF?text=Steamed+White+Rice'"/>
          <div class="carousel-caption d-none d-md-block">
            <h5>Steamed White Rice & Curry</h5>
            <p>A classic Sri Lankan meal — tender rice served with our house chicken curry.</p>
          </div>
        </div>
        <div class="carousel-item">
          <img src="images/kottu2.jpg" class="d-block w-100 carousel-img" alt="Chicken Kottu"
               onerror="this.src='https://placehold.co/1100x420/8B3E1A/FFF?text=Chicken+Kottu'"/>
          <div class="carousel-caption d-none d-md-block">
            <h5>Chicken Kottu Roti</h5>
            <p>Freshly chopped roti tossed with chicken, vegetables and spices on a hot griddle.</p>
          </div>
        </div>
        <div class="carousel-item">
          <img src="images/dessert1.jpg" class="d-block w-100 carousel-img" alt="Watalappan"
               onerror="this.src='https://placehold.co/1100x420/A0522D/FFF?text=Watalappan'"/>
          <div class="carousel-caption d-none d-md-block">
            <h5>Watalappan</h5>
            <p>Traditional Sri Lankan coconut custard pudding — a must-try dessert.</p>
          </div>
        </div>
        <div class="carousel-item">
          <img src="images/Rice3.jpg" class="d-block w-100 carousel-img" alt="Chicken Fried Rice"
               onerror="this.src='https://placehold.co/1100x420/C0622A/FFF?text=Chicken+Fried+Rice'"/>
          <div class="carousel-caption d-none d-md-block">
            <h5>Chicken Fried Rice</h5>
            <p>Wok-tossed fragrant rice with tender chicken pieces and fresh vegetables.</p>
          </div>
        </div>
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#specialsCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#specialsCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
      </button>
    </div>
  </div>
</section>


<!-- GALLERY -->
<section class="gallery-section">
  <div class="container">
    <h2 class="section-title text-center d-block mb-5">Gallery</h2>
    <div class="row g-3">
      <div class="col-md-4 fade-up">
        <div class="gallery-wrap">
          <img src="images/gallery1.jpg" alt="Sri Lankan Food"
               onerror="this.src='https://placehold.co/500x300/C0622A/FFF?text=Sri+Lankan+Food'"/>
        </div>
      </div>
      <div class="col-md-4 fade-up" style="transition-delay:0.15s">
        <div class="gallery-wrap">
          <img src="images/gallery2.jpg" alt="Beachside Dining"
               onerror="this.src='https://placehold.co/500x300/A0522D/FFF?text=Beachside+Dining'"/>
        </div>
      </div>
      <div class="col-md-4 fade-up" style="transition-delay:0.30s">
        <div class="gallery-wrap">
          <img src="images/gallery3.jpg" alt="Ocean View"
               onerror="this.src='https://placehold.co/500x300/8B4513/FFF?text=Ocean+View'"/>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- VISIT US -->
<section class="visit-section">
  <div class="container">
    <h2 class="section-title text-center d-block mb-5">Visit Us</h2>
    <div class="row g-4 justify-content-center">
      <div class="col-md-4 fade-up">
        <div class="visit-card">
          <i class="bi bi-geo-alt-fill"></i><h6>Location</h6>
          <p>Una Beach, Galle, Sri Lanka</p>
        </div>
      </div>
      <div class="col-md-4 fade-up" style="transition-delay:0.15s">
        <div class="visit-card">
          <i class="bi bi-clock-fill"></i><h6>Hours</h6>
          <p>Daily: 10:00 AM – 11:00 PM</p>
        </div>
      </div>
      <div class="col-md-4 fade-up" style="transition-delay:0.30s">
        <div class="visit-card">
          <i class="bi bi-telephone-fill"></i><h6>Contact</h6>
          <p>+94 123 456 789</p>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- FOOTER -->
<footer class="footer">
  <p>© 2026 Una Beach Restaurant. All rights reserved.</p>
  <small>Authentic Sri Lankan Cuisine by the Beach</small>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/main.js"></script>
</body>
</html>
