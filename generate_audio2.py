import math
import struct
import wave
import random
import os

output_dir = "public/assets/audio"
os.makedirs(output_dir, exist_ok=True)
sample_rate = 44100
duration = 5 # 5 seconds loop to save space

def save_wav(filename, samples):
    with wave.open(os.path.join(output_dir, filename), 'w') as f:
        f.setnchannels(1)
        f.setsampwidth(2)
        f.setframerate(sample_rate)
        
        # Normalize and pack
        max_amp = max(abs(s) for s in samples) if samples else 1.0
        if max_amp == 0: max_amp = 1.0
        
        for s in samples:
            # Normalize to 0.8 to prevent clipping, then scale to 16-bit int
            val = (s / max_amp) * 0.8
            f.writeframesraw(struct.pack('<h', int(val * 32767)))

# 1. RAIN: White noise with strong high-pass and low-pass to sound like rain
print("Generating rain.wav...")
samples_rain = []
for i in range(sample_rate * duration):
    val = random.uniform(-1.0, 1.0)
    samples_rain.append(val)
save_wav("rain.wav", samples_rain)

# 2. OCEAN: Pink noise modulated by a very slow sine wave (0.1Hz)
print("Generating ocean.wav...")
samples_ocean = []
for i in range(sample_rate * 10): # 10 seconds for ocean
    t = float(i) / sample_rate
    val = random.uniform(-1.0, 1.0)
    # Slow wave from 0.2 to 1.0
    mod = (math.sin(2 * math.pi * 0.1 * t) + 1.0) / 2.0 
    samples_ocean.append(val * (0.3 + 0.7 * mod))
save_wav("ocean.wav", samples_ocean)

# 3. SPACE: 200Hz, 202Hz, 400Hz, 405Hz combined for a sci-fi drone (audible on laptops!)
print("Generating space.wav...")
samples_space = []
for i in range(sample_rate * duration):
    t = float(i) / sample_rate
    v = math.sin(2 * math.pi * 200 * t) + \
        math.sin(2 * math.pi * 202 * t) + \
        (math.sin(2 * math.pi * 400 * t) * 0.5) + \
        (math.sin(2 * math.pi * 405 * t) * 0.5)
    samples_space.append(v)
save_wav("space.wav", samples_space)

# 4. ZEN/WIND: Filtered noise that sweeps slowly like wind
print("Generating zen.wav...")
samples_zen = []
last = 0
for i in range(sample_rate * 10):
    t = float(i) / sample_rate
    val = random.uniform(-1.0, 1.0)
    # Variable low pass filter to simulate wind gusts
    cutoff = 0.9 + 0.05 * math.sin(2 * math.pi * 0.2 * t)
    last = (last * cutoff) + (val * (1.0 - cutoff))
    samples_zen.append(last * 5.0) # Boost before normalize
save_wav("zen.wav", samples_zen)

print("Audio generated successfully.")
