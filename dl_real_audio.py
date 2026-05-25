import urllib.request
import os

out_dir = "public/assets/audio"
os.makedirs(out_dir, exist_ok=True)

files = {
    "rain.mp3": "https://cdn.pixabay.com/audio/2021/08/04/audio_3d1bfd6f94.mp3",
    "ocean.mp3": "https://cdn.pixabay.com/audio/2022/02/07/audio_677eb93158.mp3",
    "space.mp3": "https://cdn.pixabay.com/audio/2022/03/15/audio_2056a2bbd2.mp3",
    "zen.mp3": "https://cdn.pixabay.com/audio/2021/09/06/audio_346dc0366f.mp3"
}

for filename, url in files.items():
    print(f"Downloading {filename}...")
    req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'})
    try:
        with urllib.request.urlopen(req) as response:
            with open(os.path.join(out_dir, filename), 'wb') as f:
                f.write(response.read())
        print(f"Success: {filename}")
    except Exception as e:
        print(f"Failed to download {filename}: {e}")
