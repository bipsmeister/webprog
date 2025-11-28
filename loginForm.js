document.addEventListener("DOMContentLoaded", () => {
  const loginModal = document.getElementById("loginModal");
  const openLoginModal = document.getElementById("openLoginModal");
  const closeLoginModal = document.getElementById("closeLoginModal");
  const loginForm = document.getElementById("loginForm");

  openLoginModal.onclick = () => {
    loginModal.style.display = "block";
  };

  closeLoginModal.onclick = () => {
    loginModal.style.display = "none";
  };

  loginForm.addEventListener("submit", (event) => {
    event.preventDefault();
    alert("Pokušaj prijave (logika još nije implementirana)");
    loginModal.style.display = "none";
  });

  window.onclick = (event) => {
    if (event.target === loginModal) {
      loginModal.style.display = "none";
    }
  };
});
