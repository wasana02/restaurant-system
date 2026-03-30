/* ================================================
   contact.js — Contact page scripts
   Una Beach Restaurant
   Handles: character counter, form validation,
            success toast notification
================================================ */


// Character counter for the message textarea (max 500)
var msgTextarea = document.getElementById('cMessage');
var charDisplay = document.getElementById('charCount');
var MAX_CHARS   = 500;

if (msgTextarea && charDisplay) {
  msgTextarea.addEventListener('input', function () {
    var len = msgTextarea.value.length;

    if (len > MAX_CHARS) {
      msgTextarea.value = msgTextarea.value.substring(0, MAX_CHARS);
      len = MAX_CHARS;
    }

    charDisplay.textContent = len + ' / ' + MAX_CHARS + ' characters';
    charDisplay.style.color = (len >= MAX_CHARS * 0.9) ? '#dc3545' : '';
  });
}


// Send message — validates fields then shows success toast
function sendMessage() {
  var name    = document.getElementById('cName').value.trim();
  var email   = document.getElementById('cEmail').value.trim();
  var message = document.getElementById('cMessage').value.trim();

  if (!name || !email || !message) {
    alert('Please fill in all required fields.');
    return;
  }

  // Basic email format check
  var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailPattern.test(email)) {
    alert('Please enter a valid email address.');
    return;
  }

  // Clear form after successful send
  document.getElementById('cName').value    = '';
  document.getElementById('cPhone').value   = '';
  document.getElementById('cEmail').value   = '';
  document.getElementById('cSubject').value = '';
  document.getElementById('cMessage').value = '';
  if (charDisplay) {
    charDisplay.textContent = '0 / ' + MAX_CHARS + ' characters';
    charDisplay.style.color = '';
  }

  // Show green toast for 3 seconds
  var toast = document.getElementById('successToast');
  if (toast) {
    toast.classList.add('show');
    setTimeout(function () { toast.classList.remove('show'); }, 3000);
  }
}