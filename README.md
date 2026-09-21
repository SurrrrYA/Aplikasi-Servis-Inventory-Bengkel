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

### Tampilan Login
<p float="left">
  <img width="250" height="541" alt="Screenshot_2026-09-12-21-29-21-392_com bengkel sempoeloer" src="https://github.com/user-attachments/assets/ead0fd1f-12b7-42a8-b50d-83ea968721a1" />
</p>

### Tampilan Owner
### Tampilan Owner
<p float="left">
  <img width="250" height="541" alt="Screenshot_2026-09-12-21-32-12-257_com bengkel sempoeloer" src="https://github.com/user-attachments/assets/fb2a0f65-c1ce-4526-9b5e-e914b00662ad" />
  <img width="250" height="541" alt="Screenshot_2026-09-13-12-51-39-178_com bengkel sempoeloer" src="https://github.com/user-attachments/assets/3fb1c417-c210-4991-b49f-d90805d660c8" />
  <img width="250" height="541" alt="Screenshot_2026-09-13-12-51-47-520_com bengkel sempoeloer" src="https://github.com/user-attachments/assets/e4e80d58-bed3-4f17-8a45-7ea971a67f57" />
  <img width="250" height="541" alt="Screenshot_2026-09-13-12-52-05-070_com bengkel sempoeloer" src="https://github.com/user-attachments/assets/b4286aff-6a2b-444e-9989-57133fbd697a" />
  <img width="250" height="541" alt="Screenshot_2026-09-13-12-52-16-715_com bengkel sempoeloer" src="https://github.com/user-attachments/assets/6873c62e-4841-445e-8c15-0ef9696c1cb6" />
  <img width="250" height="541" alt="Screenshot_2026-09-13-12-52-19-363_com bengkel sempoeloer" src="https://github.com/user-attachments/assets/822a1823-0d39-4c59-a332-fd1194a30a21" />
  <img width="250" height="541" alt="Screenshot_2026-09-13-12-52-25-088_com bengkel sempoeloer" src="https://github.com/user-attachments/assets/422abfa1-7837-4e6e-ab9b-77e0158febb5" />
  <img width="250" height="541" alt="Screenshot_2026-09-13-12-52-31-382_com bengkel sempoeloer" src="https://github.com/user-attachments/assets/9eec3f30-6ad1-4b7f-8f49-5ff1cd975b4a" />
  <img width="250" height="541" alt="Screenshot_2026-09-13-12-52-34-433_com bengkel sempoeloer" src="https://github.com/user-attachments/assets/d5185e80-57c3-4d4f-a262-b3ac06504530" />
  <img width="250" height="541" alt="Screenshot_2026-09-13-12-52-37-110_com bengkel sempoeloer" src="https://github.com/user-attachments/assets/94d8faeb-c8a3-41e4-aa86-c2e2203ff13f" />
  <img width="250" height="541" alt="Screenshot_2026-09-13-12-52-39-910_com bengkel sempoeloer" src="https://github.com/user-attachments/assets/16782dee-dab9-4952-b9bd-9a3dcac22b52" />
  <img width="250" height="541" alt="Screenshot_2026-09-13-12-52-44-128_com bengkel sempoeloer" src="https://github.com/user-attachments/assets/ac8974e4-8168-4a28-9bb4-1cbfd03f9246" />
  <img width="250" height="541" alt="Screenshot_2026-09-13-12-52-48-743_com bengkel sempoeloer" src="https://github.com/user-attachments/assets/ff720a6a-0d37-421d-9731-78a822ed78dc" />
  <img width="250" height="541" alt="Screenshot_2026-09-13-12-52-54-050_com bengkel sempoeloer" src="https://github.com/user-attachments/assets/d9a63547-cf35-4453-ab9b-4f4501fc4618" />
</p>

### Tampilan Karyawan/Kasir
<p float="left">
  <img width="250" height="541" alt="Screenshot_2026-09-12-23-01-26-245_com bengkel sempoeloer" src="https://github.com/user-attachments/assets/27361bab-f8b4-455f-a45e-19007c878499" />
  <img width="250" height="541" alt="Screenshot_2026-09-12-23-01-31-496_com bengkel sempoeloer" src="https://github.com/user-attachments/assets/d3b70350-3a54-405d-ad83-f0c298e73380" />
  <img width="250" height="541" alt="Screenshot_2026-09-12-23-01-39-917_com bengkel sempoeloer" src="https://github.com/user-attachments/assets/8fcaffba-d017-4971-bdb7-e0ca92c9f050" />
    <img width="250" height="541" alt="Screenshot_2026-09-13-13-01-46-908_com bengkel sempoeloer" src="https://github.com/user-attachments/assets/3b2ba3c6-48b9-4927-8313-cc232bd3eb60" />
    <img width="250" height="541" alt="Screenshot_2026-09-13-13-01-51-454_com bengkel sempoeloer" src="https://github.com/user-attachments/assets/c75256a6-a282-47ce-8359-b1ee91ca9a2a" />
<img width="250" height="541" alt="Screenshot_2026-09-13-13-01-54-359_com bengkel sempoeloer" src="https://github.com/user-attachments/assets/a2392806-120c-4d3f-8789-e8e267a2cdf3" />
<img width="250" height="541" alt="Screenshot_2026-09-13-13-02-02-342_com bengkel sempoeloer" src="https://github.com/user-attachments/assets/3b86c5fd-7304-4537-96ce-741233960498" />


</p>

## Author

**Ardiansyah Surya Pratama**

---


> Aplikasi Servis Inventory Bengkel dikembangkan sebagai project portfolio untuk menunjukkan implementasi backend REST API, database management, role-based access control, inventory management, transaction management, audit logging, project management, dan Firebase Cloud Messaging.
