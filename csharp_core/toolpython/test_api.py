import urllib.request, json

try:
    req2 = urllib.request.Request('http://127.0.0.1:5000/api/tutors/schedule?mock_user_id=5&start_date=2026-06-15&end_date=2026-06-21')
    res2 = urllib.request.urlopen(req2)
    slots = json.loads(res2.read().decode())
    print(f"Total slots returned: {len(slots)}")
    if len(slots) > 0:
        print("First slot:")
        print(slots[0])
except Exception as e:
    print(e)
