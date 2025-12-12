 const navbarContent = `
 <nav>
 <div class="navbar-left">
   <ul>
     <li><a href='model.php'>Početna</a></li>
     <li><a href='categories.php'>Kategorije</a></li>
     <li><a href='cart.php' id="cartLink">Košarica</a></li>
   </ul>
 </div>
 <div class="navbar-right">
   <button id="navbarLoginBtn">Prijava</button>
   <button id="openRegisterModal">Registracija</button>
 </div>
 </nav>
 `;
 document.getElementById('navbar').innerHTML = navbarContent;

 // Dodaj event listener za navbar login button
 const navbarLoginBtn = document.getElementById('navbarLoginBtn');
 if (navbarLoginBtn) {
   navbarLoginBtn.onclick = (e) => {
     e.preventDefault();
     const loginModal = document.getElementById('loginModal');
     if (loginModal) {
       loginModal.style.display = 'block';
     }
   };
 }

 fetch("session_user.php")
  .then(res => res.json())
  .then(user => {
    if (user) {
      const nav = document.querySelector('nav');
      const cartLink = document.getElementById("cartLink");
      cartLink.href = 'cart.php';
      
      // Ukloni login dugme
      const navbarLoginBtn = document.getElementById('navbarLoginBtn');
      if (navbarLoginBtn) {
        navbarLoginBtn.style.display = 'none';
      }
      
      // Dodaj link za narudžbe
      const navbarLeft = nav.querySelector('.navbar-left ul');
      const ordersLi = document.createElement('li');
      ordersLi.innerHTML = '<a href="orders.php">Moje narudžbe</a>';
      navbarLeft.appendChild(ordersLi);

      // Dodaj admin panel ako je admin (korisnik_id = 1)
      if (user.is_admin === true) {
        const adminLi = document.createElement('li');
        adminLi.innerHTML = '<a href="admin.php">Admin Panel</a>';
        navbarLeft.appendChild(adminLi);
      }

      // Dodaj user info desno
      const navbarRight = nav.querySelector('.navbar-right');
      navbarRight.innerHTML = 
        `<div class='user-info'>
            Prijavljeni ste kao: <b>${user.ime} ${user.prezime}</b>
            <a href="logout.php" style="margin-left: 10px; color: #f57c42; text-decoration: none;">Odjava</a>
         </div>`;
    }
  });
