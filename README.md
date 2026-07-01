## 📑Deskripsi

Siakad mini adalah web yang berfokus untuk melakukan CRUD data mahasiswa, dosen, dan mata kuliah. Web ini merupakan tugas kuliah yang saya sedang 

---

## 🚀 Fitur

* ✍️ CRUD data mahasiswa, dosen, dan mata kuliah
* 📄 Import dan Export CSV
* 🌓 Dark mode

---

## 🛠️ Teknologi yang Digunakan

* **Laravel** (PHP Framework)
* **MySQL** (Database)

---

## 📂 Struktur Project (Simplified)

```
app
├── Http
│   └── Controllers
│      ├── DosenController.php
│      ├── Mahasiswatroller.php
│      └── MataKuliahController.php
|
└── Models
    ├── Dosen.php
    ├── Mahasiswa.php
    └── MataKuiah.php

database
└── seeders
    ├── DosenSeeder.php
    ├── MahasiswaSeeder.php
    └── MataKuiahSeeder.php

resources
├── views
│   └── dosen
│      ├── _form.blade.php
│      ├── create.blade.php
│      ├── edit.blade.php
│      ├── index.blade.php
│      └── show.blade.php
|
│   └── mahasiswa
│      ├── form.blade.php
│      ├── create.blade.php
│      ├── edit.blade.php
│      ├── index.blade.php
│      └── show.blade.php
|
│   └── matakuliah
│      ├── create.blade.php
│      ├── edit.blade.php
│      ├── index.blade.php
│      └── show.blade.php

```

---

# ✅ Alur Lengkap Setup Naratia (via XAMPP)

## 🧩 1. Siapkan XAMPP

* Nyalakan:

  * ✅ Apache
  * ✅ MySQL

* Buka:

  ```
  http://localhost/phpmyadmin
  ```

---

## 🗄️ 2. Buat Database Kosong

Di phpMyAdmin:

* Klik **New**
* Nama database:

  ```
  siakad_mini
  ```
* Klik **Create**

❗ Jangan buat tabel manual — Laravel yang akan isi

---

## 📥 3. Clone Repository

```bash
git clone https://github.com/talitha404/siakad-mini.git
cd siakad_mini
```

---

## 📦 4. Install Dependency

```bash
composer install
npm install tailwindcss @tailwindcss/vite
```

---

## ⚙️ 5. Setup Environment (INI PENTING ⚠️)

```bash
cp .env.example .env
php artisan key:generate
```

---

## 🛠️ 6. Konfigurasi Database

Buka `.env`, ubah:

```env
DB_DATABASE=siakad_mini
DB_USERNAME=root
DB_PASSWORD=
```

(XAMPP default biasanya kosong passwordnya)

---

## 🧱 7. Migrasi Database

```bash
php artisan migrate
```

---

## 🚀 8. Jalankan Server

```bash
php artisan serve
npm run dev
```

Akan muncul:

```
http://127.0.0.1:8000
```