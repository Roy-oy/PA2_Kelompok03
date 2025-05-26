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
  final Map<int, bool> _imageLoadFailed = {};

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
          backgroundColor: Colors.redAccent,
          duration: const Duration(seconds: 5),
        ),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.grey[100],
      appBar: AppBar(
        elevation: 4,
        flexibleSpace: Container(
          decoration: const BoxDecoration(
            gradient: LinearGradient(
              colors: [Color(0xFF0D47A1), Color(0xFF1976D2)],
              begin: Alignment.topLeft,
              end: Alignment.bottomRight,
            ),
          ),
        ),
        leading: IconButton(
          icon: const Icon(Icons.arrow_back, color: Colors.white),
          onPressed: () => Navigator.pop(context),
        ),
        title: const Text(
          'Jadwal Dokter',
          style: TextStyle(
            color: Colors.white,
            fontWeight: FontWeight.bold,
            fontSize: 22,
          ),
        ),
      ),
      body: _isLoading
          ? const Center(
              child: CircularProgressIndicator(
                color: Color(0xFF1976D2),
                strokeWidth: 3,
              ),
            )
          : _jadwalList.isEmpty
              ? const Center(
                  child: Text(
                    'Tidak ada jadwal ditemukan.',
                    style: TextStyle(
                      fontSize: 18,
                      color: Colors.grey,
                      fontStyle: FontStyle.italic,
                    ),
                  ),
                )
              : ListView.builder(
                  padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 20),
                  itemCount: _jadwalList.length,
                  itemBuilder: (context, index) {
                    final jadwal = _jadwalList[index];
                    return GestureDetector(
                      onTap: () {
                        // Optional: Add navigation or action on tap
                      },
                      child: AnimatedContainer(
                        duration: const Duration(milliseconds: 200),
                        margin: const EdgeInsets.only(bottom: 16),
                        child: Card(
                          elevation: 6,
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(20),
                          ),
                          child: Container(
                            decoration: BoxDecoration(
                              borderRadius: BorderRadius.circular(20),
                              gradient: const LinearGradient(
                                colors: [Colors.white, Color(0xFFE3F2FD)],
                                begin: Alignment.topLeft,
                                end: Alignment.bottomRight,
                              ),
                            ),
                            child: Padding(
                              padding: const EdgeInsets.all(20),
                              child: Row(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  Container(
                                    decoration: BoxDecoration(
                                      shape: BoxShape.circle,
                                      border: Border.all(
                                        color: Colors.blue[700]!,
                                        width: 3,
                                      ),
                                      boxShadow: [
                                        BoxShadow(
                                          color: Colors.black.withOpacity(0.1),
                                          blurRadius: 8,
                                          offset: const Offset(0, 4),
                                        ),
                                      ],
                                    ),
                                    child: CircleAvatar(
                                      radius: 35,
                                      backgroundColor: Colors.white,
                                      backgroundImage: _imageLoadFailed[jadwal.id] != true
                                          ? NetworkImage(jadwal.fotoProfil)
                                          : null,
                                      onBackgroundImageError: _imageLoadFailed[jadwal.id] != true
                                          ? (error, stackTrace) {
                                              print('Error loading image for ${jadwal.namaDokter}: $error');
                                              setState(() {
                                                _imageLoadFailed[jadwal.id] = true;
                                              });
                                            }
                                          : null,
                                      child: _imageLoadFailed[jadwal.id] == true ||
                                              jadwal.fotoProfil == 'https://via.placeholder.com/150'
                                          ? const Icon(
                                              Icons.person,
                                              size: 35,
                                              color: Colors.grey,
                                            )
                                          : null,
                                    ),
                                  ),
                                  const SizedBox(width: 16),
                                  Expanded(
                                    child: Column(
                                      crossAxisAlignment: CrossAxisAlignment.start,
                                      children: [
                                        Text(
                                          jadwal.namaDokter,
                                          style: const TextStyle(
                                            fontSize: 18,
                                            fontWeight: FontWeight.bold,
                                            color: Colors.black87,
                                          ),
                                        ),
                                        const SizedBox(height: 6),
                                        Row(
                                          children: [
                                            Icon(
                                              Icons.medical_services,
                                              size: 18,
                                              color: Colors.blue[700],
                                            ),
                                            const SizedBox(width: 6),
                                            Expanded(
                                              child: Text(
                                                jadwal.spesialis,
                                                style: const TextStyle(
                                                  fontSize: 14,
                                                  color: Colors.black54,
                                                ),
                                                overflow: TextOverflow.ellipsis,
                                              ),
                                            ),
                                          ],
                                        ),
                                        const SizedBox(height: 6),
                                        Row(
                                          children: [
                                            Icon(
                                              Icons.email,
                                              size: 18,
                                              color: Colors.blue[700],
                                            ),
                                            const SizedBox(width: 6),
                                            Expanded(
                                              child: Text(
                                                jadwal.email,
                                                style: const TextStyle(
                                                  fontSize: 14,
                                                  color: Colors.black54,
                                                ),
                                                overflow: TextOverflow.ellipsis,
                                              ),
                                            ),
                                          ],
                                        ),
                                        const SizedBox(height: 6),
                                        Row(
                                          children: [
                                            Icon(
                                              Icons.calendar_today,
                                              size: 18,
                                              color: Colors.blue[700],
                                            ),
                                            const SizedBox(width: 6),
                                            Text(
                                              jadwal.scheduleDay,
                                              style: const TextStyle(
                                                fontSize: 14,
                                                color: Colors.black54,
                                              ),
                                            ),
                                          ],
                                        ),
                                        const SizedBox(height: 6),
                                        Row(
                                          children: [
                                            Icon(
                                              Icons.access_time,
                                              size: 18,
                                              color: Colors.blue[700],
                                            ),
                                            const SizedBox(width: 6),
                                            Text(
                                              '${jadwal.jamMulai} - ${jadwal.jamSelesai}',
                                              style: const TextStyle(
                                                fontSize: 14,
                                                color: Colors.black54,
                                              ),
                                            ),
                                          ],
                                        ),
                                        const SizedBox(height: 6),
                                        Row(
                                          crossAxisAlignment: CrossAxisAlignment.start,
                                          children: [
                                            Icon(
                                              Icons.meeting_room,
                                              size: 18,
                                              color: Colors.blue[700],
                                            ),
                                            const SizedBox(width: 6),
                                            Expanded(
                                              child: Text(
                                                jadwal.ruangan,
                                                style: const TextStyle(
                                                  fontSize: 14,
                                                  color: Colors.black54,
                                                ),
                                                softWrap: true,
                                                maxLines: 2,
                                              ),
                                            ),
                                          ],
                                        ),
                                      ],
                                    ),
                                  ),
                                ],
                              ),
                            ),
                          ),
                        ),
                      ),
                    );
                  },
                ),
    );
  }
}