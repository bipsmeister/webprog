<!DOCTYPE html>
<html lang="hr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Kategorije - Web Shop Računala</title>
  <link rel="stylesheet" href="css.css" />
  <style>
    .categories-container {
      max-width: 1200px;
      margin: 20px auto;
      flex: 1;
      width: 100%;
    }
    .categories-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 15px;
      margin: 30px 0;
    }
    .category-card {
      background: white;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      overflow: hidden;
      cursor: pointer;
      transition: transform 0.3s;
    }
    .category-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 5px 20px rgba(0,0,0,0.2);
    }
    .category-header {
      background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
      color: white;
      padding: 30px;
      text-align: center;
    }
    .category-header h3 {
      margin: 0;
      font-size: 20px;
    }
    .category-content {
      padding: 20px;
    }
    .category-content p {
      margin: 0 0 15px 0;
      color: #666;
      font-size: 14px;
    }
    .category-btn {
      background: #28a745;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 3px;
      cursor: pointer;
      width: 100%;
      font-size: 16px;
    }
    .category-btn:hover {
      background: #218838;
    }
    .products-container {
      max-width: 1200px;
      margin: 20px auto;
      flex: 1;
      width: 100%;
    }
    .products-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
      gap: 20px;
      margin: 20px 0;
    }
    .product-card {
      background: white;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      overflow: hidden;
      transition: transform 0.3s;
    }
    .product-card:hover {
      transform: translateY(-5px);
    }
    .product-image {
      width: 100%;
      height: 200px;
      background: #f0f0f0;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 12px;
      color: #999;
    }
    .product-info {
      padding: 15px;
    }
    .product-info h3 {
      margin: 0 0 10px 0;
      font-size: 16px;
    }
    .product-info p {
      margin: 0 0 10px 0;
      color: #666;
      font-size: 13px;
      max-height: 40px;
      overflow: hidden;
    }
    .product-footer {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding-top: 10px;
      border-top: 1px solid #eee;
    }
    .product-price {
      font-size: 18px;
      font-weight: bold;
      color: #007bff;
    }
    .btn-add-cart {
      background: #28a745;
      color: white;
      border: none;
      padding: 8px 12px;
      border-radius: 3px;
      cursor: pointer;
      font-size: 12px;
    }
    .btn-add-cart:hover {
      background: #218838;
    }
    .btn-back {
      background: #007bff;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 3px;
      cursor: pointer;
      font-size: 14px;
      margin-bottom: 20px;
    }
    .btn-back:hover {
      background: #0056b3;
    }
  </style>
</head>
<body>
  <!-- Navigacija -->
  <div id="navbar"></div>

  <main class="content">
    <div id="categoriesView" class="categories-container">
      <h1>Kategorije - Web Shop Računala</h1>
      <p>Odaberite kategoriju proizvoda koju vas zanima:</p>
      
      <div class="categories-grid" id="categoriesGrid"></div>
    </div>

    <div id="productsView" class="products-container" style="display: none;">
      <button class="btn-back" onclick="backToCategories()">← Povratak na kategorije</button>
      <h1 id="categoryTitle"></h1>
      <div class="products-grid" id="productsGrid"></div>
    </div>
  </main>

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

  <script src="navbar.js"></script>
  <script>
    let currentUser = null;

    // Provjeri je li korisnik prijavljen
    fetch("session_user.php")
      .then(res => res.json())
      .then(user => {
        currentUser = user;
      });

    function loadCategories() {
      // Učitaj kategorije iz API-ja
      fetch('get_products.php?action=get_categories')
        .then(res => res.json())
        .then(categories => {
          if (categories.length === 0) {
            // Fallback ako nema kategorija u bazi
            categories = [
              { id: 1, naziv: 'Računala', opis: 'Gaming računala, stolna i prijenosna računala' },
              { id: 2, naziv: 'Komponente', opis: 'Matične ploče, procesori, memorija i ostale komponente' },
              { id: 3, naziv: 'Mobiteli', opis: 'Sve vrste mobitela od najnovijih modela do povoljnih opcija' },
              { id: 4, naziv: 'Periferija', opis: 'Tipkovnice, miševi, slušalice, mikrofoni...' }
            ];
          }

          const grid = document.getElementById('categoriesGrid');
          grid.innerHTML = '';

          categories.forEach(category => {
            const card = document.createElement('div');
            card.className = 'category-card';
            card.onclick = () => loadProducts(category.id, category.naziv);
            card.innerHTML = `
              <div class="category-header">
                <h3>${category.naziv}</h3>
              </div>
              <div class="category-content">
                <p>${category.opis || 'Nema opisa'}</p>
                <button class="category-btn">Pogledaj proizvode</button>
              </div>
            `;
            grid.appendChild(card);
          });
        })
        .catch(err => {
          console.error('Greška pri učitavanju kategorija:', err);
          // Fallback kategorije
          const fallback = [
            { id: 1, naziv: 'Linkovi', opis: 'Računalni linkovi' },
            { id: 2, naziv: 'HG Spot', opis: 'LED rasvjeta' },
            { id: 3, naziv: 'Instar Informatika', opis: 'Komponente za informatiku' }
          ];
          
          const grid = document.getElementById('categoriesGrid');
          grid.innerHTML = '';
          fallback.forEach(category => {
            const card = document.createElement('div');
            card.className = 'category-card';
            card.onclick = () => loadProducts(category.id, category.naziv);
            card.innerHTML = `
              <div class="category-header">
                <h3>${category.naziv}</h3>
              </div>
              <div class="category-content">
                <p>${category.opis}</p>
                <button class="category-btn">Pogledaj proizvode</button>
              </div>
            `;
            grid.appendChild(card);
          });
        });
    }

    function loadProducts(categoryId, categoryName) {
      // API poziv za dohvatanje proizvoda
      fetch(`get_products.php?action=get_by_category&category_id=${categoryId}`)
        .then(res => res.json())
        .then(products => {
          document.getElementById('categoriesView').style.display = 'none';
          document.getElementById('productsView').style.display = 'block';
          document.getElementById('categoryTitle').textContent = categoryName;

          const grid = document.getElementById('productsGrid');
          grid.innerHTML = '';

          if (products.length === 0) {
            grid.innerHTML = '<p>Nema proizvoda u ovoj kategoriji.</p>';
            return;
          }

          products.forEach(product => {
            const card = document.createElement('div');
            card.className = 'product-card';
            card.innerHTML = `
              <div class="product-image">
                ${product.slika ? `<img src="${product.slika}" alt="${product.naziv}">` : '[Slika]'}
              </div>
              <div class="product-info">
                <h3>${product.naziv}</h3>
                <p>${product.opis || 'Nema opisa'}</p>
                <div class="product-footer">
                  <div class="product-price">€${parseFloat(product.cijena).toFixed(2)}</div>
                  <button class="btn-add-cart" onclick="addToCart(${product.proizvod_id})" ${!currentUser ? 'disabled title="Prijavite se da dodate u košaricu"' : ''}>U košaricu</button>
                </div>
              </div>
            `;
            grid.appendChild(card);
          });
        })
        .catch(err => {
          console.error('Greška pri dohvatanju proizvoda:', err);
          const grid = document.getElementById('productsGrid');
          grid.innerHTML = '<p>Greška pri učitavanju proizvoda.</p>';
        });
    }

    function backToCategories() {
      document.getElementById('categoriesView').style.display = 'block';
      document.getElementById('productsView').style.display = 'none';
    }

    function addToCart(productId) {
      if (!currentUser) {
        alert("Morate biti prijavljeni da dodate proizvod u košaricu!");
        return;
      }

      const formData = new FormData();
      formData.append('action', 'add');
      formData.append('product_id', productId);
      formData.append('quantity', 1);

      fetch('cart_api.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
          alert('Proizvod dodan u košaricu!');
        })
        .catch(err => {
          alert('Greška pri dodavanju u košaricu!');
        });
    }

    // Učitaj kategorije pri otvaranju
    loadCategories();
  </script>
</body>
</html>
