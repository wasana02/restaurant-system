/* ================================================
   menu.js — Menu page scripts
   Una Beach Restaurant
   Handles: menu data, cart, live search,
            takeaway checkout, dine-in booking
================================================ */


// ============================================================
// MENU DATA
// ============================================================
var menuData = {
  starters: [
    { id: 1,  name: "Chicken Cutlets",   price: 100, img: "images/food1.jpg" },
    { id: 2,  name: "Parippu Wade",      price: 50,  img: "images/food2.jpg" },
    { id: 3,  name: "Vegetable Rolls",   price: 150, img: "images/food3.jpg" },
    { id: 4,  name: "Fish Cutlets",      price: 50,  img: "images/food4.jpg" },
    { id: 5,  name: "Vegetable Roti",    price: 100, img: "images/food5.jpg" },
    { id: 6,  name: "Egg Roti",          price: 120, img: "images/food6.jpg" },
    { id: 7,  name: "Udu Wade",          price: 50,  img: "images/food7.jpg" },
    { id: 8,  name: "Vegetable Samosa",  price: 50,  img: "images/food8.jpg" }
  ],
  ricecurry: [
    { id: 9,  name: "Steamed White Rice",       price: 500, img: "images/Main1.jpg" },
    { id: 10, name: "Red Rice",                 price: 350, img: "images/Main2.jpg" },
    { id: 11, name: "Yellow Rice",              price: 450, img: "images/Main3.jpg" },
    { id: 12, name: "Chicken Curry",            price: 250, img: "images/Main4.jpg" },
    { id: 13, name: "Fish Curry (Chili/Sour)",  price: 250, img: "images/Main5.jpg" },
    { id: 14, name: "Pork Black Curry",         price: 250, img: "images/Main6.jpg" },
    { id: 15, name: "Fish Ambul Thiyal",        price: 150, img: "images/Main7.jpg" }
  ],
  friednoodles: [
    { id: 16, name: "Vegetable Fried Rice", price: 900,  img: "images/Rice1.jpg" },
    { id: 17, name: "Egg Fried Rice",       price: 1000, img: "images/Rice2.jpg" },
    { id: 18, name: "Chicken Fried Rice",   price: 1100, img: "images/Rice3.jpg" },
    { id: 19, name: "Seafood Fried Rice",   price: 1250, img: "images/Rice4.jpg" },
    { id: 20, name: "Vegetable Noodles",    price: 1250, img: "images/Rice5.jpg" },
    { id: 21, name: "Chicken Noodles",      price: 1300, img: "images/Rice6.jpg" },
    { id: 22, name: "Seafood Noodles",      price: 1500, img: "images/Rice7.jpg" }
  ],
  kottu: [
    { id: 23, name: "Vegetable Kottu",              price: 1000, img: "images/kottu1.jpg" },
    { id: 24, name: "Chicken Kottu",                price: 1100, img: "images/kottu2.jpg" },
    { id: 25, name: "Chicken Cheese Kottu",         price: 1200, img: "images/kottu3.jpg" },
    { id: 26, name: "Cheese Kottu",                 price: 1300, img: "images/kottu4.jpg" },
    { id: 27, name: "Seafood Kottu",                price: 1500, img: "images/kottu5.jpg" },
    { id: 28, name: "Mixed Kottu (Chk & Seafood)",  price: 1600, img: "images/kottu6.jpg" },
    { id: 29, name: "Chicken Cheese Kottu Special", price: 1450, img: "images/kottu7.jpg" }
  ],
  desserts: [
    { id: 30, name: "Watalappan",                    price: 250, img: "images/dessert1.jpg" },
    { id: 31, name: "Curd & Kithul Treacle",          price: 350, img: "images/dessert2.jpg" },
    { id: 32, name: "Milk Toffee",                    price: 150, img: "images/dessert3.jpg" },
    { id: 33, name: "Fruit Salad",                    price: 250, img: "images/dessert4.jpg" },
    { id: 34, name: "Ice Cream (Vanilla/Chocolate)",  price: 150, img: "images/dessert5.jpg" }
  ],
  beverages: [
    { id: 35, name: "Tea (Plain / Milk)", price: 150, img: "images/be1.jpg" },
    { id: 36, name: "Ceylon Coffee",      price: 200, img: "images/be2.jpg" },
    { id: 37, name: "Soft Drinks",        price: 250, img: "images/be3.jpg" }
  ]
};


// ============================================================
// CART
// ============================================================
var cart = JSON.parse(localStorage.getItem('unaCart') || '[]');

function saveCart() {
  localStorage.setItem('unaCart', JSON.stringify(cart));
}

function addToCart(id) {
  var allItems = [];
  Object.values(menuData).forEach(function (cat) {
    cat.forEach(function (item) { allItems.push(item); });
  });

  var item = allItems.find(function (i) { return i.id === id; });
  if (!item) return;

  var existing = cart.find(function (c) { return c.id === id; });
  if (existing) {
    existing.qty++;
  } else {
    cart.push({ id: item.id, name: item.name, price: item.price, img: item.img, qty: 1 });
  }

  saveCart();
  updateCartUI();

  var btn = document.querySelector('[data-id="' + id + '"]');
  if (btn) {
    btn.textContent = '✓ Added';
    btn.classList.add('added');
    setTimeout(function () {
      btn.textContent = '+ ADD';
      btn.classList.remove('added');
    }, 1000);
  }
}

function changeQty(id, delta) {
  var item = cart.find(function (c) { return c.id === id; });
  if (!item) return;
  item.qty += delta;
  if (item.qty <= 0) {
    cart = cart.filter(function (c) { return c.id !== id; });
  }
  saveCart();
  updateCartUI();
}

function getSubtotal() {
  var total = 0;
  cart.forEach(function (c) { total += c.price * c.qty; });
  return total;
}

function updateCartUI() {
  var totalQty = 0;
  cart.forEach(function (c) { totalQty += c.qty; });
  document.getElementById('cartBadge').textContent = totalQty;

  var cartEmpty   = document.getElementById('cartEmpty');
  var cartItems   = document.getElementById('cartItems');
  var cartSummary = document.getElementById('cartSummary');

  if (cart.length === 0) {
    cartEmpty.style.display   = 'block';
    cartSummary.style.display = 'none';
    cartItems.innerHTML       = '';
    return;
  }

  cartEmpty.style.display   = 'none';
  cartSummary.style.display = 'block';

  var html = '';
  cart.forEach(function (c) {
    html +=
      '<div class="cart-item-row">' +
        '<img class="cart-item-img" src="' + c.img + '" alt="' + c.name + '"/>' +
        '<div class="cart-item-info">' +
          '<div class="cart-item-name">' + c.name + '</div>' +
          '<div class="cart-item-price">LKR ' + (c.price * c.qty).toLocaleString() + '</div>' +
        '</div>' +
        '<div class="qty-controls">' +
          '<button class="qty-btn" onclick="changeQty(' + c.id + ', -1)">−</button>' +
          '<span class="qty-num">' + c.qty + '</span>' +
          '<button class="qty-btn" onclick="changeQty(' + c.id + ', +1)">+</button>' +
        '</div>' +
      '</div>';
  });
  cartItems.innerHTML = html;

  var sub = getSubtotal();
  document.getElementById('cartSubtotal').textContent = 'LKR ' + sub.toLocaleString();
  document.getElementById('cartTotal').textContent    = 'LKR ' + sub.toLocaleString();
}

function toggleCart() {
  var sidebar = document.getElementById('cartSidebar');
  var overlay = document.getElementById('cartOverlay');
  var isOpen  = sidebar.classList.toggle('show');
  overlay.classList.toggle('show', isOpen);
  document.body.style.overflow = isOpen ? 'hidden' : '';
}

// Helper — builds the order summary HTML for any modal
function buildOrderSummaryHtml(includeDelivery) {
  var html = '';
  cart.forEach(function (c) {
    html +=
      '<div class="d-flex justify-content-between mb-1">' +
        '<span>' + c.name + ' × ' + c.qty + '</span>' +
        '<span>LKR ' + (c.price * c.qty).toLocaleString() + '</span>' +
      '</div>';
  });
  return html;
}


// ============================================================
// TAKEAWAY MODAL
// ============================================================
function openTakeaway() {
  if (cart.length === 0) return;

  // Require login before proceeding
  if (!isLoggedIn) {
    if (confirm('You need to be logged in to place an order.\n\nClick OK to go to the Login page.')) {
      window.location.href = loginUrl;
    }
    return;
  }

  var sub = getSubtotal();

  document.getElementById('takeawayItems').innerHTML    = buildOrderSummaryHtml();
  document.getElementById('takeawaySubtotal').textContent = 'LKR ' + sub.toLocaleString();
  document.getElementById('takeawayTotal').textContent    = 'LKR ' + (sub + 200).toLocaleString();

  // Reset form fields
  document.getElementById('tName').value    = '';
  document.getElementById('tPhone').value   = '';
  document.getElementById('tAddress').value = '';

  document.getElementById('takeawayForm').style.display      = 'block';
  document.getElementById('takeawayConfirmed').style.display = 'none';

  toggleCart();
  new bootstrap.Modal(document.getElementById('takeawayModal')).show();
}

function placeTakeaway() {
  var name    = document.getElementById('tName').value.trim();
  var phone   = document.getElementById('tPhone').value.trim();
  var address = document.getElementById('tAddress').value.trim();

  if (!name || !phone || !address) {
    alert('Please fill in all required fields.');
    return;
  }

  var phonePattern = /^[0-9+\-\s]{7,15}$/;
  if (!phonePattern.test(phone)) {
    alert('Please enter a valid phone number (numbers only).');
    return;
  }

  var sub     = getSubtotal();
  var payload = {
    order_type:    'takeaway',
    customer_name: name,
    phone:         phone,
    address:       address,
    items:         cart,
    total:         sub + 200
  };

  fetch('place_order.php', {
    method:  'POST',
    headers: { 'Content-Type': 'application/json' },
    body:    JSON.stringify(payload)
  })
  .then(function(r) { return r.json(); })
  .then(function(res) {
    if (res.success) {
      document.getElementById('takeawayForm').style.display      = 'none';
      document.getElementById('takeawayConfirmed').style.display = 'block';
    } else {
      alert('Could not save order: ' + (res.message || 'Unknown error'));
    }
  })
  .catch(function() {
    alert('Network error. Please try again.');
  });
}

function resetTakeaway() {
  cart = [];
  saveCart();
  updateCartUI();
  bootstrap.Modal.getInstance(document.getElementById('takeawayModal')).hide();
}


// ============================================================
// DINE IN MODAL
// ============================================================
var currentSeating = '';

function selectSeating(type) {
  currentSeating = type;
  document.getElementById('btnIndoor').classList.toggle('active',  type === 'indoor');
  document.getElementById('btnOutdoor').classList.toggle('active', type === 'outdoor');
}

function openDineIn() {
  if (cart.length === 0) return;

  // Require login before proceeding
  if (!isLoggedIn) {
    if (confirm('You need to be logged in to book a table.\n\nClick OK to go to the Login page.')) {
      window.location.href = loginUrl;
    }
    return;
  }

  var sub = getSubtotal();

  document.getElementById('dineinItems').innerHTML    = buildOrderSummaryHtml();
  document.getElementById('dineinTotal').textContent  = 'LKR ' + sub.toLocaleString();

  // Reset all dine-in fields
  document.getElementById('dName').value          = '';
  document.getElementById('dPhone').value         = '';
  document.getElementById('partySize').value      = '';
  document.getElementById('bookingDate').value    = '';
  document.getElementById('bookingTime').value    = '';
  document.getElementById('specialRequests').value = '';

  // Reset seating buttons
  currentSeating = '';
  document.getElementById('btnIndoor').classList.remove('active');
  document.getElementById('btnOutdoor').classList.remove('active');

  document.getElementById('dineinForm').style.display      = 'block';
  document.getElementById('dineinConfirmed').style.display = 'none';

  toggleCart();
  new bootstrap.Modal(document.getElementById('dineinModal')).show();
}

function placeDineIn() {
  var name  = document.getElementById('dName').value.trim();
  var phone = document.getElementById('dPhone').value.trim();

  if (!name || !phone) {
    alert('Please enter your name and phone number.');
    return;
  }

  // Phone must contain numbers only (allows +, spaces, dashes)
  var phonePattern = /^[0-9+\-\s]{7,15}$/;
  if (!phonePattern.test(phone)) {
    alert('Please enter a valid phone number (numbers only).');
    return;
  }

  if (!document.getElementById('partySize').value) {
    alert('Please select your party size.');
    return;
  }

  if (!currentSeating) {
    alert('Please select Indoor or Outdoor seating.');
    return;
  }

  var date = document.getElementById('bookingDate').value;
  var time = document.getElementById('bookingTime').value;

  if (!date || !time) {
    alert('Please select your booking date and time.');
    return;
  }

  // Prevent past date bookings
  var today  = new Date();
  today.setHours(0, 0, 0, 0);
  var chosen = new Date(date);
  if (chosen < today) {
    alert('Please select a future date for your booking.');
    return;
  }

  // Restaurant is open 10:00 AM to 11:00 PM only
  var parts      = time.split(':');
  var timeInMins = parseInt(parts[0], 10) * 60 + parseInt(parts[1], 10);
  if (timeInMins < 600 || timeInMins > 1380) {
    alert('We are open from 10:00 AM to 11:00 PM. Please select a valid time.');
    return;
  }

  document.getElementById('dineinForm').style.display      = 'none';
  document.getElementById('dineinConfirmed').style.display = 'block';

  var sub     = getSubtotal();
  var payload = {
    order_type:    'dinein',
    customer_name: name,
    phone:         phone,
    party_size:    document.getElementById('partySize').value,
    seating:       currentSeating,
    booking_date:  date,
    booking_time:  time,
    special_note:  document.getElementById('specialRequests').value.trim(),
    items:         cart,
    total:         sub
  };

  fetch('place_order.php', {
    method:  'POST',
    headers: { 'Content-Type': 'application/json' },
    body:    JSON.stringify(payload)
  })
  .then(function(r) { return r.json(); })
  .then(function(res) {
    if (!res.success) {
      console.warn('Order save failed:', res.message);
    }
  })
  .catch(function() {
    console.warn('Network error saving dine-in order.');
  });
}

function resetDineIn() {
  cart = [];
  saveCart();
  updateCartUI();
  bootstrap.Modal.getInstance(document.getElementById('dineinModal')).hide();
}


// ============================================================
// RENDER MENU CARDS
// ============================================================
function renderCategory(items, rowId) {
  var row = document.getElementById(rowId);
  if (!row) return;

  var html = '';
  items.forEach(function (item) {
    html +=
      '<div class="col-6 col-md-4 col-lg-3">' +
        '<div class="food-card">' +
          '<img src="' + item.img + '" alt="' + item.name + '" loading="lazy" ' +
               'onerror="this.src=\'https://placehold.co/300x155/C0622A/FFF?text=Food\'"/>' +
          '<div class="food-card-body">' +
            '<div class="food-name">' + item.name + '</div>' +
            '<div class="food-price">LKR ' + item.price.toLocaleString() + '</div>' +
            '<button class="btn-add" data-id="' + item.id + '" ' +
                    'onclick="addToCart(' + item.id + ')">+ ADD</button>' +
          '</div>' +
        '</div>' +
      '</div>';
  });

  row.innerHTML = html;
}


// ============================================================
// LIVE SEARCH
// ============================================================
var categoryRowMap = {
  starters:     'starters-row',
  ricecurry:    'ricecurry-row',
  friednoodles: 'friednoodles-row',
  kottu:        'kottu-row',
  desserts:     'desserts-row',
  beverages:    'beverages-row'
};

function filterMenu(query) {
  query = query.toLowerCase().trim();
  document.getElementById('clearBtn').style.display = query ? 'block' : 'none';

  var anyMatch = false;

  Object.keys(categoryRowMap).forEach(function (category) {
    var row = document.getElementById(categoryRowMap[category]);
    if (!row) return;

    var cards            = row.querySelectorAll('.col-6');
    var categoryHasMatch = false;

    cards.forEach(function (card) {
      var nameEl  = card.querySelector('.food-name');
      var matches = nameEl && nameEl.textContent.toLowerCase().indexOf(query) !== -1;

      if (!query || matches) {
        card.style.display = '';
        categoryHasMatch   = true;
        anyMatch           = true;
      } else {
        card.style.display = 'none';
      }
    });

    var header = row.previousElementSibling;
    if (header && header.classList.contains('category-header')) {
      header.style.display = categoryHasMatch ? '' : 'none';
    }
  });

  var noResults = document.getElementById('noResults');
  if (noResults) noResults.style.display = anyMatch ? 'none' : 'block';
}

function clearSearch() {
  document.getElementById('menuSearch').value = '';
  filterMenu('');
}


// ============================================================
// INIT
// ============================================================
document.addEventListener('DOMContentLoaded', function () {
  renderCategory(menuData.starters,     'starters-row');
  renderCategory(menuData.ricecurry,    'ricecurry-row');
  renderCategory(menuData.friednoodles, 'friednoodles-row');
  renderCategory(menuData.kottu,        'kottu-row');
  renderCategory(menuData.desserts,     'desserts-row');
  renderCategory(menuData.beverages,    'beverages-row');
  updateCartUI();

  // Set today as the minimum selectable date in the dine-in form
  var dateInput = document.getElementById('bookingDate');
  if (dateInput) {
    dateInput.setAttribute('min', new Date().toISOString().split('T')[0]);
  }
});