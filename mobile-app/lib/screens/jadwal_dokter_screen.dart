import 'package:flutter/material.dart';
import '../models/jadwal_dokter_model.dart';
import '../services/jadwal_dokter_service.dart';

class JadwalDokterScreen extends StatefulWidget {
  const JadwalDokterScreen({Key? key}) : super(key: key);

  @override
  State<JadwalDokterScreen> createState() => _JadwalDokterScreenState();
}

class _JadwalDokterScreenState extends State<JadwalDokterScreen> {
  final JadwalDokterService _service = JadwalDokterService();
  List<JadwalDokterModel> _jadwalList = [];
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _loadJadwalDokter();
  }

  Future<void> _loadJadwalDokter() async {
    setState(() => _isLoading = true);
    try {
      final jadwalList = await _service.getJadwalDokter();
      setState(() {
        _jadwalList = jadwalList;
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
        leading: IconButton(
          icon: const Icon(Icons.arrow_back, color: Colors.white),
          onPressed: () => Navigator.pop(context),
        ),
        title: const Text(
          'Jadwal Dokter',
          style: TextStyle(color: Colors.white),
        ),
      ),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : _jadwalList.isEmpty
              ? const Center(child: Text('Tidak ada jadwal ditemukan.'))
              : ListView.builder(
                  itemCount: _jadwalList.length,
                  itemBuilder: (context, index) {
                    final jadwal = _jadwalList[index];
                    return Card(
                      margin: const EdgeInsets.all(16),
                      elevation: 0,
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(12),
                        side: BorderSide(color: Colors.grey.shade200),
                      ),
                      child: Padding(
                        padding: const EdgeInsets.all(16),
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Row(
                              children: [
                                CircleAvatar(
                                  radius: 30,
                                  backgroundImage: jadwal.fotoProfil != '-'
                                      ? NetworkImage(jadwal.fotoProfil)
                                      : null,
                                  child: jadwal.fotoProfil == '-'
                                      ? const Icon(Icons.person)
                                      : null,
                                ),
                                const SizedBox(width: 12),
                                Expanded(
                                  child: Column(
                                    crossAxisAlignment: CrossAxisAlignment.start,
                                    children: [
                                      Text(
                                        jadwal.namaDokter,
                                        style: const TextStyle(
                                          fontSize: 16,
                                          fontWeight: FontWeight.bold,
                                        ),
                                      ),
                                      const SizedBox(height: 4),
                                      Row(
                                        children: [
                                          Icon(Icons.medical_services_outlined, 
                                            size: 16, 
                                            color: Colors.blue[800]
                                          ),
                                          const SizedBox(width: 4),
                                          Text(
                                            jadwal.spesialis,
                                            style: TextStyle(
                                              color: Colors.grey[600],
                                            ),
                                          ),
                                        ],
                                      ),
                                      const SizedBox(height: 4),
                                      Row(
                                        children: [
                                          Icon(Icons.email_outlined,
                                            size: 16,
                                            color: Colors.blue[800]
                                          ),
                                          const SizedBox(width: 4),
                                          Text(
                                            jadwal.email,
                                            style: TextStyle(
                                              color: Colors.grey[600],
                                            ),
                                          ),
                                        ],
                                      ),
                                    ],
                                  ),
                                ),
                              ],
                            ),
                            const SizedBox(height: 16),
                            Row(
                              children: [
                                Icon(Icons.calendar_today_outlined,
                                  size: 16,
                                  color: Colors.blue[800]
                                ),
                                const SizedBox(width: 4),
                                Text(
                                  jadwal.hari,
                                  style: TextStyle(
                                    color: Colors.grey[800],
                                  ),
                                ),
                              ],
                            ),
                            const SizedBox(height: 8),
                            Wrap(
                              spacing: 8,
                              children: [
                                _buildTimeChip(jadwal.jamMulai),
                                if (jadwal.jamSelesai != jadwal.jamMulai)
                                  _buildTimeChip(jadwal.jamSelesai),
                              ],
                            ),
                          ],
                        ),
                      ),
                    );
                  },
                ),
    );
  }

  Widget _buildTimeChip(String time) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 4),
      decoration: BoxDecoration(
        border: Border.all(color: Colors.blue),
        borderRadius: BorderRadius.circular(4),
      ),
      child: Text(
        time,
        style: const TextStyle(
          color: Colors.blue,
          fontSize: 12,
        ),
      ),
    );
  }
}
