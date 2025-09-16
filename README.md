# CRUD Multi Role Access

## 📌 Deskripsi Proyek

CRUD Multi Role Access adalah sebuah aplikasi berbasis Laravel yang mengimplementasikan sistem autentikasi dan otorisasi dengan **multi-role login**. Aplikasi ini mendukung role **Admin** dan **User** dengan hak akses berbeda, serta memiliki fitur CRUD (Create, Read, Update, Delete) dengan validasi, middleware, dan pengelolaan token untuk voting.

---

## 🚀 Fitur Utama

* **Autentikasi Multi-Role** (Admin & User)
* **CRUD User & Kandidat**
* **Voting System** dengan token
* **Middleware Role-based Access**
* **Soft delete & Restore data**
* **Statistik hasil voting**
* **Proteksi Token** agar hanya bisa digunakan sekali

---

## 📂 Struktur Proyek

Struktur utama proyek Laravel ini:

```
CRUDMultiRoleAccess/
│
├── app/
│   ├── Console/        # Perintah artisan kustom
│   ├── Exceptions/     # Handler error aplikasi
│   ├── Http/
│   │   ├── Controllers/  # Controller utama aplikasi
│   │   │   ├── Auth/         # Controller autentikasi
│   │   │   ├── CandidateController.php # CRUD kandidat
│   │   │   ├── PostController.php      # CRUD postingan (contoh)
│   │   │   ├── TokenController.php     # Manajemen token voting
│   │   │   ├── UserController.php      # CRUD user
│   │   │   └── VoteController.php      # Proses voting & hasil
│   │   ├── Middleware/   # Middleware kustom (Role, Token, dll)
│   │   │   ├── EnsureTokenIsVerified.php
│   │   │   ├── RoleMiddleware.php
│   │   │   └── Authenticate.php
│   │   └── Kernel.php   # Registrasi middleware
│   │
│   ├── Models/         # Model database (Eloquent ORM)
│   │   ├── Candidate.php
│   │   ├── Token.php
│   │   ├── User.php
│   │   └── Vote.php
│   │
│   └── Providers/      # Service providers
│
├── bootstrap/          # Bootstrap Laravel
├── config/             # Konfigurasi aplikasi (auth.php, database.php, dll)
├── database/
│   ├── factories/      # Factory untuk seeding
│   ├── migrations/     # Migrasi database
│   └── seeders/        # Data awal
│
├── public/             # Root folder web (index.php, asset)
├── resources/
│   ├── views/          # Blade templates (auth, dashboard, vote, dll)
│   ├── js/             # JavaScript frontend
│   └── css/            # File CSS
│
├── routes/
│   ├── web.php         # Routing utama aplikasi (auth, admin, user)
│   └── api.php         # Jika ada API tambahan
│
├── storage/            # Cache, logs, upload, dll
├── tests/              # Unit test & feature test
├── vendor/             # Dependensi Composer
│
├── .env                # Konfigurasi environment (DB, APP_KEY, dll)
├── artisan             # CLI Laravel
├── composer.json       # Dependensi Laravel
└── package.json        # Jika ada dependensi frontend
```

### 🔑 Penjelasan Struktur Penting

* **app/Http/Controllers/** → berisi logika utama aplikasi (CRUD, autentikasi, voting).
* **app/Http/Middleware/** → pengecekan role dan token sebelum request diproses.
* **app/Models/** → representasi tabel database.
* **resources/views/** → UI berbasis Blade (login, dashboard admin, voting user).
* **routes/web.php** → mendefinisikan route utama (admin & user).
* **database/migrations/** → mendefinisikan struktur tabel database.

---

## 📜 Routing Utama

```php
Route::redirect('/', '/login');
Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::resource('users', UserController::class);
    Route::resource('candidates', CandidateController::class);
    Route::resource('tokens', TokenController::class);
});

Route::middleware(['auth', 'role:user', 'token.verify'])->group(function () {
    Route::get('/vote', [VoteController::class, 'index'])->name('vote.index');
    Route::post('/vote', [VoteController::class, 'store'])->name('vote.store');
    Route::get('/results', [VoteController::class, 'results'])->name('vote.results');
});
```

---

## 🔧 Kode Penting

### VoteController (store method)

```php
public function store(Request $request)
{
    $request->validate([
        'candidate_id' => 'required|exists:candidates,id'
    ]);

    // Cek apakah user sudah pernah vote
    $existingVote = Vote::where('user_id', auth()->id())->first();

    if ($existingVote) {
        return redirect()->back()->with('error', 'Anda sudah pernah memberikan suara.');
    }

    // Simpan vote
    Vote::create([
        'user_id' => auth()->id(),
        'candidate_id' => $request->candidate_id
    ]);

    return redirect()->back()->with('success', 'Voting berhasil!');
}
```

Kode di atas mengecek apakah user sudah pernah voting. Jika sudah, voting ditolak. Jika belum, data baru disimpan ke tabel **votes**.

---

## 🔌 Contoh API Endpoint

### Login

**POST** `/login`

```json
{
  "email": "admin@mail.com",
  "password": "password"
}
```

**Response**

```json
{
  "message": "Login berhasil",
  "redirect": "/admin/dashboard"
}
```

### Vote

**POST** `/vote`

```json
{
  "candidate_id": 2
}
```

**Response**

```json
{
  "message": "Voting berhasil!"
}
```

---

## 🗄️ ERD (Entity Relationship Diagram)

```
Users (id, name, email, password, role)
Candidates (id, name, visi, misi)
Votes (id, user_id, candidate_id, created_at)
Tokens (id, token, user_id, expires_at, used_at)
```

**Relasi:**

* **User → Vote**: One-to-One (user hanya bisa vote sekali)
* **Candidate → Vote**: One-to-Many (satu kandidat bisa dipilih banyak user)
* **User → Token**: One-to-One (satu user punya satu token)

---

## 📊 Statistik

Admin dapat melihat hasil voting berupa jumlah suara tiap kandidat, ditampilkan di dashboard.

---

## 🔮 Pengembangan Selanjutnya

* Tambah **import data user dari excel** ke Excel/PDF
* Tambah **chart hasil voting** dengan Chart.js
* Implementasi **Notifikasi real-time** dengan Pusher/WebSocket
* Tambah **uji unit & feature test**
