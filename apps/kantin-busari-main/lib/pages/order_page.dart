import 'package:flutter/material.dart';

class OrderPage extends StatelessWidget {
  const OrderPage({super.key});

  @override
  Widget build(BuildContext context) {
    final Map<String, dynamic>? args = 
        ModalRoute.of(context)?.settings.arguments as Map<String, dynamic>?;

    final String namaMenu = args?['namaMenu'] ?? 'Nasi Goreng Spesial Bu Sari';
    final int totalHarga = args?['totalHarga'] ?? 15000;
    final int jumlahPorsi = args?['jumlahPorsi'] ?? 1;
    final bool pakaiTelur = args?['pakaiTelur'] ?? false;

    return Scaffold(
      appBar: AppBar(
        title: const Text('Kantin Bu Sari - Pesanan Kamu'),
        backgroundColor: Colors.deepOrange,
        foregroundColor: Colors.white,
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(24.0),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            // Widget 1: Icon Sukses
            const Icon(Icons.check_circle, size: 80, color: Colors.green),
            const SizedBox(height: 16),
            
            // Widget 2: Judul Teks
            const Text(
              'Pesanan Diterima!',
              style: TextStyle(fontSize: 22, fontWeight: FontWeight.bold),
              textAlign: TextAlign.center,
            ),
            const SizedBox(height: 8),
            
            // Widget 3: Sub-teks instruksi
            const Text('Tunjukkan struk ini ke kasir Bu Sari.'),
            const SizedBox(height: 12),

            // Widget 4: Chip Status Pesanan
            Chip(
              avatar: const Icon(Icons.hourglass_top, size: 16, color: Colors.deepOrange),
              label: const Text('Status: Menunggu Diproses'),
              backgroundColor: Colors.deepOrange.shade50,
            ),
            const SizedBox(height: 16),
            
            // Widget 5: Card Ringkasan Pesanan Dinamis
            Card(
              elevation: 4,
              child: Padding(
                padding: const EdgeInsets.all(16.0),
                child: Column(
                  children: [
                    const Text('No. Antrian: #065124018', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
                    const Divider(),
                    Text('Menu: $namaMenu', textAlign: TextAlign.center, style: const TextStyle(fontWeight: FontWeight.w600)),
                    Text('Jumlah Porsi: $jumlahPorsi porsi'),
                    Text('Ekstra Telur: ${pakaiTelur ? "Ya (+Rp ${4000 * jumlahPorsi})" : "Tidak"}'),
                    const Divider(),
                    Text(
                      'Total Pembayaran: Rp $totalHarga',
                      style: const TextStyle(
                        fontWeight: FontWeight.bold, 
                        fontSize: 16, 
                        color: Colors.deepOrange,
                      ),
                    ),
                  ],
                ),
              ),
            ),
            
            const SizedBox(height: 16),

            // Widget 6: Informasi Waktu
            const Row(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                Icon(Icons.access_time, size: 16, color: Colors.grey),
                SizedBox(width: 6),
                Text('Estimasi waktu penyajian: ± 10 menit', style: TextStyle(color: Colors.grey)),
              ],
            ),

            const SizedBox(height: 24),
            
            // Widget 7: Tombol Kembali ke Beranda
            ElevatedButton.icon(
              style: ElevatedButton.styleFrom(
                backgroundColor: Colors.deepOrange,
                foregroundColor: Colors.white,
              ),
              onPressed: () {
                ScaffoldMessenger.of(context).showSnackBar(
                  const SnackBar(content: Text('Pesanan berhasil diselesaikan!')),
                );
                Navigator.popUntil(context, (route) => route.isFirst);
              },
              icon: const Icon(Icons.home),
              label: const Text('Kembali ke Daftar Menu'),
            ),
          ],
        ),
      ),
    );
  }
}