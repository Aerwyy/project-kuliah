import pandas as pd
import matplotlib.pyplot as plt
import os
import re

file_names = [
    "Samsung 32 HD HE50E TV  HDR  OTS Lite  One UI Tizen  Slim Design  UA32HE50EFKLXD - 32 HD TV HE50E.csv",
    "Samsung 55 Crystal UHD 4K U8000F Smart TV  Crystal Processor 4K  SmartThings  UA55U8000FKXXD - 55 crystal.csv",
    "Samsung Mesin Cuci Front Load Washer 12kg - WW12CGP44DSBSE - WW12CGP44DSBSE.csv",
    "Samsung Mesin Cuci WA70 Top Load Wobble Technology - 7kg - White.csv",
    "Samsung Mesin Cuci WT85 Twin Tub dengan Dynamic Pulsator 8.5kg - WT85H3210MGSE - White.csv",
    "Samsung Mesin Cuci WT95 Twin Tub dengan Dynamic Pulsator 9.5kg - WT95H3330MBSE - Light Gray.csv",
    "Samsung Microwave Grill 23L dengan Browning Plus - MG23K3505AKSE - Black.csv",
    "Samsung Microwave Grill BESPOKE 23L - MG23T5068CWSE - Samsung Microwave Grill BESPOKE 23L.csv",
    "SAMSUNG RT20 KULKAS 2 PINTU W COOLPACK 12 JAM - 216L - Silver.csv",
    "Samsung Smart TV 43 QLED 4K QEF1  Q4 AI Processor  Samsung Knox Security  QA43QEF1AKXXD - 43.csv",
    "Samsung Smart TV 50 QLED 4K QEF1  Q4 AI Processor  Samsung Knox Security  QA50QEF1AKXXD - 50.csv"
]

all_data = []

def get_category(filename):
    lower_f = filename.lower()
    if "tv" in lower_f or "qled" in lower_f or "crystal" in lower_f:
        return "Smart TV"
    elif "mesin cuci" in lower_f or "washer" in lower_f or "wt85" in lower_f or "wa70" in lower_f:
        return "Washing Machine"
    elif "microwave" in lower_f:
        return "Microwave"
    elif "kulkas" in lower_f:
        return "Refrigerator"
    return "Other"

for file in file_names:
    if os.path.exists(file):
        df = pd.read_csv(file)
        df['Category'] = get_category(file)
        if 'Rating' in df.columns:
            df['Rating_Num'] = df['Rating'].apply(lambda x: int(re.search(r'\d+', str(x)).group()) if re.search(r'\d+', str(x)) else 0)
        all_data.append(df)
    else:
        print(f"Peringatan: File '{file}' tidak ditemukan.")

if all_data:
    combined_df = pd.concat(all_data, ignore_index=True)

    # --- 1. Grafik Pie: Distribusi Sentimen Keseluruhan ---
    plt.figure(figsize=(8, 6))
    sentiment_counts = combined_df['Sentiment'].value_counts()
    colors = ['#66b3ff', '#99ff99', '#ff9999'] # Biru (Pos), Hijau (Net), Merah (Neg)
    sentiment_counts.plot(kind='pie', autopct='%1.1f%%', startangle=140, colors=colors, explode=(0.05, 0, 0))
    plt.title('Proporsi Sentimen Keseluruhan (11 Produk Samsung - Alat Rumah Tangga)')
    plt.ylabel('')
    plt.show()

    # --- 2. Grafik Batang: Sentimen Per Kategori Produk ---
    # Memastikan kolom sentimen lengkap ada untuk diplot
    cat_sentiment = combined_df.groupby(['Category', 'Sentiment']).size().unstack(fill_value=0)
    # Filter kolom yang ada saja (menghindari error jika salah satu sentimen tidak ada di data)
    available_cols = [col for col in ['Positif', 'Netral', 'Negatif'] if col in cat_sentiment.columns]
    
    cat_sentiment[available_cols].plot(kind='bar', stacked=True, color=['#66b3ff', '#99ff99', '#ff9999'], figsize=(10, 6))
    plt.title('Distribusi Sentimen Berdasarkan Kategori Produk')
    plt.xlabel('Kategori Produk')
    plt.ylabel('Jumlah Ulasan')
    plt.xticks(rotation=45)
    plt.legend(title='Sentimen')
    plt.tight_layout()
    plt.show()

    # --- 3. Grafik Batang: Rata-rata Rating Per Kategori ---
    plt.figure(figsize=(10, 6))
    avg_rating = combined_df.groupby('Category')['Rating_Num'].mean().sort_values(ascending=False)
    avg_rating.plot(kind='bar', color='skyblue')
    plt.title('Rata-rata Rating (Bintang) Per Kategori')
    plt.ylabel('Rating (1-5)')
    plt.ylim(0, 5.5)
    plt.xticks(rotation=45)
    plt.tight_layout()
    plt.show()
else:
    print("Tidak ada data ulasan yang berhasil dimuat untuk analisis.")