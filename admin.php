<!DOCTYPE html>
<html lang="hr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Panel</title>
  <link rel="stylesheet" href="css.css" />
  <style>
    .admin-container {
      display: grid;
      grid-template-columns: 250px 1fr;
      gap: 20px;
      margin: 20px;
      flex: 1;
    }
    .admin-sidebar {
      background: #f0f0f0;
      padding: 20px;
      border-radius: 5px;
    }
    .admin-sidebar button {
      display: block;
      width: 100%;
      margin: 10px 0;
      padding: 10px;
      background: #007bff;
      color: white;
      border: none;
      cursor: pointer;
      border-radius: 3px;
    }
    .admin-sidebar button:hover {
      background: #0056b3;
    }
    .admin-sidebar button.active {
      background: #28a745;
    }
    .admin-content {
      background: white;
      padding: 20px;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      flex: 1;
      display: flex;
      flex-direction: column;
    }
    .section {
      display: none;
    }
    .section.active {
      display: block;
    }
    .product-form, .order-table {
      margin: 20px 0;
    }
    .form-group {
      margin: 15px 0;
    }
    .form-group label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
    }
    .form-group input, .form-group textarea, .form-group select {
      width: 100%;
      padding: 8px;
      border: 1px solid #ddd;
      border-radius: 3px;
      font-size: 14px;
      box-sizing: border-box;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    table th, table td {
      border: 1px solid #ddd;
      padding: 10px;
      text-align: left;
    }
    table th {
      background: #f0f0f0;
    }
    .btn {
      padding: 6px 12px;
      margin: 2px;
      cursor: pointer;
      border: none;
      border-radius: 3px;
      font-size: 12px;
    }
    .btn-success {
      background: #28a745;
      color: white;
    }
    .btn-danger {
      background: #dc3545;
      color: white;
    }
    .btn-info {
      background: #17a2b8;
      color: white;
    }
    .btn-warning {
      background: #ffc107;
      color: black;
    }
    .status-pending { color: #ffc107; }
    .status-confirmed { color: #17a2b8; }
    .status-shipped { color: #007bff; }
    .status-delivered { color: #28a745; }
    .status-cancelled { color: #dc3545; }
  </style>
</head>
<body>
  <!-- Navigacija -->
  <div id="navbar"></div>

  <main class="content">
    <div class="admin-container">
      <div class="admin-sidebar">
        <h3>Admin Opcije</h3>
        <button id="productsBtn" class="active">Proizvodi</button>
        <button id="ordersBtn">Narudžbe</button>
        <button id="logoutBtn">Odjava</button>
      </div>

      <div class="admin-content">
      <!-- Sekcija: Proizvodi -->
      <div id="productsSection" class="section active">
        <h2>Upravljanje Proizvodima</h2>
        
        <div class="product-form">
          <h3>Dodaj novi proizvod</h3>
          <form id="addProductForm">
            <div class="form-group">
              <label for="categoryId">Kategorija:</label>
              <select id="categoryId" required>
                <option value="">Odaberi kategoriju</option>
              </select>
            </div>
            <div class="form-group">
              <label for="productName">Naziv proizvoda:</label>
              <input type="text" id="productName" required />
            </div>
            <div class="form-group">
              <label for="productDescription">Opis:</label>
              <textarea id="productDescription" rows="4"></textarea>
            </div>
            <div class="form-group">
              <label for="productPrice">Cijena (€):</label>
              <input type="number" id="productPrice" step="0.01" required />
            </div>
            <div class="form-group">
              <label for="productStock">Količina na zalihi:</label>
              <input type="number" id="productStock" required />
            </div>
            <div class="form-group">
              <label for="productImage">URL slike:</label>
              <input type="text" id="productImage" />
            </div>
            <button type="submit" class="btn btn-success">Dodaj proizvod</button>
          </form>
        </div>

        <div id="productsList">
          <h3>Svi proizvodi</h3>
          <table id="productsTable">
            <thead>
              <tr>
                <th>Naziv</th>
                <th>Kategorija</th>
                <th>Cijena</th>
                <th>Zaliha</th>
                <th>Akcije</th>
              </tr>
            </thead>
            <tbody id="productsBody"></tbody>
          </table>
        </div>
      </div>

      <!-- Sekcija: Narudžbe -->
      <div id="ordersSection" class="section">
        <h2>Upravljanje Narudžbama</h2>
        
        <table id="ordersTable">
          <thead>
            <tr>
              <th>ID</th>
              <th>Korisnik</th>
              <th>Ukupna cijena</th>
              <th>Status</th>
              <th>Datum</th>
              <th>Akcije</th>
            </tr>
          </thead>
          <tbody id="ordersBody"></tbody>
        </table>

        <!-- Modal za detaljni pregled narudžbe -->
        <div id="orderDetailsModal" class="modal">
          <div class="modal-content">
            <span class="close" id="closeOrderModal">&times;</span>
            <h2>Detalji narudžbe</h2>
            <div id="orderDetails"></div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <script src="navbar.js"></script>
  <script>
    let currentUser = null;

    // Učitaj kategorije pri otvaranju
    function loadCategories() {
      fetch('get_products.php?action=get_categories')
        .then(res => res.json())
        .then(categories => {
          const select = document.getElementById('categoryId');
          categories.forEach(cat => {
            const option = document.createElement('option');
            option.value = cat.id;
            option.textContent = cat.naziv;
            select.appendChild(option);
          });
        });
    }

    // Provjeri je li korisnik admin
    fetch("session_user.php")
      .then(res => res.json())
      .then(user => {
        if (!user || user.is_admin !== true) {
          alert("Samo admini mogu pristupiti ovoj stranici!");
          window.location.href = 'model.php';
        }
        currentUser = user;
        loadCategories();
      });

    // Prebacivanje između sekcija
    document.getElementById('productsBtn').addEventListener('click', () => {
      showSection('products');
    });
    document.getElementById('ordersBtn').addEventListener('click', () => {
      showSection('orders');
    });
    document.getElementById('logoutBtn').addEventListener('click', () => {
      fetch("logout.php").then(() => {
        window.location.href = 'model.php';
      });
    });

    function showSection(section) {
      document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
      document.querySelectorAll('.admin-sidebar button').forEach(b => b.classList.remove('active'));
      
      if (section === 'products') {
        document.getElementById('productsSection').classList.add('active');
        document.getElementById('productsBtn').classList.add('active');
        loadProducts();
      } else if (section === 'orders') {
        document.getElementById('ordersSection').classList.add('active');
        document.getElementById('ordersBtn').classList.add('active');
        loadOrders();
      }
    }

    // Učitaj proizvode
    function loadProducts() {
      fetch('admin_api.php?action=get_products')
        .then(res => res.json())
        .then(products => {
          const tbody = document.getElementById('productsBody');
          tbody.innerHTML = '';
          
          products.forEach(product => {
            const row = document.createElement('tr');
            row.innerHTML = `
              <td>${product.naziv}</td>
              <td>${product.kategorija_id === 1 ? 'Linkovi' : product.kategorija_id === 2 ? 'HG Spot' : 'Instar Informatika'}</td>
              <td>€${parseFloat(product.cijena).toFixed(2)}</td>
              <td>${product.kolicina_na_skladistu}</td>
              <td>
                <button class="btn btn-info" onclick="editProduct(${product.proizvod_id})">Uredi</button>
                <button class="btn btn-danger" onclick="deleteProduct(${product.proizvod_id})">Obriši</button>
              </td>
            `;
            tbody.appendChild(row);
          });
        });
    }

    // Dodaj proizvod
    document.getElementById('addProductForm').addEventListener('submit', async (e) => {
      e.preventDefault();
      
      const formData = new FormData();
      formData.append('action', 'add_product');
      formData.append('category_id', document.getElementById('categoryId').value);
      formData.append('name', document.getElementById('productName').value);
      formData.append('description', document.getElementById('productDescription').value);
      formData.append('price', document.getElementById('productPrice').value);
      formData.append('stock', document.getElementById('productStock').value);
      formData.append('image', document.getElementById('productImage').value);
      
      const res = await fetch('admin_api.php', { method: 'POST', body: formData });
      const data = await res.json();
      
      if (res.ok) {
        alert('Proizvod dodan!');
        document.getElementById('addProductForm').reset();
        loadProducts();
      } else {
        alert('Greška: ' + data.error);
      }
    });

    // Obriši proizvod
    function deleteProduct(id) {
      if (confirm('Jeste li sigurni da želite obrisati ovaj proizvod?')) {
        const formData = new FormData();
        formData.append('action', 'delete_product');
        formData.append('product_id', id);
        
        fetch('admin_api.php', { method: 'POST', body: formData })
          .then(res => res.json())
          .then(data => {
            alert(data.message || data.error);
            loadProducts();
          });
      }
    }

    // Učitaj narudžbe
    function loadOrders() {
      fetch('admin_api.php?action=get_orders')
        .then(res => res.json())
        .then(orders => {
          const tbody = document.getElementById('ordersBody');
          tbody.innerHTML = '';
          
          orders.forEach(order => {
            const row = document.createElement('tr');
            row.innerHTML = `
              <td>${order.narudzba_id}</td>
              <td>${order.ime} ${order.prezime}</td>
              <td>€${parseFloat(order.ukupna_cijena).toFixed(2)}</td>
              <td class="status-${order.status}">${order.status}</td>
              <td>${new Date(order.datum_narudzbe).toLocaleDateString('hr-HR')}</td>
              <td>
                <button class="btn btn-info" onclick="viewOrderDetails(${order.narudzba_id})">Pregled</button>
                <select onchange="updateOrderStatus(${order.narudzba_id}, this.value)" class="btn btn-warning">
                  <option value="">Promijeni status</option>
                  <option value="U obradi">U obradi</option>
                  <option value="Poslano">Poslano</option>
                  <option value="Dostavljeno">Dostavljeno</option>
                  <option value="Otkazano">Otkazano</option>
                </select>
              </td>
            `;
            tbody.appendChild(row);
          });
        });
    }

    // Pregled detalja narudžbe
    function viewOrderDetails(orderId) {
      fetch(`admin_api.php?action=get_order_items&order_id=${orderId}`)
        .then(res => res.json())
        .then(items => {
          let html = '<table><thead><tr><th>Proizvod</th><th>Količina</th><th>Cijena</th><th>Ukupno</th></tr></thead><tbody>';
          items.forEach(item => {
            const total = (item.kolicina * item.cena).toFixed(2);
            html += `<tr>
              <td>${item.naziv}</td>
              <td>${item.kolicina}</td>
              <td>€${parseFloat(item.cena).toFixed(2)}</td>
              <td>€${total}</td>
            </tr>`;
          });
          html += '</tbody></table>';
          
          document.getElementById('orderDetails').innerHTML = html;
          document.getElementById('orderDetailsModal').style.display = 'block';
        });
    }

    // Ažuriranje statusa narudžbe
    function updateOrderStatus(orderId, status) {
      if (!status) return;
      
      const formData = new FormData();
      formData.append('action', 'update_order_status');
      formData.append('order_id', orderId);
      formData.append('status', status);
      
      fetch('admin_api.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
          alert(data.message || data.error);
          loadOrders();
        });
    }

    // Zatvaranje modala
    document.getElementById('closeOrderModal').addEventListener('click', () => {
      document.getElementById('orderDetailsModal').style.display = 'none';
    });

    window.addEventListener('click', (e) => {
      const modal = document.getElementById('orderDetailsModal');
      if (e.target === modal) {
        modal.style.display = 'none';
      }
    });

    // Učitaj proizvode pri otvaranju
    loadProducts();
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
