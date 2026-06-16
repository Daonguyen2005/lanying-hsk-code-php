import urllib.request
import urllib.parse
import json

# Login as Super Admin (email: admin@lanying.com, pass: admin123)
req = urllib.request.Request("http://localhost:5000/api/auth/login", 
    data=json.dumps({"email": "admin@lanying.com", "password": "admin123"}).encode('utf-8'),
    headers={"Content-Type": "application/json"})
try:
    with urllib.request.urlopen(req) as response:
        res = json.loads(response.read().decode())
        token = res['access_token']
        print("Token:", token[:10] + "...")
        
        # Fetch /api/admin/users
        req2 = urllib.request.Request("http://localhost:5000/api/admin/users",
            headers={"Authorization": f"Bearer {token}"})
        with urllib.request.urlopen(req2) as response2:
            print("Users Response:", response2.read().decode())
except urllib.error.HTTPError as e:
    print("HTTPError:", e.code, e.read().decode())
