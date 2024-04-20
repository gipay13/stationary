# STATIONARY
php 8.3.6.

composer 2.4.1.

node 20.12.2.

## Installation
STEP 1:
```
composer install
```
STEP 2:
```
cp .env.example .env
```
STEP 3:
```
php artisan migrate --seed
```
username
- admin@gmail.com
- staff@gmail.com
- supervisor@gmail.com

password
- password
STEP 4:
```
php artisan key:generate
```
STEP 5:
```
php artisan serve
```
STEP 6:

Buka terminal baru
```
php artisan queue:listen
```

## Note
jika queue pengiriman email statusnya gagal saat setelah melakukan pengajuan barang atau mengubah statusnya menjadi 'disetujui' coba terminate `php artisan queue:listen` kemudian coba ulang pengajuan barang hingga proses di setujui kemudian jalankan `php artisan queue:work`
