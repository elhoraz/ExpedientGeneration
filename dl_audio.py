import urllib.request
import json
import os

out_dir = "public/assets/audio"
os.makedirs(out_dir, exist_ok=True)

def search_and_download(prefix, filename):
    url = f"https://en.wikipedia.org/w/api.php?action=query&list=allimages&aimime=audio/ogg&aiprop=url&aiprefix={prefix}&format=json"
    req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0'})
    try:
        with urllib.request.urlopen(req) as response:
            data = json.loads(response.read().decode())
            images = data.get('query', {}).get('allimages', [])
            if images:
                file_url = images[0]['url']
                print(f"Downloading {file_url} for {filename}...")
                urllib.request.urlretrieve(file_url, os.path.join(out_dir, filename))
                print("Done!")
            else:
                print(f"No results for {prefix}")
    except Exception as e:
        print("Error:", e)

search_and_download("Rain", "rain.ogg")
search_and_download("Ocean", "ocean.ogg")
search_and_download("Space", "space.ogg")
search_and_download("Wind", "zen.ogg")
