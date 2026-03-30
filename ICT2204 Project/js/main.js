/* ================================================
   main.js — Shared scripts (all pages)
   Una Beach Restaurant
   Handles: navbar scroll shadow, fade-up animation,
            cart badge sync from localStorage
================================================ */


// Navbar gets a deeper shadow once user scrolls past 40px
window.addEventListener('scroll', function () {
  var navbar = document.getElementById('mainNavbar');
  if (!navbar) return;
  navbar.classList.toggle('scrolled', window.scrollY > 40);
});


// Fade-up animation — each .fade-up element slides into view
// when it enters the viewport using IntersectionObserver
var fadeObserver = new IntersectionObserver(function (entries) {
  entries.forEach(function (entry) {
    if (entry.isIntersecting) {
      entry.target.classList.add('visible');
      fadeObserver.unobserve(entry.target);
    }
  });
}, { threshold: 0.15 });

document.querySelectorAll('.fade-up').forEach(function (el) {
  fadeObserver.observe(el);
});


// Read the cart from localStorage and update the badge in the navbar.
// This runs on every page so the count stays in sync.
function updateCartBadge() {
  var stored = localStorage.getItem('unaCart');
  var cart   = stored ? JSON.parse(stored) : [];
  var total  = 0;
  cart.forEach(function (item) { total += item.qty; });
  var badge = document.getElementById('cartBadge');
  if (badge) badge.textContent = total;
}

updateCartBadge();