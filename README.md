# CVE2024-24048_Likeshop_Fix

## Môn học: Bảo mật web và ứng dụng (NT213.Q12.ANTT) - Nhóm 3

### Thành viên thực hiện

| Họ và tên | MSSV | Email |
| :--- | :--- | :--- |
| Trịnh Nhật Duy | 23520394 | 23520394@gm.uit.edu.vn |
| Lê Văn Khôi | 23520770 | 23520770@gm.uit.edu.vn |
| Đặng Minh Quân | 23521251 | 23521251@gm.uit.edu.vn |
| Nguyễn Đức Trung | 23521678 | 23521678@gm.uit.edu.vn |

---

## Tổng quan dự án

Dự án này tập trung vào việc nghiên cứu, khai thác và khắc phục lỗ hổng **SSRF (Server-Side Request Forgery)** trên nền tảng mã nguồn mở **LikeShop** (phiên bản trước 2.5.7).

### Lỗ hổng mục tiêu: CVE-2024-24028
- **Loại lỗ hổng:** SSRF (Server-Side Request Forgery).
- **Mô tả:** Lỗ hổng cho phép kẻ tấn công điều hướng máy chủ thực hiện các yêu cầu HTTP không mong muốn thông qua tham số `avatar` trong chức năng `UserLogic::updateWechatInfo` (endpoint `/api/user/setWechatInfo`). 
- **Hệ quả:** Có thể dẫn đến việc rò rỉ thông tin nhạy cảm từ các dịch vụ nội bộ (Intranet), quét cổng, hoặc tương tác với Metadata Service của các nhà cung cấp đám mây (AWS, Azure, GCP).

---

## Cấu trúc thư mục

### 1. `LikeShop/`
Chứa toàn bộ mã nguồn của ứng dụng LikeShop phiên bản bị lỗi. Đây là môi trường để thực hiện các bài thử nghiệm khai thác lỗ hổng SSRF.

### 2. `SSRFmap/`
Công cụ mã nguồn mở [SSRFmap](https://github.com/swisskyrepo/SSRFmap) dùng để tự động hóa việc tìm kiếm và khai thác các lỗ hổng SSRF. Công cụ này hỗ trợ nhiều module khác nhau để tương tác với các dịch vụ nội bộ sau khi đã xác định được điểm yếu.

### 3. `Custom_SSRFmap/` (Nhóm tự phát triển)
Các module bổ sung được nhóm viết riêng để tối ưu hóa việc khai thác trên mục tiêu LikeShop:
- **`mass_scan.py`**: Tự động quét một danh sách dài các tệp tin hệ thống nhạy cảm (như `/etc/passwd`, `/proc/self/environ`, cấu hình mạng...) thông qua lỗ hổng SSRF của LikeShop. Các mục tiêu hợp lệ sẽ được lưu vào tệp `valid_targets.txt`.
- **`dump_content.py`**: Đọc danh sách từ `valid_targets.txt`, thực hiện truy vấn lại để lấy URL kết quả từ API `/api/user/info` và tải nội dung chi tiết của tệp tin đó về máy của kẻ tấn công.
- **`aws.py`**: Module tùy chỉnh nhằm khai thác Metadata Service của AWS để lấy các thông tin nhạy cảm như IAM credentials, user-data, etc.

### 4. `LikeShop-Fix/`
Chứa mã nguồn LikeShop đã được nhóm thực hiện các biện pháp khắc phục (khớp với các bản vá trong phiên bản 2.5.7 trở lên). Các biện pháp này bao gồm việc kiểm tra tính hợp lệ của URL, chặn các giao thức nguy hiểm (như `file://`, `gopher://`, `ftp://`) và danh sách đen các IP nội bộ.
