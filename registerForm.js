document.addEventListener("DOMContentLoaded", () => {
  const registerModal = document.getElementById("registerModal");
  const openRegisterModal = document.getElementById("openRegisterModal");
  const closeRegisterModal = document.getElementById("closeRegisterModal");
  const registerForm = document.getElementById("registerForm");
  const registerError = document.getElementById("registerError");

  openRegisterModal.onclick = () => {
    registerModal.style.display = "block";
  };

  closeRegisterModal.onclick = () => {
    registerModal.style.display = "none";
  };

registerForm.addEventListener("submit", async (event) => {
    
    // Spriječi refresh stranice
    event.preventDefault();

    // Uzimanje vrijednosti iz input polja
    const ime            = document.getElementById("ime").value;
    const prezime        = document.getElementById("prezime").value;
    const email          = document.getElementById("email").value;
    const password       = document.getElementById("password").value;
    const confirmPassword = document.getElementById("confirmPassword").value;

    // Provjera lozinki
    if (password !== confirmPassword) {
        registerError.style.display = "block";
        return;
    }

    // Sakrij grešku ako je bila prikazana ranije
    registerError.style.display = "none";

    // Formiranje podataka koji se šalju na server
    const formData = new FormData();
    formData.append("ime", ime);
    formData.append("prezime", prezime);
    formData.append("email", email);
    formData.append("password", password);

    // Slanje podataka na register.php
    let res = await fetch("register.php", {
        method: "POST",
        body: formData,
    });

    // Odgovor od PHP-a
    let text = await res.text();

    // Ako PHP vrati "OK" → registracija je uspjela
    if (text.trim() === "OK") {
        alert("Registracija uspješna!");
        registerModal.style.display = "none";
    } 
    else {
        alert("Greška pri registraciji! Pokušajte ponovo.");
    }
});


  window.onclick = (event) => {
    if (event.target === registerModal) {
      registerModal.style.display = "none";
    }
  };
});
