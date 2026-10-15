import 'package:flutter/material.dart';

class MenuDetailPage extends StatefulWidget {
  const MenuDetailPage({super.key});

  @override
  State<MenuDetailPage> createState() => _MenuDetailPageState();
}

class _MenuDetailPageState extends State<MenuDetailPage> {
  double _jumlahPorsi = 1.0;
  bool _pakaiAddOn = false;

  @override
  Widget build(BuildContext context) {
    final Map<String, dynamic> menu = 
        ModalRoute.of(context)?.settings.arguments as Map<String, dynamic>? ?? 
        {
          'nama': 'Nasi Goreng Spesial Bu Sari',
          'harga': 15000,
          'deskripsi': 'Disajikan dengan kerupuk, timun segar, dan suwiran ayam melimpah.',
          'gambar': 'https://images.unsplash.com/photo-1513104890138-7c749659a591?w=600',
          'namaAddOn': 'Tambah Ekstra Telur',
          'hargaAddOn': 4000,
        };

    final int hargaDasar = menu['harga'];
    final String namaAddOn = menu['namaAddOn'] ?? 'Tambah Ekstra';
    final int hargaAddOnSatuan = menu['hargaAddOn'] ?? 4000;

    int totalPorsi = _jumlahPorsi.round();
    
    // Kalkulasi harga add-on dikalikan jumlah porsi
    int tambahanBiaya = _pakaiAddOn ? (hargaAddOnSatuan * totalPorsi) : 0;
    int totalHarga = (hargaDasar * totalPorsi) + tambahanBiaya;

    return Scaffold(
      appBar: AppBar(
        title: Text('Kantin Bu Sari - ${menu['nama']}'),
        backgroundColor: Colors.deepOrange,
        foregroundColor: Colors.white,
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(20.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Widget 1
            ClipRRect(
              borderRadius: BorderRadius.circular(12),
              child: Image.network(
                menu['gambar'],
                height: 200,
                width: double.infinity,
                fit: BoxFit.cover,
              ),
            ),
            const SizedBox(height: 16),
            // Widget 2
            Text(
              menu['nama'],
              style: const TextStyle(fontSize: 22, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 8),
            // Widget 3
            Text(
              menu['deskripsi'],
              style: const TextStyle(color: Colors.grey),
            ),
            const SizedBox(height: 20),
            // Widget 4: Checkbox Add-on Sesuai Jenis Menu
            Row(
              children: [
                Checkbox(
                  value: _pakaiAddOn,
                  activeColor: Colors.deepOrange,
                  onChanged: (val) {
                    setState(() {
                      _pakaiAddOn = val ?? false;
                    });
                  },
                ),
                Text('$namaAddOn (+Rp $hargaAddOnSatuan / porsi)'),
              ],
            ),
            const SizedBox(height: 10),
            // Widget 5
            const Text('Jumlah Porsi:'),
            Slider(
              value: _jumlahPorsi,
              min: 1,
              max: 5,
              divisions: 4,
              label: totalPorsi.toString(),
              activeColor: Colors.deepOrange,
              onChanged: (val) {
                setState(() {
                  _jumlahPorsi = val;
                });
              },
            ),
            Text('Porsi dipilih: $totalPorsi porsi'),
            const SizedBox(height: 16),
            // Widget 6
            Container(
              padding: const EdgeInsets.all(12),
              decoration: BoxDecoration(
                color: Colors.deepOrange.shade50,
                borderRadius: BorderRadius.circular(8),
                border: Border.all(color: Colors.deepOrange.shade200),
              ),
              child: Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  const Text(
                    'Total Harga:',
                    style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
                  ),
                  Text(
                    'Rp $totalHarga',
                    style: const TextStyle(
                      fontSize: 18,
                      fontWeight: FontWeight.bold,
                      color: Colors.deepOrange,
                    ),
                  ),
                ],
              ),
            ),
            const SizedBox(height: 20),
            // Widget 7
            const TextField(
              decoration: InputDecoration(
                labelText: 'Catatan untuk Bu Sari (mis: Jangan pedes, karetnya 1 aja)',
                border: OutlineInputBorder(),
              ),
            ),
            const SizedBox(height: 30),
            // Widget 8
            SizedBox(
              width: double.infinity,
              child: ElevatedButton.icon(
                style: ElevatedButton.styleFrom(
                  backgroundColor: Colors.deepOrange,
                  foregroundColor: Colors.white,
                ),
                onPressed: () {
                  Navigator.pushNamed(
                    context, 
                    '/pesan',
                    arguments: {
                      'namaMenu': menu['nama'],
                      'totalHarga': totalHarga,
                      'jumlahPorsi': totalPorsi,
                      'pakaiAddOn': _pakaiAddOn,
                      'namaAddOn': namaAddOn,
                      'hargaAddOnSatuan': hargaAddOnSatuan,
                    },
                  );
                },
                icon: const Icon(Icons.shopping_cart),
                label: const Text('Pesan Sekarang'),
              ),
            ),
          ],
        ),
      ),
    );
  }
}