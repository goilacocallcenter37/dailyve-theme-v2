# Hướng dẫn Deploy Theme Dailyve

Theme này được xây dựng trên nền tảng **Sage 10** và sử dụng **Vite** để quản lý assets. Dưới đây là các bước để deploy theme lên server.

## 1. Yêu cầu hệ thống trên Server
- **PHP**: >= 8.2
- **WordPress**: Phiên bản mới nhất
- **Plugin bắt buộc**: [Acorn](https://roots.io/acorn/) (Thường được cài đặt qua Composer cùng theme)

## 2. Quy trình Build (Thực hiện dưới Local hoặc Build Server)

Trước khi upload theme lên server, bạn cần thực hiện các bước build để tối ưu hóa hiệu suất.

### Bước 2.1: Cài đặt PHP Dependencies
Mở terminal tại thư mục theme và chạy:
```bash
composer install --no-dev --optimize-autoloader
```

### Bước 2.2: Cài đặt JS Dependencies và Build Assets
```bash
# Cài đặt thư viện node
npm install

# Build assets cho môi trường production
npm run build
```
Sau khi chạy lệnh này, các file đã được tối ưu sẽ nằm trong thư mục `public/build/`.

## 3. Upload lên Server

Bạn có thể upload theme qua FTP/SFTP hoặc sử dụng Git. Các thư mục và file **CẦN THIẾT** phải có trên server:
- `app/`
- `config/` (nếu có)
- `public/` (Đặc biệt là `public/build/`)
- `resources/`
- `vendor/`
- `functions.php`
- `index.php`
- `style.css`
- `theme.json`
- `screenshot.png`

**Các thư mục có thể loại bỏ để nhẹ server:**
- `node_modules/`
- `tests/`
- `.editorconfig`, `.gitignore`, `package.json`, `package-lock.json`, `vite.config.js`

## 4. Cấu hình trên Server

### Quyền ghi (Permissions)
Đảm bảo server có quyền ghi vào thư mục `storage/` (nằm trong thư mục theme hoặc thư mục root của WordPress tùy cấu hình Acorn) để lưu cache của Blade template.

### Kích hoạt Theme
1. Truy cập vào WP Admin -> Appearance -> Themes.
2. Tìm theme **Dailyve Theme** và nhấn **Activate**.

## 5. Các lệnh hữu ích (Nếu server có WP-CLI)
Nếu server có cài đặt WP-CLI và Acorn, bạn nên chạy các lệnh sau để tối ưu cache:
```bash
wp acorn view:cache
wp acorn config:cache
```

## 6. Lưu ý quan trọng
- Luôn chạy `npm run build` trước khi deploy để đảm bảo CSS/JS mới nhất được áp dụng.
- Nếu gặp lỗi "White Screen of Death", hãy kiểm tra log PHP để xem có thiếu thư viện nào trong `vendor/` hoặc phiên bản PHP không phù hợp hay không.
