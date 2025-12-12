<!DOCTYPE html>
<html lang="hr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Web Shop Računala</title>
  <link rel="stylesheet" href="css.css" />
</head>
<body>
  <!-- Navigacija -->
  <div id="navbar"></div>

  <!-- Glavni sadržaj -->
  <main class="content">
    <h1>Informatika SPOT!</h1>
    <p>Vaš pouzdani web shop za sve računalne proizvode - od linkova do kompletnih računala.</p>
  </main>

  <!-- Modal: Registracija -->
  <div id="registerModal" class="modal">
    <div class="modal-content">
      <span class="close" id="closeRegisterModal">&times;</span>
      <h2>Registracija</h2>

      <form id="registerForm" method="post">
        <label for="ime">Ime:</label>
        <input type="text" id="ime" name="ime" required />

        <label for="prezime">Prezime:</label>
        <input type="text" id="prezime" name="prezime" required />

        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" required />

        <label for="password">Lozinka:</label>
        <input type="password" id="password" name="password" required />

        <label for="confirmPassword">Potvrdi lozinku:</label>
        <input type="password" id="confirmPassword" name="confirmPassword" required />

        <button type="submit">Registriraj se</button>
      </form>

      <div id="registerError" style="color: red; display: none;">
        Lozinke se ne podudaraju. Pokušajte ponovo.
      </div>
    </div>
  </div>

  <!-- Modal: Login -->
  <div id="loginModal" class="modal">
    <div class="modal-content">
      <span id="closeLoginModal" class="close">&times;</span>
      <h2>Prijavite se</h2>

      <form id="loginForm">
        <label for="loginUsername">E-mail:</label>
        <input type="email" id="loginUsername" name="username" required />

        <label for="loginPassword">Lozinka:</label>
        <input type="password" id="loginPassword" name="password" required />

        <button type="submit">Prijavi se</button>

        <!-- Novo dugme za registraciju -->
        <p>Nemate račun? 
          <button type="button" id="openRegisterFromLogin">Registriraj se</button>
        </p>

      </form>

      <p id="loginError" style="color: red;"></p>
    </div>
  </div>

  <!-- Modal: Korisnički profil -->
  <div id="userProfileModal" class="modal">
    <div class="modal-content">
      <span class="close" id="closeUserProfileModal">&times;</span>
      <h2>Moj Profil</h2>

      <p>Ime: <span id="imeDisplay"></span></p>
      <p>Prezime: <span id="prezimeDisplay"></span></p>
      <p>E-mail: <span id="emailDisplay"></span></p>
    </div>
  </div>

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

  <!-- Skripte -->
  <script src="navbar.js"></script>
  <script src="modals.js"></script>
  <script src="registerForm.js"></script>
  <script src="loginForm.js"></script>
</body>
</html>
