# Aplikasi Servis Inventory Bengkel

Backend REST API untuk aplikasi manajemen servis dan inventory bengkel motor. Aplikasi ini dirancang untuk membantu proses pengelolaan servis, pelanggan, kendaraan, barang, stok, transaksi, project, laporan, serta pengguna dalam satu sistem terintegrasi.

Backend dikembangkan menggunakan Laravel dan digunakan sebagai penghubung antara aplikasi Android dengan database serta layanan backend.

## Fitur Utama

###  Autentikasi & Manajemen Pengguna

* Login pengguna.
* Autentikasi menggunakan Laravel Sanctum.
* Manajemen akun pengguna.
* Role pengguna:

  * Owner
  * Admin
  * Kasir
* Status akun Aktif / Nonaktif.
* Pengguna nonaktif tidak dapat menggunakan akun.
* Pengelolaan nama, email, role, dan password.
* Pembatasan perubahan role dan status pada akun sendiri.

###  Pelanggan & Kendaraan

* Pengelolaan data pelanggan.
* Pengelolaan data kendaraan.
* Satu pelanggan dapat memiliki beberapa kendaraan.
* Data kendaraan terhubung dengan data pelanggan.
* Data pelanggan dan kendaraan dapat digunakan dalam proses servis dan transaksi.

###  Manajemen Servis

* Pengelolaan data jasa servis.
* Penambahan jasa servis.
* Perubahan data jasa.
* Penghapusan jasa.
* Pengelolaan harga jasa.
* Data jasa dapat digunakan dalam transaksi dan project.

###  Inventory

* Pengelolaan kategori barang.
* Pengelolaan data barang.
* Pengelolaan harga barang.
* Pengelolaan stok barang.
* Stok masuk.
* Stok keluar.
* Riwayat pergerakan stok.
* Batas minimum stok.
* Monitoring barang dengan stok menipis.

###  Project Bengkel

* Pembuatan project.
* Pengelolaan data project.
* Pengelolaan item barang pada project.
* Pengelolaan item jasa pada project.
* Pengelolaan biaya project.
* Perhitungan biaya project.
* Pengelolaan status project.
* Penyelesaian project.
* Pencatatan pendapatan dari project.

###  Transaksi

* Pencatatan transaksi.
* Detail transaksi.
* Pengelolaan pembayaran.
* Pembatalan transaksi.
* Pencatatan pendapatan.
* Riwayat transaksi.

###  Dashboard & Laporan

Dashboard dan laporan digunakan untuk membantu pengguna memantau kondisi operasional bengkel.

Fitur laporan meliputi:

* Dashboard operasional.
* Laporan penjualan.
* Laporan stok.
* Informasi transaksi.
* Informasi pendapatan.
* Monitoring inventory.

###  Activity Log

Sistem menyediakan pencatatan aktivitas pengguna untuk kebutuhan audit dan monitoring.

Aktivitas yang dapat dicatat antara lain:

* Menambahkan data.
* Mengubah data.
* Menghapus data.
* Mengubah status pengguna.
* Mengelola barang.
* Mengelola jasa.
* Mengelola project.
* Perubahan data penting lainnya.

Activity log mencatat pengguna yang melakukan aktivitas serta data sebelum dan sesudah perubahan jika diperlukan.

Contoh aktivitas:

```text
Kasir menambahkan barang Oli Mesin
Admin mengubah harga barang Oli Mesin
Kasir menambahkan project Custom Minibike
Admin menonaktifkan akun Kasir
```

###  Firebase Push Notification

Aplikasi menggunakan Firebase Cloud Messaging (FCM) untuk mengirimkan notifikasi ke perangkat Android.

Jenis notifikasi yang tersedia:

#### Project Baru

Memberikan informasi kepada Owner ketika project baru ditambahkan.

```text
Project Baru
Project Custom Minibike telah ditambahkan.
```

#### Project Selesai

Memberikan informasi ketika project telah diselesaikan.

```text
Project Selesai
Project Custom Minibike telah selesai.
```

#### Pendapatan Masuk

Memberikan informasi ketika terdapat pendapatan dari project atau transaksi.

```text
Pendapatan Masuk
Pendapatan sebesar Rp2.500.000 telah diterima.
```

#### Stok Menipis

Memberikan informasi ketika stok barang melewati batas minimum.

```text
Stok Menipis
Stok Oli Mesin tersisa 4 pcs.
Batas minimum: 5 pcs.
```

Notifikasi stok menggunakan mekanisme threshold sehingga notifikasi dikirim ketika stok melewati batas minimum dari kondisi sebelumnya.

## Role Pengguna

| Role      | Hak Akses Utama                                                                         |
| --------- | --------------------------------------------------------------------------------------- |
| **Owner** | Memantau operasional, inventory, project, transaksi, laporan, dan notifikasi            |
| **Admin** | Mengelola pengguna, data master, laporan, serta aktivitas sistem                        |
| **Kasir** | Mengelola pelanggan, kendaraan, servis, barang, transaksi, dan project sesuai hak akses |

Hak akses setiap pengguna dikontrol berdasarkan role yang dimiliki.

## Teknologi

### Backend

* PHP 8.2
* Laravel 12
* Laravel Sanctum
* MySQL
* REST API

### Notification

* Firebase Cloud Messaging (FCM)

### Android Client

* Kotlin
* Android Studio
* Retrofit
* Gson

### Web Admin

* Laravel Blade
* Vite
* HTML
* CSS
* JavaScript

## Arsitektur Sistem

Secara umum, sistem menggunakan arsitektur client-server.

```text
┌──────────────────────┐
│    Android Client    │
│       Kotlin        │
└──────────┬───────────┘
           │
           │ REST API
           ▼
┌──────────────────────┐
│   Laravel Backend    │
│                      │
│ Authentication       │
│ Business Logic       │
│ Role & Permission    │
│ Activity Log         │
│ Notification         │
└──────────┬───────────┘
           │
           ▼
┌──────────────────────┐
│       MySQL          │
│      Database        │
└──────────────────────┘
           │
           │
           ▼
┌──────────────────────┐
│ Firebase Cloud       │
│ Messaging (FCM)      │
└──────────────────────┘

``````



## Status Pengembangan

Project masih dalam tahap pengembangan.

Fitur yang telah tersedia:

* Authentication
* Role management
* User management
* Active / inactive account
* Customer management
* Vehicle management
* Service management
* Product management
* Inventory management
* Stock movement
* Low stock monitoring
* Transaction management
* Project management
* Project item management
* Project cost management
* Dashboard
* Sales report
* Stock report
* Activity log
* Firebase push notification


## Tujuan Project

Aplikasi ini dikembangkan untuk membantu digitalisasi proses operasional bengkel motor, khususnya dalam pengelolaan:

* Data pelanggan.
* Data kendaraan.
* Servis.
* Inventory.
* Stok.
* Transaksi.
* Project.
* Pendapatan.
* Pengguna.
* Aktivitas sistem.

Dengan adanya sistem terintegrasi, proses pencatatan dan monitoring dapat dilakukan secara lebih terstruktur serta mengurangi ketergantungan terhadap pencatatan manual.

## Tampilan Halaman Aplikasi

## Tampilan Halaman Aplikasi

### Tampilan Login
<p float="left">
  <img width="250" height="541" alt="Screenshot_2026-09-12-21-29-21-392_com bengkel sempoeloer" src="https://github.com/user-attachments/assets/ead0fd1f-12b7-42a8-b50d-83ea968721a1" />
</p>

### Tampilan Owner
<p float="left">
  <img width="250" height="541" alt="Screenshot_2026-09-12-21-32-12-257_com bengkel sempoeloer" src="https://github.com/user-attachments/assets/fb2a0f65-c1ce-4526-9b5e-e914b00662ad" />
</p>

### Tampilan Karyawan/Kasir
<p float="left">
  <img width="250" height="541" alt="Screenshot_2026-09-12-23-01-26-245_com bengkel sempoeloer" src="https://github.com/user-attachments/assets/27361bab-f8b4-455f-a45e-19007c878499" />
  <img width="250" height="541" alt="Screenshot_2026-09-12-23-01-31-496_com bengkel sempoeloer" src="https://github.com/user-attachments/assets/d3b70350-3a54-405d-ad83-f0c298e73380" />
  <img width="250" height="541" alt="Screenshot_2026-09-12-23-01-39-917_com bengkel sempoeloer" src="https://github.com/user-attachments/assets/8fcaffba-d017-4971-bdb7-e0ca92c9f050" />
</p>

## Author

**Ardiansyah Surya Pratama**

---


> Aplikasi Servis Inventory Bengkel dikembangkan sebagai project portfolio untuk menunjukkan implementasi backend REST API, database management, role-based access control, inventory management, transaction management, audit logging, project management, dan Firebase Cloud Messaging.
