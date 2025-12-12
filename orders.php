<!DOCTYPE html>
<html lang="hr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Moje Narudžbe</title>
  <link rel="stylesheet" href="css.css" />
  <style>
    .orders-container {
      max-width: 1000px;
      margin: 20px auto;
      background: white;
      padding: 20px;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      flex: 1;
      width: 100%;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    table th, table td {
      border: 1px solid #ddd;
      padding: 15px;
      text-align: left;
    }
    table th {
      background: #f0f0f0;
    }
    .status-pending { color: #ffc107; }
    .status-confirmed { color: #17a2b8; }
    .status-shipped { color: #007bff; }
    .status-delivered { color: #28a745; }
    .status-cancelled { color: #dc3545; }
    .btn {
      padding: 6px 12px;
      background: #007bff;
      color: white;
      border: none;
      border-radius: 3px;
      cursor: pointer;
      text-decoration: none;
    }
    .btn:hover {
      background: #0056b3;
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
    <div class="orders-container">
      <h1>Moje Narudžbe</h1>
      
      <div id="ordersContent"></div>
    </div>
  </main>

  <script src="navbar.js"></script>
  <script>
    let currentUser = null;

    // Provjeri je li korisnik prijavljen
    fetch("session_user.php")
      .then(res => res.json())
      .then(user => {
        if (!user) {
          alert("Morate biti prijavljeni!");
          window.location.href = 'model.php';
        }
        currentUser = user;
        loadOrders();
      });

    function loadOrders() {
      fetch('order_api.php?action=get_user_orders')
        .then(res => res.json())
        .then(orders => {
          const content = document.getElementById('ordersContent');
          
          if (orders.length === 0) {
            content.innerHTML = `
              <div class="empty-message">
                <p>Nemate ni jednu narudžbu.</p>
                <p><a href="categories.php">Započnite sa kupnjom →</a></p>
              </div>
            `;
            return;
          }

          let html = `
            <table>
              <thead>
                <tr>
                  <th>Broj narudžbe</th>
                  <th>Datum</th>
                  <th>Ukupna cijena</th>
                  <th>Status</th>
                  <th>Dostava na</th>
                  <th>Akcije</th>
                </tr>
              </thead>
              <tbody>
          `;

          orders.forEach(order => {
            html += `
              <tr>
                <td>#${order.narudzba_id}</td>
                <td>${new Date(order.datum_narudzbe).toLocaleDateString('hr-HR')}</td>
                <td>€${parseFloat(order.ukupna_cijena).toFixed(2)}</td>
                <td class="status-${order.status}">${translateStatus(order.status)}</td>
                <td>${order.grad || '-'}</td>
                <td>
                  <button class="btn" onclick="viewDetails(${order.narudzba_id})">Detalji</button>
                </td>
              </tr>
            `;
          });

          html += `
              </tbody>
            </table>
          `;

          content.innerHTML = html;
        });
    }

    function viewDetails(orderId) {
      fetch(`order_api.php?action=get_order_items&order_id=${orderId}`)
        .then(res => res.json())
        .then(items => {
          let html = '<h3>Stavke u narudžbi:</h3><table><thead><tr><th>Proizvod</th><th>Količina</th><th>Cijena</th><th>Ukupno</th></tr></thead><tbody>';
          
          items.forEach(item => {
            const total = (item.kolicina * item.cena).toFixed(2);
            html += `
              <tr>
                <td>${item.naziv}</td>
                <td>${item.kolicina}</td>
                <td>€${parseFloat(item.cena).toFixed(2)}</td>
                <td>€${total}</td>
              </tr>
            `;
          });

          html += '</tbody></table>';
          alert(html);
        });
    }

    function translateStatus(status) {
      const statusMap = {
        'Na čekanju': 'Na čekanju',
        'U obradi': 'U obradi',
        'Poslano': 'Poslano',
        'Dostavljeno': 'Dostavljeno',
        'Otkazano': 'Otkazano'
      };
      return statusMap[status] || status;
    }
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
