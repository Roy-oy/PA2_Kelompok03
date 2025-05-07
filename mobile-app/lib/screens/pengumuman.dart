import 'package:flutter/material.dart';
import '../models/pengumuman_model.dart';
import '../services/pengumuman_service.dart';

class Pengumuman extends StatefulWidget {
  const Pengumuman({Key? key}) : super(key: key);

  @override
  State<Pengumuman> createState() => _PengumumanState();
}

class _PengumumanState extends State<Pengumuman> {
  final PengumumanService _service = PengumumanService();
  List<PengumumanModel> _announcements = [];
  bool _isLoading = true;
  int _selectedIndex = -1;

  @override
  void initState() {
    super.initState();
    _loadPengumuman();
  }

  Future<void> _loadPengumuman() async {
    setState(() => _isLoading = true);
    try {
      final announcements = await _service.getPengumuman();
      setState(() {
        _announcements = announcements;
        _isLoading = false;
      });
    } catch (e) {
      setState(() => _isLoading = false);
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text('Error: ${e.toString()}'),
          backgroundColor: Colors.red,
          duration: const Duration(seconds: 5),
        ),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(
        backgroundColor: const Color(0xFF0D47A1),
        title: const Text(
          'Pengumuman',
          style: TextStyle(color: Colors.white),
        ),
        leading: _selectedIndex != -1
            ? IconButton(
                icon: const Icon(Icons.arrow_back, color: Colors.white),
                onPressed: () => setState(() => _selectedIndex = -1),
              )
            : null,
      ),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : _announcements.isEmpty
              ? const Center(child: Text('Tidak ada pengumuman.'))
              : _selectedIndex == -1
                  ? _buildListView()
                  : _buildDetailView(),
    );
  }

  Widget _buildListView() {
    return RefreshIndicator(
      onRefresh: _loadPengumuman,
      child: ListView.builder(
        padding: const EdgeInsets.all(16),
        itemCount: _announcements.length,
        itemBuilder: (context, index) {
          final item = _announcements[index];
          return Card(
            margin: const EdgeInsets.only(bottom: 12),
            elevation: 0,
            shape: RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(12),
              side: BorderSide(color: Colors.grey.shade200),
            ),
            child: ListTile(
              contentPadding: const EdgeInsets.all(16),
              title: Text(
                item.judul,
                style: const TextStyle(
                  fontSize: 16,
                  fontWeight: FontWeight.bold,
                ),
                maxLines: 2,
                overflow: TextOverflow.ellipsis,
              ),
              subtitle: Padding(
                padding: const EdgeInsets.only(top: 8),
                child: Row(
                  children: [
                    Icon(Icons.calendar_today, 
                      size: 16, 
                      color: Colors.blue[800]
                    ),
                    const SizedBox(width: 4),
                    Text(
                      item.tanggal,
                      style: TextStyle(color: Colors.grey[600]),
                    ),
                  ],
                ),
              ),
              onTap: () => setState(() => _selectedIndex = index),
            ),
          );
        },
      ),
    );
  }

  Widget _buildDetailView() {
    final item = _announcements[_selectedIndex];
    return SingleChildScrollView(
      padding: const EdgeInsets.all(16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            item.judul,
            style: const TextStyle(
              fontSize: 20,
              fontWeight: FontWeight.bold,
            ),
          ),
          const SizedBox(height: 12),
          Row(
            children: [
              Icon(Icons.calendar_today,
                size: 16,
                color: Colors.blue[800]
              ),
              const SizedBox(width: 4),
              Text(
                item.tanggal,
                style: TextStyle(color: Colors.grey[600]),
              ),
            ],
          ),
          const SizedBox(height: 16),
          Text(
            item.isi,
            style: const TextStyle(
              fontSize: 16,
              height: 1.5,
            ),
          ),
        ],
      ),
    );
  }
}
