 const navbarContent = `
 <nav>
 <ul>
 <li><a href='model.php'>Početna</a></li>
 <li><a href=$'>Kategorije</a></li>
 <li><a href=$'>Košarica</a></li>
 <li><button id="navbarLoginBtn">Prijava</button></li>
 </ul>
 </nav>
 `;
 document.getElementById('navbar').innerHTML = navbarContent;

 fetch("session_user.php")
  .then(res => res.json())
  .then(user => {
    if (user) {
      document.getElementById("navbar").innerHTML += 
        `<div class='user-info'>
            Prijavljeni ste kao: <b>${user.name}</b>
         </div>`;
    }
  });
