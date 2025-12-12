<!DOCTYPE html>
<html lang="hr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Košarica</title>
  <link rel="stylesheet" href="css.css" />
  <style>
    .cart-container {
      max-width: 1000px;
      margin: 20px auto;
      display: grid;
      grid-template-columns: 1fr 300px;
      gap: 20px;
      flex: 1;
      width: 100%;
    }
    .cart-items {
      background: white;
      padding: 20px;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .cart-summary {
      background: #f0f0f0;
      padding: 20px;
      border-radius: 5px;
      height: fit-content;
    }
    .cart-item {
      display: grid;
      grid-template-columns: 100px 1fr 100px 80px;
      gap: 15px;
      align-items: center;
      padding: 15px;
      border-bottom: 1px solid #ddd;
    }
    .cart-item:last-child {
      border-bottom: none;
    }
    .cart-item-image {
      width: 100px;
      height: 100px;
      background: #f0f0f0;
      border-radius: 3px;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .cart-item-info h3 {
      margin: 0;
      font-size: 16px;
    }
    .cart-item-info p {
      margin: 5px 0 0 0;
      color: #666;
      font-size: 14px;
    }
    .cart-item-quantity {
      display: flex;
      align-items: center;
      gap: 5px;
    }
    .cart-item-quantity input {
      width: 50px;
      padding: 5px;
      border: 1px solid #ddd;
    }
    .cart-item-price {
      text-align: right;
      font-weight: bold;
    }
    .btn-remove {
      background: #dc3545;
      color: white;
      border: none;
      padding: 5px 10px;
      border-radius: 3px;
      cursor: pointer;
      font-size: 12px;
    }
    .summary-row {
      display: flex;
      justify-content: space-between;
      margin: 10px 0;
      padding: 10px 0;
    }
    .summary-row.total {
      border-top: 2px solid #ddd;
      font-size: 18px;
      font-weight: bold;
    }
    .checkout-btn {
      width: 100%;
      padding: 12px;
      background: #28a745;
      color: white;
      border: none;
      border-radius: 3px;
      cursor: pointer;
      font-size: 16px;
      margin-top: 20px;
    }
    .checkout-btn:hover {
      background: #218838;
    }
    .checkout-btn:disabled {
      background: #ccc;
      cursor: not-allowed;
    }
    .empty-message {
      text-align: center;
      padding: 40px;
      color: #666;
    }
    .empty-message a {
      color: #007bff;
      text-decoration: none;
    }
  </style>
</head>
<body>
  <!-- Navigacija -->
  <div id="navbar"></div>

  <main class="content">
    <h1>Vaša Košarica</h1>

    <div class="cart-container">
      <div class="cart-items">
        <div id="cartContent"></div>
      </div>

      <div class="cart-summary">
        <h3>Sažetak narudžbe</h3>
        <div id="cartSummary">
          <div class="summary-row">
            <span>Podukupno:</span>
            <span id="subtotal">€0.00</span>
          </div>
          <div class="summary-row">
            <span>Poštarina:</span>
            <span id="shipping">€0.00</span>
          </div>
          <div class="summary-row total">
            <span>Ukupno:</span>
            <span id="total">€0.00</span>
          </div>
        </div>
        <button class="checkout-btn" id="checkoutBtn">Idi na naplatu</button>
      </div>
    </div>

    <!-- Modal: Checkout -->
    <div id="checkoutModal" class="modal">
      <div class="modal-content">
        <span class="close" id="closeCheckoutModal">&times;</span>
        <h2>Naplata i Dostava</h2>
        
        <form id="checkoutForm">
          <div class="form-group">
            <label for="deliveryAddress">Adresa dostave:</label>
            <input type="text" id="deliveryAddress" required />
          </div>
          <div class="form-group">
            <label for="deliveryCity">Grad:</label>
            <input type="text" id="deliveryCity" required />
          </div>
          <div class="form-group">
            <label for="deliveryZip">Poštanski broj:</label>
            <input type="text" id="deliveryZip" required />
          </div>
          <div class="form-group">
            <label for="phone">Telefonski broj:</label>
            <input type="tel" id="phone" required />
          </div>
          <div class="form-group">
            <h3>Način plaćanja</h3>
            <label>
              <input type="radio" name="payment" value="card" checked /> Kreditna kartица
            </label>
            <label>
              <input type="radio" name="payment" value="bank" /> Bankovni transfer
            </label>
            <label>
              <input type="radio" name="payment" value="cash" /> Pouzećem
            </label>
          </div>
          <button type="submit" class="btn btn-success">Potvrdi narudžbu</button>
        </form>
      </div>
    </div>
  </main>

  <script src="navbar.js"></script>
  <script>
    let currentUser = null;
    let cartItems = [];

    // Provjeri je li korisnik prijavljen
    fetch("session_user.php")
      .then(res => res.json())
      .then(user => {
        if (!user) {
          alert("Morate biti prijavljeni!");
          window.location.href = 'model.php';
        }
        currentUser = user;
        loadCart();
      });

    function loadCart() {
      fetch('cart_api.php?action=get')
        .then(res => res.json())
        .then(items => {
          cartItems = items;
          renderCart();
          updateSummary();
        });
    }

    function renderCart() {
      const cartContent = document.getElementById('cartContent');
      
      if (cartItems.length === 0) {
        cartContent.innerHTML = `
          <div class="empty-message">
            <p>Vaša košarica je prazna.</p>
            <p><a href="categories.php">Nastavite sa kupnjom →</a></p>
          </div>
        `;
        document.getElementById('checkoutBtn').disabled = true;
        return;
      }

      let html = '';
      cartItems.forEach(item => {
        const itemTotal = (item.cijena * item.kolicina).toFixed(2);
        html += `
          <div class="cart-item">
            <div class="cart-item-image">
              ${item.slika ? `<img src="${item.slika}" alt="${item.naziv}">` : '[Slika]'}
            </div>
            <div class="cart-item-info">
              <h3>${item.naziv}</h3>
              <p>€${parseFloat(item.cijena).toFixed(2)}</p>
            </div>
            <div class="cart-item-quantity">
              <input type="number" value="${item.kolicina}" min="1" onchange="updateQuantity(${item.id}, this.value)" />
            </div>
            <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 10px;">
              <div class="cart-item-price">€${itemTotal}</div>
              <button class="btn-remove" onclick="removeFromCart(${item.id})">Obriši</button>
            </div>
          </div>
        `;
      });
      
      cartContent.innerHTML = html;
      document.getElementById('checkoutBtn').disabled = false;
    }

    function updateQuantity(cartId, quantity) {
      quantity = parseInt(quantity);
      if (quantity <= 0) {
        removeFromCart(cartId);
        return;
      }

      const formData = new FormData();
      formData.append('action', 'update');
      formData.append('cart_id', cartId);
      formData.append('quantity', quantity);

      fetch('cart_api.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
          loadCart();
        });
    }

    function removeFromCart(cartId) {
      const formData = new FormData();
      formData.append('action', 'remove');
      formData.append('cart_id', cartId);

      fetch('cart_api.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
          loadCart();
        });
    }

    function updateSummary() {
      const subtotal = cartItems.reduce((sum, item) => sum + (item.cijena * item.kolicina), 0);
      const shipping = subtotal > 100 ? 0 : 5;
      const total = subtotal + shipping;

      document.getElementById('subtotal').textContent = '€' + subtotal.toFixed(2);
      document.getElementById('shipping').textContent = '€' + shipping.toFixed(2);
      document.getElementById('total').textContent = '€' + total.toFixed(2);
    }

    // Checkout modal
    const checkoutModal = document.getElementById('checkoutModal');
    document.getElementById('checkoutBtn').addEventListener('click', () => {
      checkoutModal.style.display = 'block';
    });

    document.getElementById('closeCheckoutModal').addEventListener('click', () => {
      checkoutModal.style.display = 'none';
    });

    window.addEventListener('click', (e) => {
      if (e.target === checkoutModal) {
        checkoutModal.style.display = 'none';
      }
    });

    // Podnošenje narudžbe
    document.getElementById('checkoutForm').addEventListener('submit', async (e) => {
      e.preventDefault();

      const formData = new FormData();
      formData.append('action', 'create_order');
      formData.append('delivery_address', document.getElementById('deliveryAddress').value);
      formData.append('delivery_city', document.getElementById('deliveryCity').value);
      formData.append('delivery_zip', document.getElementById('deliveryZip').value);
      formData.append('phone', document.getElementById('phone').value);

      const res = await fetch('order_api.php', { method: 'POST', body: formData });
      const data = await res.json();

      if (res.ok) {
        alert('Narudžba je uspješno kreirani!');
        checkoutModal.style.display = 'none';
        window.location.href = 'orders.php';
      } else {
        alert('Greška: ' + data.error);
      }
    });
  </script>

  <!-- Footer -->
  <footer class="footer">
    <div class="footer-content">
      <div class="footer-section">
        <h3>Kontakt</h3>
        <p>Telefon: +385 1 234 5678</p>
        <p>E-mail: info@webshop.hr</p>
      </div>
      <div class="footer-section">
        <h3>Lokacija</h3>
        <p>Adresa: Primjer Ulica 123</p>
        <p>10000 Zagreb, Hrvatska</p>
      </div>
      <div class="footer-section">
        <h3>Radno vrijeme</h3>
        <p>Ponedjeljak - Petak: 09:00 - 17:00</p>
        <p>Subota: 10:00 - 14:00</p>
      </div>
    </div>
    <div class="footer-bottom">
      <p>&copy; 2025 Web Shop Računala. Sva prava pridržana.</p>
    </div>
  </footer>
</body>
</html>
