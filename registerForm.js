 document.addEventListener('DOMContentLoaded', () => {
 // Modal za registraciju
 const registerModal = document.getElementById('registerModal');
 const openRegisterModal = document.getElementById('openRegisterModal');
 const closeRegisterModal = document.getElementById('closeRegisterModal');
 const registerForm = document.getElementById('registerForm');
 const registerError = document.getElementById('registerError');
 // Otvoriti modal za registraciju
 openRegisterModal.onclick = () => {
 registerModal.style.display = 'block';
 };
 // Zatvoriti modal za registraciju
 closeRegisterModal.onclick = () => {
 registerModal.style.display = 'none';
 };
 // Zatvoriti modal ako klikneš izvan sadržaja modala
 window.onclick = (event) => {
 if (event.target == registerModal) {
 registerModal.style.display = 'none';
 }
 };
 // Validacija registracijskog obrasca
 registerForm.addEventListener('submit', function(event) {
 event.preventDefault(); // Sprječavamo slanje obrasca
 // Dohvat vrijednosti s forme
 const username = document.getElementById('username').value;
 const email = document.getElementById('email').value;
const password = document.getElementById('password').value;
 const confirmPassword = document.getElementById('confirmPassword').value;
 // Provjera podudaranja lozinki
 if (password !== confirmPassword) {
 registerError.style.display = 'block'; // Prikazujemo grešku ako lozinke nisu iste
 } else {
 registerError.style.display = 'none'; // Skriva grešku
 alert(`Korisnik ${username} uspješno registriran!`);
 registerModal.style.display = 'none'; // Zatvaramo modal nakon uspješne registracije
 // Ovdje možete dodati funkcionalnost za slanje podataka na server
 }
 });
 });