const registerModal = document.getElementById('registerModal');
 const openRegisterModal = document.getElementById('openRegisterModal');
 const closeRegisterModal = document.getElementById('closeRegisterModal');
 const userProfileModal = document.getElementById('userProfileModal');
const openUserProfileModal = document.getElementById('openUserProfileModal');
 const closeUserProfileModal = document.getElementById('closeUserProfileModal');
 openRegisterModal.onclick = () => {
 registerModal.style.display = 'block';
 };
 closeRegisterModal.onclick = () => {
 registerModal.style.display = 'none';
 };
 openUserProfileModal.onclick = () => {
 document.getElementById('usernameDisplay').textContent = "Ivan Ivanić";
 document.getElementById('emailDisplay').textContent = "ivan@example.com";
 userProfileModal.style.display = 'block';
 };
 closeUserProfileModal.onclick = () => {
 userProfileModal.style.display = 'none';
 };
 window.onclick = (event) => {
 if (event.target == registerModal) {
 registerModal.style.display = 'none';
 }
 if (event.target == userProfileModal) {
 userProfileModal.style.display = 'none';
 }
 };

 fetch("navbar.html")
  .then(res => res.text())
  .then(html => {
    document.getElementById("navbar").innerHTML = html;
  });
