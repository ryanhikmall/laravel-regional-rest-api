# 🗺️ GeoData Admin Dashboard (Laravel REST API)

Aplikasi Web Service RESTful untuk mengelola data wilayah administratif Indonesia (Provinsi, Kota, Kecamatan) secara hierarkis. Dilengkapi dengan sistem otentikasi User dan Dashboard Frontend sederhana.

![Project Status](https://img.shields.io/badge/status-active-success)
![Laravel](https://img.shields.io/badge/Laravel-10-red)
![Sanctum](https://img.shields.io/badge/Auth-Sanctum-blue)

## 🌟 Fitur Utama

-   **CRUD Data Wilayah:** Kelola data Provinsi, Kota, dan Kecamatan.
-   **Relational Data Integrity:** Relasi One-to-Many (Provinsi -> Kota -> Kecamatan) dengan Foreign Key & Cascade Delete.
-   **Cascading Filter:**
    -   Get Cities by Province ID
    -   Get Districts by City ID
-   **Secure Authentication:** Register, Login, Logout menggunakan **Laravel Sanctum**.
-   **Interactive Dashboard:** Frontend Single Page (HTML/JS) untuk visualisasi data dan manajemen login.

## 🛠️ Teknologi yang Digunakan

-   **Backend:** Laravel 10 (PHP Framework)
-   **Database:** MySQL
-   **Authentication:** Laravel Sanctum (Bearer Token)
-   **Frontend:** HTML5, Vanilla JavaScript (Fetch API), Tailwind CSS (CDN)
-   **Tools:** Postman (API Testing)

## 📸 Screenshots

### 1. Dashboard UI

### 2. API Response (Postman)

## 🚀 Cara Instalasi

1.  **Clone Repository**

    ```bash
    git clone [https://github.com/USERNAME/REPO-NAME.git](https://github.com/USERNAME/REPO-NAME.git)
    cd REPO-NAME
    ```

2.  **Install Dependencies**

    ```bash
    composer install
    ```

3.  **Setup Environment**

    -   Copy file `.env.example` menjadi `.env`
    -   Atur konfigurasi database di file `.env` (DB_DATABASE, DB_USERNAME, dll).

4.  **Generate Key & Migrate**

    ```bash
    php artisan key:generate
    php artisan migrate
    ```

5.  **Jalankan Server**

    ```bash
    php artisan serve --port=88
    ```

6.  **Akses Dashboard**
    Buka file `index.html` di browser Anda.

## 🔗 API Endpoints

| Method | Endpoint                  | Deskripsi                          | Auth Required |
| :----- | :------------------------ | :--------------------------------- | :------------ |
| POST   | `/api/register`           | Mendaftar user baru                | No            |
| POST   | `/api/login`              | Login user & dapatkan token        | No            |
| GET    | `/api/province`           | Lihat semua provinsi               | No            |
| POST   | `/api/province`           | Tambah provinsi baru               | **Yes**       |
| GET    | `/api/city/province/{id}` | Lihat kota berdasarkan ID provinsi | No            |
| ...    | ...                       | ...                                | ...           |

---

**Dibuat oleh:** Sahryan Hikmal Pradana
