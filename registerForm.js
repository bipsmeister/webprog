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

  registerForm.addEventListener("submit", (event) => {
    event.preventDefault();

    const password = document.getElementById("password").value;
    const confirmPassword = document.getElementById("confirmPassword").value;

    if (password !== confirmPassword) {
      registerError.style.display = "block";
    } else {
      registerError.style.display = "none";
      alert("Registracija uspješna!");
      registerModal.style.display = "none";
    }
  });

  window.onclick = (event) => {
    if (event.target === registerModal) {
      registerModal.style.display = "none";
    }
  };
});
