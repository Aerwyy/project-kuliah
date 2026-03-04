import re
import time
import pandas as pd
from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from webdriver_manager.chrome import ChromeDriverManager

# ==========================================
# 1. LOGIKA SENTIMEN (VERSI PALING AKURAT)
# ==========================================

def normalize_word(word):
    # Menghapus huruf berulang (baikk -> baik)
    return re.sub(r'(.)\1{1,}', r'\1', word.lower())

def analyze_sentiment_v5(text):
    if pd.isna(text) or text == "": return "Netral"
    
    # Skor awal dari Emoji
    positive_emojis = ['👍', '👌', '⭐', '🌟', '😍', '❤️', '🔥']
    score = 1 if any(emoji in text for emoji in positive_emojis) else 0

    # Kamus slang & typos hasil analisis 100 halaman
    pos_words = {
        'bagus', 'bgus', 'mantap', 'mantab', 'mantul', 'puas', 'cepat', 'cepet', 'fast',
        'original', 'ori', 'aman', 'oke', 'ok', 'recomended', 'recommended', 'rekomended',
        'sesuai', 'keren', 'adem', 'amanah', 'juara', 'joss', 'jos', 'sip', 'baik', 'good',
        'nice', 'alhamdulillah', 'berfungsi', 'awet', 'top', 'syuka', 'mendarat', 'selamat',
        'cakep', 'real', 'rill', 'asli', 'segel', 'mulus', 'ramah', 'best', 'love', 'terimakasih',
        'makasih', 'tks', 'thank', 'thanks', 'sempurna', 'terjangkau'
    }
    
    neg_words = {
        'kecewa', 'lambat', 'buruk', 'rusak', 'lecet', 'lama', 'tidak', 'kurang', 'nyesel',
        'nyesal', 'parah', 'zonk', 'jelek', 'penipu', 'kapok', 'pecah', 'penyok', 'error',
        'kendala', 'masalah', 'cacat', 'batal', 'cancel', 'bohong', 'huft', 'mengecewakan',
        'lowbat', 'soak', 'mati', 'telat', 'lelet'
    }
    
    negations = {'tidak', 'ga', 'gak', 'bukan', 'ngga', 'ndak'}
    text = text.lower()
    if 'terima kasih' in text: score += 1
    
    words = re.findall(r'\w+', text)
    skip_next = 0
    for i in range(len(words)):
        if skip_next > 0:
            skip_next -= 1
            continue
        word = normalize_word(words[i])
        
        # Logika Negasi (Contoh: "tidak mengecewakan" = Positif)
        if word in negations:
            found_logic = False
            for j in range(i + 1, min(i + 3, len(words))):
                next_word = normalize_word(words[j])
                if next_word in neg_words:
                    score += 1 
                    skip_next = j - i
                    found_logic = True
                    break
                elif next_word in pos_words:
                    score -= 1 
                    skip_next = j - i
                    found_logic = True
                    break
            if found_logic: continue
            
        if word in pos_words:
            score += 1
        elif word in neg_words:
            score -= 1
            
    if score > 0: return "Positif"
    elif score < 0: return "Negatif"
    else: return "Netral"

# ==========================================
# 2. SCRAPER SPESIFIK PRODUK (TANPA NAMA PRODUK)
# ==========================================

def scrape_one_product_clean(url, total_pages=3):
    chrome_options = Options()
    chrome_options.add_argument("--user-agent=Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36")
    
    driver = webdriver.Chrome(service=Service(ChromeDriverManager().install()), options=chrome_options)
    driver.get(url)
    
    scraped_data = []

    try:
        for page in range(1, total_pages + 1):
            print(f"--- Scraping Laman {page} ---")
            WebDriverWait(driver, 15).until(EC.presence_of_element_located((By.CSS_SELECTOR, "article")))
            
            # Scroll biar konten muncul
            for _ in range(3):
                driver.execute_script("window.scrollBy(0, 1000);")
                time.sleep(1)

            reviews = driver.find_elements(By.CSS_SELECTOR, "article")

            for rev in reviews:
                try:
                    user = rev.find_element(By.CSS_SELECTOR, "span.name").text
                    content = rev.find_element(By.CSS_SELECTOR, "span[data-testid='lblItemUlasan']").text
                    rating = rev.find_element(By.CSS_SELECTOR, "div[data-testid='icnStarRating']").get_attribute("aria-label")

                    # Analisis Sentimen Langsung
                    sentiment = analyze_sentiment_v5(content)

                    scraped_data.append({
                        "User": user,
                        "Rating": rating,
                        "Review": content,
                        "Sentiment": sentiment
                    })
                except:
                    continue

            # Tombol Next
            if page < total_pages:
                try:
                    next_btn = driver.find_element(By.CSS_SELECTOR, f"button[aria-label='Laman {page + 1}']")
                    driver.execute_script("arguments[0].click();", next_btn)
                    time.sleep(4)
                except:
                    break
    finally:
        driver.quit()
    
    return scraped_data

# ==========================================
# 3. JALANKAN
# ==========================================

# Masukkan URL produk spesifik di sini
url_produk = "https://www.tokopedia.com/samsung-official-store/samsung-mesin-cuci-wt95-twin-tub-dengan-dynamic-pulsator-9-5kg-wt95h3330mb-se-1732713416142194669/review"
jumlah_halaman = 5 

data = scrape_one_product_clean(url_produk, total_pages=jumlah_halaman)

if data:
    df = pd.DataFrame(data)
    df.to_csv("ulasan_produk_spesifik.csv", index=False, encoding='utf-8-sig')
    print(f"\nBeres! {len(df)} ulasan tersimpan di 'ulasan_produk_spesifik.csv'")
    print("\nHasil Sentimen:")
    print(df['Sentiment'].value_counts())
else:
    print("Gagal mengambil data.")