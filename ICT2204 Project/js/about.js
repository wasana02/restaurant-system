/* ================================================
   about.js — About page scripts
   Una Beach Restaurant
   Handles: animated stat counters
================================================ */


// Counts up from 0 to target when stats scroll into view
function animateCounter(el, target, duration) {
  var start     = 0;
  var stepTime  = 16;
  var steps     = Math.ceil(duration / stepTime);
  var increment = Math.ceil(target / steps);

  var timer = setInterval(function () {
    start += increment;
    if (start >= target) {
      start = target;
      clearInterval(timer);
    }
    el.textContent = start.toLocaleString();
  }, stepTime);
}

// Watch for the stats section to enter the viewport
var statsObserver = new IntersectionObserver(function (entries) {
  entries.forEach(function (entry) {
    if (entry.isIntersecting) {
      var counters = entry.target.querySelectorAll('[data-target]');
      counters.forEach(function (counter) {
        var target = parseInt(counter.getAttribute('data-target'), 10);
        animateCounter(counter, target, 1500);
      });
      statsObserver.unobserve(entry.target);
    }
  });
}, { threshold: 0.3 });

var statsSection = document.querySelector('.stats-section');
if (statsSection) statsObserver.observe(statsSection);