import 'package:flutter/material.dart';
import 'package:mobile_puskesmas/services/pasien_service.dart';
import 'package:mobile_puskesmas/screens/medical_detail_screen.dart';

class MedicalScreen extends StatefulWidget {
  final String registrationType;
  final List<Map<String, dynamic>>? records;

  const MedicalScreen({
    Key? key,
    required this.registrationType,
    this.records,
  }) : super(key: key);

  @override
  State<MedicalScreen> createState() => _MedicalScreenState();
}

class _MedicalScreenState extends State<MedicalScreen> {
  bool _isLoading = true;
  List<Map<String, dynamic>> _medicalRecords = [];

  @override
  void initState() {
    super.initState();
    
    // Jika records sudah diberikan, gunakan itu
    if (widget.records != null && widget.records!.isNotEmpty) {
      setState(() {
        _medicalRecords = widget.records!;
        _isLoading = false;
      });
    } else {
      // Jika tidak, ambil dari API
      _loadMedicalRecords();
    }
  }

  Future<void> _loadMedicalRecords() async {
    try {
      final records = await PasienService().getMedicalRecords();
      
      setState(() {
        _medicalRecords = records;
        _isLoading = false;
      });
    } catch (e) {
      print('Error loading medical records: $e');
      setState(() {
        _isLoading = false;
      });
      
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
            content: Text('Gagal memuat riwayat rekam medis'),
            backgroundColor: Colors.red,
          ),
        );
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(
        backgroundColor: const Color(0xFF06489F),
        elevation: 0,
        title: const Text(
          'Riwayat Rekam Medis',
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
        actions: [
          IconButton(
            icon: const Icon(Icons.refresh, color: Colors.white),
            onPressed: () {
              setState(() {
                _isLoading = true;
              });
              _loadMedicalRecords();
            },
          ),
        ],
      ),
      body: _buildBody(),
    );
  }

  Widget _buildBody() {
    if (_isLoading) {
      return const Center(
        child: CircularProgressIndicator(
          color: Color(0xFF06489F),
        ),
      );
    }

    if (_medicalRecords.isEmpty) {
      return Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(
              Icons.history,
              size: 64,
              color: Colors.grey[400],
            ),
            const SizedBox(height: 16),
            const Text(
              'Belum ada riwayat rekam medis',
              style: TextStyle(
                fontFamily: 'KohSantepheap',
                fontSize: 16,
                color: Colors.grey,
              ),
            ),
            const SizedBox(height: 20),
            ElevatedButton(
              onPressed: () => Navigator.pop(context),
              style: ElevatedButton.styleFrom(
                backgroundColor: const Color(0xFF06489F),
                foregroundColor: Colors.white,
                padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 12),
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(8),
                ),
              ),
              child: const Text(
                'Kembali ke Beranda',
                style: TextStyle(
                  fontFamily: 'KohSantepheap',
                  fontSize: 14,
                ),
              ),
            ),
          ],
        ),
      );
    }

    return ListView.builder(
      padding: const EdgeInsets.all(16),
      itemCount: _medicalRecords.length,
      itemBuilder: (context, index) {
        final record = _medicalRecords[index];
        return _buildMedicalRecordItem(record);
      },
    );
  }

  Widget _buildMedicalRecordItem(Map<String, dynamic> record) {
    // Implementasi _buildMedicalRecordItem sesuai dengan yang ada di MedicalHistoryScreen
    String tanggal = record['tanggal'] ?? 'Tidak tersedia';
    String waktu = record['waktu'] ?? 'Tidak tersedia';
    String diagnosis = record['diagnosis'] ?? 'Tidak ada diagnosis';
    String dokter = record['dokter'] ?? 'Tidak tersedia';
    String cluster = record['cluster'] ?? 'Tidak tersedia';

    return GestureDetector(
      onTap: () {
        if (record['id'] != null) {
          Navigator.push(
            context,
            MaterialPageRoute(
              builder: (context) => MedicalDetailScreen(recordId: record['id']),
            ),
          ).then((_) => _loadMedicalRecords()); // Refresh data when returning
        }
      },
      child: Container(
        margin: const EdgeInsets.only(bottom: 16),
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
          children: [
            // Header (implementasi sama seperti di MedicalHistoryScreen)
            Container(
              padding: const EdgeInsets.all(16),
              decoration: const BoxDecoration(
                color: Color(0xFF06489F),
                borderRadius: BorderRadius.only(
                  topLeft: Radius.circular(12),
                  topRight: Radius.circular(12),
                ),
              ),
              child: Row(
                children: [
                  const Icon(
                    Icons.calendar_month,
                    color: Colors.white,
                    size: 20,
                  ),
                  const SizedBox(width: 8),
                  Expanded(
                    child: Text(
                      '$tanggal, $waktu',
                      style: const TextStyle(
                        fontFamily: 'KohSantepheap',
                        fontSize: 14,
                        fontWeight: FontWeight.bold,
                        color: Colors.white,
                      ),
                    ),
                  ),
                  Container(
                    padding: const EdgeInsets.symmetric(
                      horizontal: 10,
                      vertical: 4,
                    ),
                    decoration: BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.circular(20),
                    ),
                    child: Text(
                      cluster,
                      style: const TextStyle(
                        fontFamily: 'KohSantepheap',
                        fontSize: 12,
                        color: Color(0xFF06489F),
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                  ),
                ],
              ),
            ),
            
            // Content (implementasi sama seperti di MedicalHistoryScreen)
            Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  // Dokter
                  Row(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Icon(
                        Icons.person,
                        size: 20,
                        color: Colors.grey[700],
                      ),
                      const SizedBox(width: 8),
                      Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            const Text(
                              'Dokter',
                              style: TextStyle(
                                fontFamily: 'KohSantepheap',
                                fontSize: 12,
                                color: Colors.grey,
                              ),
                            ),
                            Text(
                              dokter,
                              style: const TextStyle(
                                fontFamily: 'KohSantepheap',
                                fontSize: 14,
                                fontWeight: FontWeight.bold,
                              ),
                            ),
                          ],
                        ),
                      ),
                    ],
                  ),
                  
                  const SizedBox(height: 12),
                  
                  // Diagnosis
                  Row(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Icon(
                        Icons.medical_information_outlined,
                        size: 20,
                        color: Colors.grey[700],
                      ),
                      const SizedBox(width: 8),
                      Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            const Text(
                              'Diagnosis',
                              style: TextStyle(
                                fontFamily: 'KohSantepheap',
                                fontSize: 12,
                                color: Colors.grey,
                              ),
                            ),
                            Text(
                              diagnosis,
                              style: const TextStyle(
                                fontFamily: 'KohSantepheap',
                                fontSize: 14,
                              ),
                            ),
                          ],
                        ),
                      ),
                    ],
                  ),
                ],
              ),
            ),
            
            // Footer (implementasi sama seperti di MedicalHistoryScreen)
            Container(
              padding: const EdgeInsets.symmetric(vertical: 10, horizontal: 16),
              decoration: BoxDecoration(
                color: Colors.grey[50],
                borderRadius: const BorderRadius.only(
                  bottomLeft: Radius.circular(12),
                  bottomRight: Radius.circular(12),
                ),
              ),
              child: Row(
                children: [
                  Expanded(
                    child: Text(
                      'Tap untuk melihat detail',
                      style: TextStyle(
                        fontFamily: 'KohSantepheap',
                        fontSize: 12,
                        color: Colors.grey[600],
                        fontStyle: FontStyle.italic,
                      ),
                    ),
                  ),
                  Icon(
                    Icons.arrow_forward_ios,
                    size: 14,
                    color: Colors.grey[600],
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}