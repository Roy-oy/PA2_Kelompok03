import 'package:flutter/material.dart';
import 'package:mobile_puskesmas/services/pasien_service.dart';

class MedicalDetailScreen extends StatefulWidget {
  final int recordId;

  const MedicalDetailScreen({Key? key, required this.recordId}) : super(key: key);

  @override
  State<MedicalDetailScreen> createState() => _MedicalDetailScreenState();
}

class _MedicalDetailScreenState extends State<MedicalDetailScreen> {
  bool _isLoading = true;
  Map<String, dynamic>? _recordDetail;

  @override
  void initState() {
    super.initState();
    _loadRecordDetail();
  }

  Future<void> _loadRecordDetail() async {
    try {
      final detail = await PasienService().getMedicalRecordDetail(widget.recordId);
      
      setState(() {
        _recordDetail = detail;
        _isLoading = false;
      });
    } catch (e) {
      print('Error loading medical record detail: $e');
      setState(() {
        _isLoading = false;
      });
      
      // Tampilkan pesan error
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
            content: Text('Gagal memuat detail rekam medis'),
            backgroundColor: Colors.red,
          ),
        );
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        backgroundColor: const Color(0xFF06489F),
        title: const Text(
          'Detail Riwayat Berobat',
          style: TextStyle(
            fontFamily: 'KohSantepheap',
            fontSize: 18,
            color: Colors.white,
          ),
        ),
        leading: IconButton(
          icon: const Icon(Icons.arrow_back, color: Colors.white),
          onPressed: () => Navigator.pop(context),
        ),
      ),
      body: _isLoading
          ? const Center(
              child: CircularProgressIndicator(
                color: Color(0xFF06489F),
              ),
            )
          : _recordDetail == null
              ? Center(
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      Icon(
                        Icons.error_outline,
                        size: 60,
                        color: Colors.red[300],
                      ),
                      const SizedBox(height: 16),
                      const Text(
                        'Detail rekam medis tidak ditemukan',
                        style: TextStyle(
                          fontFamily: 'KohSantepheap',
                          fontSize: 16,
                        ),
                      ),
                      const SizedBox(height: 20),
                      ElevatedButton(
                        onPressed: () => Navigator.pop(context),
                        style: ElevatedButton.styleFrom(
                          backgroundColor: const Color(0xFF06489F),
                          foregroundColor: Colors.white,
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(8),
                          ),
                        ),
                        child: const Text('Kembali'),
                      ),
                    ],
                  ),
                )
              : SingleChildScrollView(
                  padding: const EdgeInsets.all(16),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      // Tanggal dan waktu
                      Container(
                        width: double.infinity,
                        padding: const EdgeInsets.all(16),
                        decoration: BoxDecoration(
                          color: const Color(0xFF06489F).withOpacity(0.1),
                          borderRadius: BorderRadius.circular(12),
                        ),
                        child: Column(
                          children: [
                            const Icon(
                              Icons.calendar_month,
                              size: 36,
                              color: Color(0xFF06489F),
                            ),
                            const SizedBox(height: 8),
                            Text(
                              '${_recordDetail!['tanggal'] ?? 'Tidak tersedia'}, ${_recordDetail!['waktu'] ?? 'Tidak tersedia'}',
                              style: const TextStyle(
                                fontFamily: 'KohSantepheap',
                                fontSize: 18,
                                fontWeight: FontWeight.bold,
                                color: Color(0xFF06489F),
                              ),
                              textAlign: TextAlign.center,
                            ),
                          ],
                        ),
                      ),
                      
                      const SizedBox(height: 24),
                      
                      // Informasi dokter
                      _buildInfoSection(
                        title: 'Dokter & Poli',
                        icon: Icons.person,
                        content: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              _recordDetail!['dokter'] ?? 'Tidak tersedia',
                              style: const TextStyle(
                                fontFamily: 'KohSantepheap',
                                fontSize: 16,
                                fontWeight: FontWeight.bold,
                              ),
                            ),
                            const SizedBox(height: 4),
                            Text(
                              _recordDetail!['cluster'] ?? 'Tidak tersedia',
                              style: TextStyle(
                                fontFamily: 'KohSantepheap',
                                fontSize: 14,
                                color: Colors.grey[700],
                              ),
                            ),
                          ],
                        ),
                      ),
                      
                      // Keluhan pasien
                      if (_recordDetail!['keluhan'] != null)
                        _buildInfoSection(
                          title: 'Keluhan',
                          icon: Icons.sick,
                          content: Text(
                            _recordDetail!['keluhan'],
                            style: const TextStyle(
                              fontFamily: 'KohSantepheap',
                              fontSize: 15,
                            ),
                          ),
                        ),
                      
                      // Diagnosa
                      _buildInfoSection(
                        title: 'Diagnosa',
                        icon: Icons.medical_information,
                        content: Text(
                          _recordDetail!['diagnosis'] ?? 'Tidak ada diagnosa',
                          style: const TextStyle(
                            fontFamily: 'KohSantepheap',
                            fontSize: 15,
                          ),
                        ),
                      ),
                      
                      // Terapi
                      _buildInfoSection(
                        title: 'Terapi / Pengobatan',
                        icon: Icons.medication,
                        content: Text(
                          _recordDetail!['terapi'] ?? 'Tidak ada terapi',
                          style: const TextStyle(
                            fontFamily: 'KohSantepheap',
                            fontSize: 15,
                          ),
                        ),
                      ),
                      
                      // Hasil Laboratorium (jika ada)
                      if (_recordDetail!['lab_result'] != null)
                        _buildInfoSection(
                          title: 'Hasil Laboratorium',
                          icon: Icons.science,
                          content: Text(
                            _recordDetail!['lab_result'],
                            style: const TextStyle(
                              fontFamily: 'KohSantepheap',
                              fontSize: 15,
                            ),
                          ),
                        ),
                        
                      // Saran (jika ada)
                      if (_recordDetail!['advice'] != null)
                        _buildInfoSection(
                          title: 'Saran Dokter',
                          icon: Icons.tips_and_updates,
                          content: Text(
                            _recordDetail!['advice'],
                            style: const TextStyle(
                              fontFamily: 'KohSantepheap',
                              fontSize: 15,
                            ),
                          ),
                        ),
                        
                      // Tanda vital (jika ada)
                      if (_recordDetail!['tekanan_darah'] != null || 
                          _recordDetail!['berat_badan'] != null || 
                          _recordDetail!['tinggi_badan'] != null)
                        _buildInfoSection(
                          title: 'Tanda Vital',
                          icon: Icons.favorite,
                          content: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              if (_recordDetail!['tekanan_darah'] != null)
                                Padding(
                                  padding: const EdgeInsets.only(bottom: 8.0),
                                  child: Row(
                                    children: [
                                      const Icon(Icons.monitor_heart, size: 16, color: Colors.grey),
                                      const SizedBox(width: 8),
                                      const Text('Tekanan Darah: '),
                                      Text(
                                        '${_recordDetail!['tekanan_darah']} mmHg',
                                        style: const TextStyle(fontWeight: FontWeight.bold),
                                      ),
                                    ],
                                  ),
                                ),
                              if (_recordDetail!['berat_badan'] != null)
                                Padding(
                                  padding: const EdgeInsets.only(bottom: 8.0),
                                  child: Row(
                                    children: [
                                      const Icon(Icons.line_weight, size: 16, color: Colors.grey),
                                      const SizedBox(width: 8),
                                      const Text('Berat Badan: '),
                                      Text(
                                        '${_recordDetail!['berat_badan']} kg',
                                        style: const TextStyle(fontWeight: FontWeight.bold),
                                      ),
                                    ],
                                  ),
                                ),
                              if (_recordDetail!['tinggi_badan'] != null)
                                Row(
                                  children: [
                                    const Icon(Icons.height, size: 16, color: Colors.grey),
                                    const SizedBox(width: 8),
                                    const Text('Tinggi Badan: '),
                                    Text(
                                      '${_recordDetail!['tinggi_badan']} cm',
                                      style: const TextStyle(fontWeight: FontWeight.bold),
                                    ),
                                  ],
                                ),
                            ],
                          ),
                        ),
                        
                      const SizedBox(height: 30),
                    ],
                  ),
                ),
    );
  }
  
  Widget _buildInfoSection({
    required String title,
    required IconData icon,
    required Widget content,
  }) {
    return Container(
      margin: const EdgeInsets.only(bottom: 16),
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(12),
        boxShadow: [
          BoxShadow(
            color: Colors.grey.withOpacity(0.1),
            spreadRadius: 0,
            blurRadius: 6,
            offset: const Offset(0, 2),
          ),
        ],
        border: Border.all(color: Colors.grey.withOpacity(0.1)),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              Icon(
                icon,
                color: const Color(0xFF06489F),
                size: 20,
              ),
              const SizedBox(width: 8),
              Text(
                title,
                style: const TextStyle(
                  fontFamily: 'KohSantepheap',
                  fontSize: 16,
                  fontWeight: FontWeight.bold,
                  color: Color(0xFF06489F),
                ),
              ),
            ],
          ),
          const Divider(height: 24),
          content,
        ],
      ),
    );
  }
}