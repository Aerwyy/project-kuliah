import 'package:flutter/material.dart';

class MenuListPage extends StatefulWidget {
  const MenuListPage({super.key});

  @override
  State<MenuListPage> createState() => _MenuListPageState();
}

class _MenuListPageState extends State<MenuListPage> {
  final TextEditingController _searchController = TextEditingController();
  String _searchQuery = '';
  
  int _limitMenu = 5;

  // Data 10 Menu Lengkap
  final List<Map<String, dynamic>> _allMenus = [
    {
      'nama': 'Nasi Goreng Spesial Bu Sari',
      'harga': 15000,
      'deskripsi': 'Disajikan dengan kerupuk, timun segar, dan suwiran ayam melimpah.',
      'gambar': 'https://images.unsplash.com/photo-1647093953000-9065ed6f85ef?q=80&w=735&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=NHwxMjA3fDA%3D?w=600',
      'tipeAddOn': 'telur',
      'namaAddOn': 'Tambah Ekstra Telur',
      'hargaAddOn': 4000,
    },
    {
      'nama': 'Mie Ayam Pangsit',
      'harga': 13000,
      'deskripsi': 'Mie kenyal dengan topping ayam gurih dan pangsit goreng renyah.',
      'gambar': 'https://images.unsplash.com/photo-1707886114235-4080ed0dc82f?q=80&w=747&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=NHwxMjA3fDA%3D?w=600',
      'tipeAddOn': 'telur',
      'namaAddOn': 'Tambah Ekstra Pangsit Rebus',
      'hargaAddOn': 3000,
    },
    {
      'nama': 'Es Teh Manis Segar',
      'harga': 4000,
      'deskripsi': 'Teh pilihan dengan gula asli, sangat segar diminum siang hari.',
      'gambar': 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=600',
      'tipeAddOn': 'minuman',
      'namaAddOn': 'Ekstra Es Batu / Lebih Manis',
      'hargaAddOn': 1000,
    },
    {
      'nama': 'Nasi Ayam Geprek',
      'harga': 18000,
      'deskripsi': 'Ayam crispy dengan sambal bawang super pedas sesuai selera.',
      'gambar': 'https://images.unsplash.com/photo-1599354607448-8ad6e92b027a?q=80&w=1167&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=NHwxMjA3fDA%3D?w=600',
      'tipeAddOn': 'telur',
      'namaAddOn': 'Tambah Ekstra Keju Mozzarella',
      'hargaAddOn': 5000,
    },
    {
      'nama': 'Bakso Urat Sapi',
      'harga': 14000,
      'deskripsi': 'Bakso daging sapi asli dengan kuah kaldu sapi yang hangat dan gurih.',
      'gambar': 'https://images.unsplash.com/photo-1687425973283-d0d266b73325?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=NHwxMjA3fDA%3D?w=600',
      'tipeAddOn': 'telur',
      'namaAddOn': 'Tambah Tetelan Daging Sapi',
      'hargaAddOn': 5000,
    },
    {
      'nama': 'Soto Ayam Lamongan',
      'harga': 16000,
      'deskripsi': 'Soto ayam berkuah kuning koya gurih dengan suwiran ayam.',
      'gambar': 'https://images.unsplash.com/photo-1677029969063-23ecbb98d0af?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=NHwxMjA3fDA%3D?w=600',
      'tipeAddOn': 'telur',
      'namaAddOn': 'Tambah Telur Muda / Ampela',
      'hargaAddOn': 4000,
    },
    {
      'nama': 'Es Jeruk Peras',
      'harga': 5000,
      'deskripsi': 'Jeruk murni diperas segar dengan tambahan es batu kristal.',
      'gambar': 'https://images.unsplash.com/photo-1629647483406-df26956b258a?q=80&w=1964&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=NHwxMjA3fDA%3D?w=600',
      'tipeAddOn': 'minuman',
      'namaAddOn': 'Ekstra Jeruk Peras (Double)',
      'hargaAddOn': 3000,
    },
    {
      'nama': 'Bubur Ayam Spesial',
      'harga': 12000,
      'deskripsi': 'Bubur hangat dengan cakwe, kacang kedelai, dan suwiran ayam.',
      'gambar': 'https://images.unsplash.com/photo-1750127885334-5d983eb7967b?q=80&w=1934&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=NHwxMjA3fDA%3D?w=600',
      'tipeAddOn': 'telur',
      'namaAddOn': 'Tambah Telur Puyuh Tusuk',
      'hargaAddOn': 3000,
    },
    {
      'nama': 'Jus Alpukat Cokelat',
      'harga': 10000,
      'deskripsi': 'Jus alpukat kental dengan lumuran susu cokelat nikmat.',
      'gambar': 'https://images.unsplash.com/photo-1630252595285-3bbcb51378d8?q=80&w=2127&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=NHwxMjA3fDA%3D?w=600',
      'tipeAddOn': 'minuman',
      'namaAddOn': 'Tambah Extra Susu Cokelat',
      'hargaAddOn': 2000,
    },
    {
      'nama': 'Pisang Goreng Keju',
      'harga': 9000,
      'deskripsi': 'Pisang manis digoreng krispi ditaburi keju parut melimpah.',
      'gambar': 'https://images.unsplash.com/photo-1694004599545-9f25feaf665a?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=NHwxMjA3fDA%3D?w=600',
      'tipeAddOn': 'telur',
      'namaAddOn': 'Tambah Extra Keju Susu',
      'hargaAddOn': 3000,
    },
  ];

  @override
  Widget build(BuildContext context) {
    final filteredMenus = _allMenus.where((menu) {
      final namaMenu = menu['nama'].toString().toLowerCase();
      return namaMenu.contains(_searchQuery.toLowerCase());
    }).toList();

    final displayedMenus = _searchQuery.isEmpty 
        ? filteredMenus.take(_limitMenu).toList() 
        : filteredMenus;

    return Scaffold(
      appBar: AppBar(
        title: const Text('Kantin Bu Sari - Daftar Menu'),
        backgroundColor: Colors.deepOrange,
        foregroundColor: Colors.white,
      ),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Widget 1
            const Text(
              'Selamat Datang di Kantin Bu Sari!',
              style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
              textAlign: TextAlign.center,
            ),
            const SizedBox(height: 8),
            // Widget 2
            const Text(
              'Pilih menu favoritmu di bawah ini:',
              textAlign: TextAlign.center,
            ),
            const SizedBox(height: 16),
            // Widget 3
            TextField(
              controller: _searchController,
              onChanged: (value) {
                setState(() {
                  _searchQuery = value;
                });
              },
              decoration: InputDecoration(
                labelText: 'Cari makanan atau minuman...',
                prefixIcon: const Icon(Icons.search),
                suffixIcon: _searchQuery.isNotEmpty
                    ? IconButton(
                        icon: const Icon(Icons.clear),
                        onPressed: () {
                          setState(() {
                            _searchController.clear();
                            _searchQuery = '';
                          });
                        },
                      )
                    : null,
                border: const OutlineInputBorder(),
              ),
            ),
            const SizedBox(height: 16),
            // Widget 4
            Text(
              _searchQuery.isEmpty ? 'Menu Pilihan (Total: ${_allMenus.length}):' : 'Hasil Pencarian:',
              style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 8),
            // Widget 5
            Expanded(
              child: displayedMenus.isEmpty
                  ? const Center(child: Text('Menu tidak ditemukan.'))
                  : ListView.builder(
                      itemCount: displayedMenus.length + (_searchQuery.isEmpty && _limitMenu < _allMenus.length ? 1 : 0),
                      itemBuilder: (context, index) {
                        if (_searchQuery.isEmpty && index == displayedMenus.length) {
                          return Padding(
                            padding: const EdgeInsets.symmetric(vertical: 12.0),
                            child: ElevatedButton.icon(
                              style: ElevatedButton.styleFrom(
                                backgroundColor: Colors.deepOrange.shade100,
                                foregroundColor: Colors.deepOrange.shade900,
                              ),
                              onPressed: () {
                                setState(() {
                                  _limitMenu += 5;
                                });
                              },
                              icon: const Icon(Icons.expand_more),
                              label: const Text('Muat Menu Lainnya (Load More)'),
                            ),
                          );
                        }

                        final menu = displayedMenus[index];
                        return Card(
                          elevation: 3,
                          margin: const EdgeInsets.only(bottom: 12),
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Image.network(
                                menu['gambar'],
                                height: 140,
                                width: double.infinity,
                                fit: BoxFit.cover,
                              ),
                              Padding(
                                padding: const EdgeInsets.all(12.0),
                                child: Row(
                                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: [
                                    Expanded(
                                      child: Column(
                                        crossAxisAlignment: CrossAxisAlignment.start,
                                        children: [
                                          Text(
                                            menu['nama'],
                                            style: const TextStyle(
                                              fontSize: 16, 
                                              fontWeight: FontWeight.bold,
                                            ),
                                          ),
                                          const SizedBox(height: 4),
                                          Text(
                                            'Rp ${menu['harga']}',
                                            style: const TextStyle(
                                              color: Colors.deepOrange, 
                                              fontWeight: FontWeight.w600,
                                            ),
                                          ),
                                          const SizedBox(height: 4),
                                          Text(
                                            menu['deskripsi'],
                                            style: const TextStyle(
                                              color: Colors.grey, 
                                              fontSize: 13,
                                            ),
                                          ),
                                        ],
                                      ),
                                    ),
                                    const SizedBox(width: 8),
                                    ElevatedButton(
                                      onPressed: () {
                                        Navigator.pushNamed(
                                          context, 
                                          '/detail',
                                          arguments: menu,
                                        );
                                      },
                                      child: const Text('Pesan'),
                                    ),
                                  ],
                                ),
                              ),
                            ],
                          ),
                        );
                      },
                    ),
            ),
          ],
        ),
      ),
    );
  }
}