import urllib.request
import urllib.parse
import json
import os

out_dir = "public/assets/audio"
os.makedirs(out_dir, exist_ok=True)

def download_wiki_audio(query, filename):
    print(f"Searching Wikipedia for: {query}")
    # Search for audio files
    search_url = f"https://en.wikipedia.org/w/api.php?action=query&list=search&srsearch={urllib.parse.quote(query)}&srnamespace=6&format=json"
    req = urllib.request.Request(search_url, headers={'User-Agent': 'Mozilla/5.0'})
    
    try:
        with urllib.request.urlopen(req) as response:
            data = json.loads(response.read().decode())
            results = data.get('query', {}).get('search', [])
            
            # Find first audio file (.ogg or .mp3)
            title = None
            for r in results:
                t = r['title']
                if t.lower().endswith('.ogg') or t.lower().endswith('.mp3'):
                    title = t
                    break
            
            if not title:
                print(f"No audio file found for {query}")
                return
            
            print(f"Found file: {title}, fetching URL...")
            # Get imageinfo for the file
            info_url = f"https://en.wikipedia.org/w/api.php?action=query&titles={urllib.parse.quote(title)}&prop=imageinfo&iiprop=url&format=json"
            req2 = urllib.request.Request(info_url, headers={'User-Agent': 'Mozilla/5.0'})
            
            with urllib.request.urlopen(req2) as resp2:
                data2 = json.loads(resp2.read().decode())
                pages = data2.get('query', {}).get('pages', {})
                for page_id, page_data in pages.items():
                    if 'imageinfo' in page_data:
                        file_url = page_data['imageinfo'][0]['url']
                        print(f"Downloading {file_url} -> {filename}")
                        
                        req3 = urllib.request.Request(file_url, headers={'User-Agent': 'Mozilla/5.0'})
                        with urllib.request.urlopen(req3) as resp3:
                            with open(os.path.join(out_dir, filename), 'wb') as f:
                                f.write(resp3.read())
                        print("Success!")
                        return
                        
    except Exception as e:
        print("Error:", e)

# Queries that likely have good audio
download_wiki_audio("Rain sound effect filetype:ogg", "rain.ogg")
download_wiki_audio("Ocean waves filetype:ogg", "ocean.ogg")
download_wiki_audio("Wind howling filetype:ogg", "zen.ogg")
download_wiki_audio("Space ambient filetype:ogg", "space.ogg")
