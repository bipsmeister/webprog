# Web Shop Računala - Kompletna Implementacija

Ovaj projekt je kompletna implementacija web shopova za prodaju računalnih proizvoda sa podrškom za admin i korisnički pristup.

## Karakteristike

### Za Korisnike
- ✅ Registracija i prijava
- ✅ Pregled proizvoda po kategorijama
- ✅ Dodavanje proizvoda u košaricu
- ✅ Checkout sa formom za dostavu
- ✅ Praćenje narudžbi
- ✅ Profil korisnika

### Za Administratore
- ✅ Dodavanje, uređivanje i brisanje proizvoda
- ✅ Upravljanje zaliho
- ✅ Pregled svih narudžbi
- ✅ Ažuriranje statusa narudžbi

### Kategorije Proizvoda
1. **Linkovi** - HDMI kabeli, mrežni kabeli, USB kabeli
2. **HG Spot** - LED rasvjeta, spot lampe
3. **Instar Informatika** - Procesori, RAM, SSD, matične ploče

## Instalacija

### 1. Kreirajte bazu podataka
```sql
CREATE DATABASE trgovina;
```

### 2. Izvršite SQL skripte
```bash
# Kreirajte tablice
mysql -u root -p trgovina < database_setup.sql

# Dodajte test podatke (opciono)
mysql -u root -p trgovina < test_data.sql
```

### 3. Konfiguracija
Ažurirajte `db.php` sa vašim database kredencijalima:
```php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "trgovina";
```

## Struktura Projekta

### HTML Datoteke
- `model.php` - Glavna stranica
- `categories.php` - Pregled kategorija i proizvoda
- `cart.php` - Košarica
- `orders.php` - Moje narudžbe
- `admin.php` - Admin panel (samo za admina)

### PHP API Datoteke
- `login.php` - Prijava korisnika
- `register.php` - Registracija korisnika
- `cart_api.php` - Upravljanje košaricom
- `order_api.php` - Upravljanje narudžbama
- `admin_api.php` - Admin API
- `get_products.php` - Dohvatanje proizvoda
- `session_user.php` - Informacije o sesiji
- `logout.php` - Odjava

### JavaScript Datoteke
- `navbar.js` - Navigacijska traka
- `loginForm.js` - Logika za login
- `registerForm.js` - Logika za registraciju
- `modals.js` - Upravljanje modalima

### CSS
- `css.css` - Stilovi cijelog projekta

## Test Kredencijali

### Admin Korisnik
- **Korisničko ime:** admin
- **Lozinka:** admin123
- **Uloga:** admin

### Obični Korisnik
- **Korisničko ime:** korisnik
- **Lozinka:** user123
- **Uloga:** user

## Korištenje

### Za Korisnike

1. **Registracija**
   - Kliknite "Registriraj se"
   - Unesite korisničko ime, email i lozinku
   - Potvrdite registraciju

2. **Kupnja**
   - Prijavite se
   - Odaberite kategoriju
   - Odaberite proizvod i kliknite "U košaricu"
   - Idite na "Košarica"
   - Kliknite "Idi na naplatu"
   - Unesite podatke za dostavu
   - Potvrdite narudžbu

3. **Praćenje Narudžbi**
   - Kliknite "Moje narudžbe"
   - Pregledajte status i stavke vaših narudžbi

### Za Administratore

1. **Pristup Admin Panelu**
   - Prijavite se kao admin
   - Kliknite "Admin Panel"

2. **Upravljanje Proizvodima**
   - Odaberite kategoriju
   - Unesite podatke proizvoda
   - Kliknite "Dodaj proizvod"
   - Uredite ili obrišite postojeće proizvode

3. **Pregled Narudžbi**
   - Kliknite na tab "Narudžbe"
   - Pregledajte sve narudžbe
   - Ažurirajte status narudžbi
   - Pogledajte detaljne stavke

## Baza Podataka - Struktura Tablica

### users
- id, name, email, password, role, phone, address, created_at

### categories
- id, name, description, image, created_at

### products
- id, category_id, name, description, price, stock, image, created_by, created_at

### cart
- id, user_id, product_id, quantity, added_at

### orders
- id, user_id, total_price, status, delivery_address, delivery_city, delivery_zip, phone, created_at

### order_items
- id, order_id, product_id, quantity, price

## Sigurnost

- ✅ Lozinke su hashirane sa `PASSWORD_DEFAULT` algoritmom
- ✅ SQL injections su zaštićene sa `prepared statements`
- ✅ Session-based autentifikacija
- ✅ Role-based pristup (admin/user)

## Budući Razvoj

- [ ] Payment gateway integracija
- [ ] Email notifikacije
- [ ] Slika proizvoda upload
- [ ] Pretraga proizvoda
- [ ] Recenzije proizvoda
- [ ] Wishlist
- [ ] Brze akcije (Flash sale)
- [ ] SMS notifikacije

## Podrška

Za pitanja ili probleme, kontaktirajte administratora web shopa.

## Verzija
v1.0.0 - 2025
