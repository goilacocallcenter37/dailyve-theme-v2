---
name: Dailyve Premium Travel Design System
description: Hệ thống thiết kế cao cấp cho nền tảng đặt vé xe, tàu, máy bay tại Việt Nam. Tối ưu trải nghiệm người dùng, độ tin cậy và thẩm mỹ hiện đại.
license: MIT
metadata:
  author: Antigravity x Dailyve Team
---

# Dailyve Design System Guideline (Vietnam Travel)

## 🎯 Mission
Xây dựng một nền tảng đặt vé du lịch và vận tải hàng đầu Việt Nam, nơi sự **Tin cậy**, **Tốc độ** và **Thẩm mỹ Premium** hòa quyện để mang lại trải nghiệm đặt chỗ tuyệt vời nhất cho người Việt.

## 🏛️ Brand Foundations (Nhãn quan người Việt)
Người dùng Việt Nam ưu tiên sự **rõ ràng về giá**, **thông tin minh bạch** và **cảm giác an toàn**. Thiết kế phải thể hiện được sự chuyên nghiệp nhưng vẫn thân thiện.

### 🎨 Color Palette (Hệ màu Xanh Tin Cậy)
- **Primary (Blue)**: `#2196F3` (Màu xanh chủ đạo của theme cũ - Đại diện cho bầu trời và sự an tâm).
- **Primary Dark**: `#1565C0` (Sử dụng cho các nút hành động chính và tiêu đề).
- **Success (Green)**: `#16A34A` (Xác nhận vé, trạng thái còn chỗ).
- **Warning (Orange)**: `#F59E0B` (Thông báo, khuyến mãi, vé sắp hết).
- **Danger (Red)**: `#DC2626` (Hủy chuyến, hết chỗ).
- **Surface (Background)**: `#F8FAFC` (Nền xám nhạt để làm nổi bật các thẻ chuyến xe trắng).

### 🖋️ Typography (Phông chữ Hiện đại)
- **Primary Font**: `Montserrat` (Dùng cho toàn bộ văn bản để đảm bảo sự rõ ràng, dễ đọc).
- **Display Font**: `Space Grotesk` (Dùng cho tiêu đề lớn, giá vé để tạo cảm giác công nghệ và hiện đại).
- **Weights**: Bold (700) cho tiêu đề và giá, Medium (500) cho thông tin chuyến, Regular (400) cho văn bản phụ.

### 📐 Spacing & Grid (Hệ thống 8pt)
Mọi khoảng cách (Margin, Padding) phải là bội số của 8px (8, 16, 24, 32...) để tạo sự nhịp điệu và cân đối.

---

## 💎 Component-level Rules

### 1. Trip Card (Thẻ chuyến xe/tàu/máy bay)
- **Bo góc**: Phải sử dụng `rounded-3xl` (24px) để tạo sự mềm mại.
- **Đổ bóng**: `shadow-premium` (mềm, sâu, không bị gắt).
- **Timeline**: Điểm đi và điểm đến phải được nối với nhau bằng dải màu Gradient hoặc đường nét đứt tinh tế.
- **Giá vé**: Phải là thành phần nổi bật nhất, sử dụng font Bold và màu Primary.

### 2. Search Form (Thanh tìm kiếm)
- **Style**: Sử dụng hiệu ứng **Glassmorphism** (kính mờ) trên nền ảnh bìa (hero image).
- **Interactions**: Các ô chọn địa điểm phải có icon trực quan và hiệu ứng focus rõ rệt.
- **Button**: Nút "Tìm chuyến" phải là nút lớn nhất, sử dụng Gradient Blue để thu hút hành động.

### 3. Seat Selection (Sơ đồ chọn chỗ)
- **Trạng thái**: Ghế trống (trắng/viền xanh), ghế đang chọn (xanh đậm), ghế đã đặt (xám nhạt/khóa).
- **Mobile-first**: Các nút chọn ghế phải có kích thước tối thiểu 44px để dễ thao tác bằng ngón tay.

---

## 👁️ Accessibility & Content
- **Ngôn ngữ**: Sử dụng từ ngữ thuần Việt, ngắn gọn, dễ hiểu (Ví dụ: "Chọn chuyến" thay vì "Đặt ngay").
- **Độ tương phản**: Chữ trên nền phải đạt chuẩn WCAG AA để người lớn tuổi cũng có thể đặt vé dễ dàng.
- **Phản hồi**: Mọi hành động click phải có phản hồi thị giác (Loading state, Scale effect).

## 🚫 Anti-patterns (Những điều cần tránh)
- Không sử dụng quá 3 font chữ trên một trang.
- Không sử dụng màu sắc quá lòe loẹt làm mất tập trung vào thông tin vé.
- Tránh các góc nhọn 90 độ (gây cảm giác cứng nhắc, không hiện đại).
- Tuyệt đối không để giá vé mờ nhạt hoặc khó tìm.

## ✅ Quality Checklist (QA)
- [ ] Giao diện có hiển thị tốt trên iPhone/Android đời cũ không?
- [ ] Tốc độ load danh sách chuyến xe có dưới 2 giây không?
- [ ] Các hiệu ứng Gradient có mượt mà, không bị gãy màu không?
- [ ] Thông tin quan trọng (Giá, Giờ chạy) có nổi bật nhất không?