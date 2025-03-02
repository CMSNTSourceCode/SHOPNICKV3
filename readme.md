# Hướng Dẫn Cài Đặt 


## Cấu Hình `.env`

Chỉnh sửa `.env` với thông tin database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

## Cài Đặt Thư Viện
Chạy lệnh sau để cài đặt các thư viện cần thiết:

```bash
composer install
```

## Tạo Database Tables
Sau khi đã cấu hình database, chạy migration để tạo các bảng:

```bash
php artisan migrate
```

## Tạo Key Cho Ứng Dụng
```bash
php artisan key:generate
```


