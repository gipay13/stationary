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

Setting Config Mailtrap
```
MAIL_MAILER=
MAIL_HOST=
MAIL_PORT=
MAIL_USERNAME=
MAIL_PASSWORD=
```
STEP 4:
```
php artisan migrate --seed
```
```
Username
    admin@gmail.com 
    staff@gmail.com 
    supervisor@gmail.com

Password
    password
```
STEP 5:
```
php artisan key:generate
```
STEP 6:
```
php artisan serve
```
STEP 7:

Buka terminal baru
```
php artisan queue:listen
```

## Note
jika queue pengiriman email statusnya gagal saat setelah melakukan pengajuan barang atau mengubah statusnya menjadi 'disetujui' coba terminate `php artisan queue:listen` kemudian coba ulang pengajuan barang hingga proses di setujui kemudian jalankan `php artisan queue:work`