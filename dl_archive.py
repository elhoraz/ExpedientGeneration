import urllib.request
import os

out_dir = "public/assets/audio"
os.makedirs(out_dir, exist_ok=True)

url = "https://archive.org/download/RainSounds10HoursAndNight/Rain%20Sounds%2010%20Hours%20%E2%98%94%20and%20Night%20--h0r1O085u0.mp3"
filename = "rain.mp3"

print(f"Downloading {filename}...")
req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'})
try:
    with urllib.request.urlopen(req) as response:
        with open(os.path.join(out_dir, filename), 'wb') as f:
            f.write(response.read())
    print(f"Success: {filename}")
except Exception as e:
    print(f"Failed to download {filename}: {e}")
