from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.common.by import By
from webdriver_manager.chrome import ChromeDriverManager
import time
import pandas as pd
import re

def setup_driver():
    options = webdriver.ChromeOptions()
    options.add_argument("--start-maximized")
    options.add_argument("--disable-blink-features=AutomationControlled")
    options.add_argument("user-agent=Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36")
    driver = webdriver.Chrome(service=Service(ChromeDriverManager().install()), options=options)
    return driver

def scroll_smart(driver):
    """Scroll dengan pola naik-turun untuk memicu lazy load Tokopedia"""
    print("  > Sedang memuat semua produk (sabar ya, butuh waktu)...")
    last_height = driver.execute_script("return document.body.scrollHeight")
    
    # Scroll bertahap ke bawah
    for i in range(15): # Loop scroll diperbanyak
        driver.execute_script("window.scrollBy(0, 800);")
        time.sleep(1) # Tunggu load
        
        # Setiap 5 kali scroll, coba scroll naik dikit biar trigger load elemen yang macet
        if i % 5 == 0:
            driver.execute_script("window.scrollBy(0, -200);")
            time.sleep(0.5)
            driver.execute_script("window.scrollBy(0, 200);")

def clean_price(price_text):
    if not price_text: return 0
    clean = re.sub(r'[^\d]', '', price_text) 
    return int(clean) if clean else 0

def clean_sold(sold_text):
    if not sold_text: return 0
    txt = sold_text.lower()
    multiplier = 1
    if "rb" in txt:
        multiplier = 1000
    match = re.search(r"(\d+([.,]\d+)?)", txt)
    if match:
        num_str = match.group(1).replace(',', '.') 
        return int(float(num_str) * multiplier)
    return 0

def clean_name_final(text):
    if not text: return ""
    text = text.replace(";", "").replace(",", " ").replace("\n", " ")
    return text.strip()

def scrape_tokopedia_v7(url):
    driver = setup_driver()
    data = []
    
    try:
        print(f"--- START SCRAPING ---\nURL: {url}")
        driver.get(url)
        time.sleep(5)
        scroll_smart(driver) # Panggil fungsi scroll baru
        
        # Selektor elemen
        product_cards = driver.find_elements(By.XPATH, "//div[contains(@class, 'css') and .//div[contains(text(), 'Rp')]]")
        print(f"  > Kandidat elemen ditemukan: {len(product_cards)} (Termasuk sampah & duplikat)")
        
        # --- BLACKLIST EXTRA ---
        # Kata-kata yang pasti BUKAN produk
        nav_blacklist = [
            "jakarta pusat", "pesanan diproses", "dikirim dari", "online", "rata-rata",
            "ulasan", "diskusi", "chat penjual", "etalase", "filter", "urutkan",
            "belanja di aplikasi", "terbaru", "harga tertinggi", "harga terendah", 
            "galaxy foldables", "tutup", "batal", "promo", "info toko"
        ]

        # --- KEYWORDS WAJIB (WHITELIST) ---
        # Karena ini official store Samsung, nama produk PASTI mengandung salah satu ini
        valid_keywords = ["samsung", "galaxy", "buds", "watch", "tv", "monitor", "kulkas", "mesin cuci", "microwave", "bespoke", "flip", "fold", "adapter", "battery", "case", "cover"]

        for card in product_cards:
            try:
                lines = [line.strip() for line in card.text.split('\n') if line.strip()]
                
                # 1. CARI HARGA VALID
                price_index = -1
                raw_price = ""
                for i, line in enumerate(lines):
                    if "Rp" in line:
                        c_price = clean_price(line)
                        if c_price > 20000: # Filter harga dummy < 20rb
                            price_index = i
                            raw_price = line
                            break 
                
                if price_index == -1: continue

                # 2. CARI NAMA (DI ATAS HARGA)
                candidates_above = lines[:price_index]
                final_name = ""
                
                # Filter kata sampah umum
                junk_words = ["%", "diskon", "cashback", "terlaris", "preorder", "stok", "ad", "sisa", "off", "hemat", "pilih", "ganti", "tersedia", "gratis", "official", "store", "bintang", "wib", "terjual", "kab.", "kota"]
                
                for text in candidates_above:
                    text_lower = text.lower()
                    
                    # Cek 1: Blacklist Mutlak
                    if any(nav in text_lower for nav in nav_blacklist): continue 
                    
                    # Cek 2: Junk Words
                    if any(junk in text_lower for junk in junk_words): continue
                    
                    # Cek 3: VALIDASI PRODUK (Kunci Utama Fix Ini)
                    # Nama harus > 10 char DAN mengandung keyword Samsung/Galaxy/dll
                    has_keyword = any(k in text_lower for k in valid_keywords)
                    
                    if len(text) > 15 and has_keyword:
                        final_name = text
                        break # Ketemu nama valid!
                
                # Jika tidak ada nama yang lolos keyword check, skip baris ini
                if not final_name: continue

                # 3. CARI RATING & TERJUAL (DI BAWAH HARGA)
                candidates_below = lines[price_index+1:]
                final_rating = 0.0
                final_sold = 0
                
                for text in candidates_below:
                    if "." in text and len(text) <= 4:
                        try:
                            val = float(text)
                            if 3.0 <= val <= 5.0: final_rating = val
                        except: pass
                    
                    if "erjual" in text or "rb+" in text:
                        final_sold = clean_sold(text)

                cleaned_name = clean_name_final(final_name)
                
                if cleaned_name:
                    data.append({
                        "Nama Produk": cleaned_name,
                        "Harga": clean_price(raw_price),
                        "Rating": final_rating,
                        "Terjual": final_sold
                    })
                
            except Exception:
                continue

    except Exception as e:
        print(f"Error: {e}")
    finally:
        driver.quit()
        
    return data

# --- EKSEKUSI ---
target_url = "https://www.tokopedia.com/samsung-official-store/product"
hasil = scrape_tokopedia_v7(target_url)

if hasil:
    df = pd.DataFrame(hasil)
    
    # Hapus duplikat total (Nama + Harga sama persis)
    df = df.drop_duplicates(subset=['Nama Produk', 'Harga'])
    
    # Sorting biar enak dilihat
    df = df.sort_values(by="Harga", ascending=False)
    
    # Simpan
    filename = "scrap_result.csv"
    df.to_csv(filename, index=False)
    
    print("\n--- SELESAI ---")
    print(f"Total Data Bersih: {len(df)}")
    print(f"File disimpan: {filename}")
    print(df.head(10))
else:
    print("Tidak ada data. Cek koneksi atau URL.")