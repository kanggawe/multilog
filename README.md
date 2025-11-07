# Laravel Multi-Level User System

Sistem manajemen pengguna multi-level dengan role dan permission untuk Laravel.

## Fitur

- ✅ Sistem Authentication (Login/Logout)
- ✅ Multi-Level User dengan Role (Admin, Manager, User)
- ✅ Middleware untuk Role Checking
- ✅ CRUD User Management untuk Admin
- ✅ Dashboard berdasarkan Role
- ✅ UI Modern dengan Tailwind CSS

## Struktur Role

1. **Admin** - Akses penuh, dapat mengelola semua user
2. **Manager** - Akses terbatas, dapat melihat dan mengelola user
3. **User** - Akses dasar, hanya dapat melihat dashboard

## Instalasi

1. Clone atau download proyek ini
2. Install dependencies:
   ```bash
   composer install
   ```
3. Copy file `.env.example` ke `.env`:
   ```bash
   cp .env.example .env
   ```
4. Generate application key:
   ```bash
   php artisan key:generate
   ```
5. Setup database di file `.env`
6. Run migrations dan seeders:
   ```bash
   php artisan migrate
   php artisan db:seed
   ```
7. Jalankan development server:
   ```bash
   php artisan serve
   ```

## Akun Test

Setelah menjalankan seeder, akun berikut tersedia:

### Admin
- Email: `admin@example.com`
- Password: `password`

### Manager
- Email: `manager@example.com`
- Password: `password`

### User
- Email: `user@example.com`
- Password: `password`

## Struktur Database

### Tabel `roles`
- `id` - Primary key
- `name` - Nama role (admin, manager, user)
- `description` - Deskripsi role
- `created_at`, `updated_at`

### Tabel `users`
- `id` - Primary key
- `role_id` - Foreign key ke tabel roles
- `name` - Nama user
- `email` - Email user
- `password` - Password yang di-hash
- `created_at`, `updated_at`

## Penggunaan Middleware

Gunakan middleware `role` untuk membatasi akses berdasarkan role:

```php
Route::middleware('role:admin')->group(function () {
    // Routes hanya untuk admin
});

Route::middleware('role:admin,manager')->group(function () {
    // Routes untuk admin dan manager
});
```

## Helper Methods di User Model

```php
$user->hasRole('admin'); // Cek apakah user punya role tertentu
$user->isAdmin();        // Cek apakah user adalah admin
$user->isManager();      // Cek apakah user adalah manager
$user->isUser();         // Cek apakah user adalah regular user
```

## Routes

### Public Routes
- `GET /` - Redirect ke dashboard
- `GET /login` - Form login
- `POST /login` - Proses login
- `POST /logout` - Logout

### Protected Routes (Auth Required)
- `GET /dashboard` - Dashboard user

### Admin Routes (Admin/Manager Only)
- `GET /admin/users` - List semua user
- `GET /admin/users/create` - Form create user
- `POST /admin/users` - Store user baru
- `GET /admin/users/{user}` - Detail user
- `GET /admin/users/{user}/edit` - Form edit user
- `PUT /admin/users/{user}` - Update user
- `DELETE /admin/users/{user}` - Delete user

## Pengembangan Lebih Lanjut

Beberapa fitur yang bisa ditambahkan:
- Permission system yang lebih detail
- Email verification
- Password reset
- Profile management
- Activity logging
- Role-based UI components

## License

Proyek ini dibuat untuk keperluan pembelajaran dan dapat digunakan secara bebas.
