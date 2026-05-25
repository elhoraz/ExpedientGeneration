import urllib.request
import os

out_dir = "public/assets/audio"
os.makedirs(out_dir, exist_ok=True)

files = {
    "rain.mp3": "https://www.soundjay.com/nature/sounds/rain-01.mp3",
    "ocean.mp3": "https://www.soundjay.com/nature/sounds/ocean-wave-1.mp3",
    "zen.mp3": "https://www.soundjay.com/nature/sounds/wind-1.mp3",
    "space.mp3": "https://www.soundjay.com/misc/sounds/spaceship-interior-1.mp3"
}

for filename, url in files.items():
    print(f"Downloading {filename}...")
    req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0'})
    try:
        with urllib.request.urlopen(req) as response:
            with open(os.path.join(out_dir, filename), 'wb') as f:
                f.write(response.read())
        print(f"Success: {filename}")
    except Exception as e:
        print(f"Failed to download {filename}: {e}")
