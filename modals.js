document.addEventListener("DOMContentLoaded", () => {
  const registerModal = document.getElementById("registerModal");
  const loginModal = document.getElementById("loginModal");
  const userProfileModal = document.getElementById("userProfileModal");

  // Registracija
  const openRegisterModal = document.getElementById("openRegisterModal");
  const closeRegisterModal = document.getElementById("closeRegisterModal");
  
  if (openRegisterModal) {
    openRegisterModal.onclick = () => {
      registerModal.style.display = "block";
    };
  }

  if (closeRegisterModal) {
    closeRegisterModal.onclick = () => {
      registerModal.style.display = "none";
    };
  }

  // Login
  const openLoginModal = document.getElementById("openLoginModal");
  const closeLoginModal = document.getElementById("closeLoginModal");
  
  if (openLoginModal) {
    openLoginModal.onclick = () => {
      loginModal.style.display = "block";
    };
  }

  if (closeLoginModal) {
    closeLoginModal.onclick = () => {
      loginModal.style.display = "none";
    };
  }

  // Navigacijska dugmad za login
  const navbarLoginBtn = document.getElementById("navbarLoginBtn");
  if (navbarLoginBtn) {
    navbarLoginBtn.onclick = () => {
      loginModal.style.display = "block";
    };
  }

  // Korisnički profil
  const userInfoDiv = document.querySelector('.user-info');
  if (userInfoDiv) {
    userInfoDiv.onclick = () => {
      fetch("session_user.php")
        .then(res => res.json())
        .then(user => {
          if (user) {
            document.getElementById("usernameDisplay").textContent = user.name;
            document.getElementById("emailDisplay").textContent = user.email;
            userProfileModal.style.display = "block";
          }
        });
    };
  }

  const closeUserProfileModal = document.getElementById("closeUserProfileModal");
  if (closeUserProfileModal) {
    closeUserProfileModal.onclick = () => {
      userProfileModal.style.display = "none";
    };
  }

  // Zatvori modal kada se klikne izvan njega
  window.onclick = (event) => {
    if (event.target === registerModal) {
      registerModal.style.display = "none";
    }
    if (event.target === loginModal) {
      loginModal.style.display = "none";
    }
    if (event.target === userProfileModal) {
      userProfileModal.style.display = "none";
    }
  };
});
