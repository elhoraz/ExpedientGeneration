import math
import struct
import wave
import random
import os

output_dir = "public/assets/audio"
os.makedirs(output_dir, exist_ok=True)
sample_rate = 44100

def save_wav(filename, samples):
    with wave.open(os.path.join(output_dir, filename), 'w') as f:
        f.setnchannels(1)
        f.setsampwidth(2)
        f.setframerate(sample_rate)
        for s in samples:
            f.writeframesraw(struct.pack('<h', int(s * 32767)))

print("Generating space.wav...")
# Space: Low frequency drone (50Hz + 55Hz)
samples_space = []
for i in range(sample_rate * 10): # 10 seconds
    t = float(i) / sample_rate
    v = (math.sin(2 * math.pi * 50 * t) + math.sin(2 * math.pi * 55 * t)) * 0.4
    samples_space.append(max(min(v, 1.0), -1.0))
save_wav("space.wav", samples_space)

print("Generating rain.wav...")
# Rain: White noise with some filtering
samples_rain = []
last_val = 0
for i in range(sample_rate * 10):
    val = random.uniform(-0.5, 0.5)
    last_val = (last_val * 0.7) + (val * 0.3) # Simple low pass
    samples_rain.append(max(min(last_val, 1.0), -1.0))
save_wav("rain.wav", samples_rain)

print("Generating ocean.wav...")
# Ocean: Pink noise modulated by a very slow sine wave (0.1Hz)
samples_ocean = []
last_val = 0
for i in range(sample_rate * 20): # 20 seconds for full wave cycle
    t = float(i) / sample_rate
    val = random.uniform(-0.3, 0.3)
    last_val = (last_val * 0.9) + (val * 0.1) # Stronger low pass
    mod = (math.sin(2 * math.pi * 0.1 * t) + 1.0) / 2.0 # 0.0 to 1.0
    samples_ocean.append(max(min(last_val * (0.2 + 0.8 * mod), 1.0), -1.0))
save_wav("ocean.wav", samples_ocean)

print("Generating zen.wav...")
# Zen: High frequency chimes (400Hz + 402Hz beating, slowly fading in and out)
samples_zen = []
for i in range(sample_rate * 10):
    t = float(i) / sample_rate
    v = (math.sin(2 * math.pi * 400 * t) + math.sin(2 * math.pi * 402 * t)) * 0.2
    mod = (math.sin(2 * math.pi * 0.2 * t) + 1.0) / 2.0
    samples_zen.append(max(min(v * mod, 1.0), -1.0))
save_wav("zen.wav", samples_zen)

print("Audio generated successfully.")
