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
# 1. LOGIKA ANALISIS SENTIMEN (VERSI OPTIMAL 100 PAGE)
# ==========================================

def normalize_word(word):
    # Menghapus huruf berulang (baikk -> baik)
    return re.sub(r'(.)\1{1,}', r'\1', word.lower())

def analyze_sentiment_final(text):
    if pd.isna(text) or text == "": return "Netral"
    
    # Emoji Score
    positive_emojis = ['👍', '👌', '⭐', '🌟', '😍', '❤️', '🔥']
    score = 1 if any(emoji in text for emoji in positive_emojis) else 0

    # Kamus hasil analisis menyeluruh
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
        
        # Logika Negasi cerdas
        if word in negations:
            found_logic = False
            for j in range(i + 1, min(i + 3, len(words))):
                next_word = normalize_word(words[j])
                if next_word in neg_words:
                    score += 1 # "tidak mengecewakan" -> Positif
                    skip_next = j - i
                    found_logic = True
                    break
                elif next_word in pos_words:
                    score -= 1 # "tidak bagus" -> Negatif
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
# 2. LOGIKA SCRAPER (FIX PRODUCT NAME)
# ==========================================

def start_scraping(url, total_pages=3):
    chrome_options = Options()
    chrome_options.add_argument("--user-agent=Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36")
    
    driver = webdriver.Chrome(service=Service(ChromeDriverManager().install()), options=chrome_options)
    driver.get(url)
    scraped_data = []

    try:
        for page in range(1, total_pages + 1):
            print(f"--- Memproses Laman {page} ---")
            WebDriverWait(driver, 15).until(EC.presence_of_element_located((By.CSS_SELECTOR, "article")))
            
            for _ in range(3):
                driver.execute_script("window.scrollBy(0, 1000);")
                time.sleep(1)

            reviews = driver.find_elements(By.CSS_SELECTOR, "article")

            for rev in reviews:
                try:
                    user = rev.find_element(By.CSS_SELECTOR, "span.name").text
                    
                    # --- FIX NAMA PRODUK DENGAN MULTI-SELECTOR ---
                    product_raw = ""
                    potential_selectors = [
                        "p.css-d2yr2-unf-heading",       # Inspect terbaru kamu
                        "p.e1qvo2ff8",                  # Class stabil (Typography)
                        "p.css-akhxpb-unf-heading",     # Inspect lama
                        "[data-testid='lblReviewProduct']",
                        "a[data-testid='lblReviewProductName']"
                    ]

                    for selector in potential_selectors:
                        try:
                            target = rev.find_element(By.CSS_SELECTOR, selector)
                            if target.text:
                                product_raw = target.text
                                break # Berhenti jika sudah dapat teksnya
                        except:
                            continue

                    if product_raw:
                        # Bersihkan tag kampanye [...] dan spasi berlebih
                        product = re.sub(r'\[.*?\]', '', product_raw).strip()
                    else:
                        product = "Samsung Device"

                    content = rev.find_element(By.CSS_SELECTOR, "span[data-testid='lblItemUlasan']").text
                    rating = rev.find_element(By.CSS_SELECTOR, "div[data-testid='icnStarRating']").get_attribute("aria-label")

                    scraped_data.append({
                        "User": user,
                        "Product": product,
                        "Rating": rating,
                        "Review": content,
                        "Sentiment": analyze_sentiment_final(content)
                    })
                except:
                    continue

            # Navigasi Halaman
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
# 3. EKSEKUSI
# ==========================================

target_url = "https://www.tokopedia.com/samsung-official-store/review"
jumlah_halaman = 10 # <--- Atur jumlah halaman di sini

hasil_akhir = start_scraping(target_url, total_pages=jumlah_halaman)

if hasil_akhir:
    df = pd.DataFrame(hasil_akhir)
    df.to_csv("data_samsung_final_fix.csv", index=False, encoding='utf-8-sig')
    print(f"\nSukses! {len(df)} ulasan disimpan.")
    print(df['Sentiment'].value_counts())