document.addEventListener("DOMContentLoaded", () => {
  const loginModal = document.getElementById("loginModal");
  const openLoginModal = document.getElementById("openLoginModal");
  const closeLoginModal = document.getElementById("closeLoginModal");
  const loginForm = document.getElementById("loginForm");
  // Otvori register modal iz login modala
  const openRegisterFromLogin = document.getElementById("openRegisterFromLogin");

openRegisterFromLogin.addEventListener("click", () => {
    // zatvori login modal
    loginModal.style.display = "none";

    // otvori register modal
    registerModal.style.display = "block";
});

  openLoginModal.onclick = () => {
    loginModal.style.display = "block";
  };

  closeLoginModal.onclick = () => {
    loginModal.style.display = "none";
  };

 loginForm.addEventListener("submit", async (event) => {

    // Spriječi reload stranice
    event.preventDefault();

    // Uzimanje podataka iz input polja
    const username = document.getElementById("loginUsername").value;
    const password = document.getElementById("loginPassword").value;

    // Formiranje podataka za slanje PHP-u
    const formData = new FormData();
    formData.append("username", username);
    formData.append("password", password);

    // Slanje POST zahtjeva prema login.php
    let res = await fetch("login.php", {
        method: "POST",
        body: formData,
    });

    // Tekstualni odgovor od PHP skripte
    let text = await res.text();

    // Provjera je li prijava uspjela
    if (text.trim() === "OK") {
        alert("Uspješna prijava!");
        loginModal.style.display = "none";
        location.reload();   // osvježi stranicu da se učitaju podaci za korisnika
    } 
    else {
        document.getElementById("loginError").textContent =
            "Pogrešno korisničko ime ili lozinka.";
    }
});


  window.onclick = (event) => {
    if (event.target === loginModal) {
      loginModal.style.display = "none";
    }
  };
});
