import 'package:flutter/material.dart';
import 'package:iconsax/iconsax.dart';
import 'package:mobile_puskesmas/models/medical_record_model.dart';
import 'package:mobile_puskesmas/services/medical_record_service.dart';
import 'package:intl/intl.dart';
import 'medical_record_detail_screen.dart'; // ⬅️ Buat file ini untuk detail

class MedicalRecordScreen extends StatefulWidget {
  const MedicalRecordScreen({Key? key, required List records}) : super(key: key);

  @override
  _MedicalRecordScreenState createState() => _MedicalRecordScreenState();
}

class _MedicalRecordScreenState extends State<MedicalRecordScreen> {
  final TextEditingController _nikController = TextEditingController();
  final MedicalRecordService _medicalRecordService = MedicalRecordService();
  List<MedicalRecordModel> _medicalRecords = [];
  bool _isLoading = false;
  String _errorMessage = '';

  Future<void> _searchMedicalRecords() async {
    if (_nikController.text.isEmpty) {
      setState(() {
        _errorMessage = 'NIK wajib diisi.';
      });
      return;
    }

    setState(() {
      _isLoading = true;
      _errorMessage = '';
    });

    try {
      final records = await _medicalRecordService.getMedicalRecords(search: _nikController.text, nik: '');
      setState(() {
        _medicalRecords = records;
      });
    } catch (e) {
      _errorMessage = e.toString();
    } finally {
      _isLoading = false;
      setState(() {});
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.grey[100],
      appBar: AppBar(
        title: const Text('Rekam Medis',
          style: TextStyle(
            fontWeight: FontWeight.bold,
            fontFamily: 'KohSantepheap',
            color: Color(0xFF06489F),
          )),
        backgroundColor: Colors.white,
        elevation: 1,
        iconTheme: const IconThemeData(color: Color(0xFF06489F)),
      ),
      body: SafeArea(
        child: Padding(
          padding: const EdgeInsets.all(16),
          child: Column(
            children: [
              TextField(
                controller: _nikController,
                decoration: InputDecoration(
                  labelText: 'Masukkan NIK Pasien',
                  prefixIcon: const Icon(Iconsax.user, color: Color(0xFF06489F)),
                  border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
                ),
                keyboardType: TextInputType.number,
              ),
              const SizedBox(height: 16),
              SizedBox(
                width: double.infinity,
                child: ElevatedButton.icon(
                  icon: const Icon(Iconsax.search_normal),
                  label: const Text(
                    'Cari Rekam Medis',
                    style: TextStyle(
                      fontWeight: FontWeight.bold,
                      fontFamily: 'KohSantepheap',
                    ),
                  ),
                  onPressed: _isLoading ? null : _searchMedicalRecords,
                  style: ElevatedButton.styleFrom(
                    backgroundColor: const Color(0xFF06489F),
                    foregroundColor: Colors.white,
                    padding: const EdgeInsets.symmetric(vertical: 14),
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                  ),
                ),
              ),
              const SizedBox(height: 16),
              if (_errorMessage.isNotEmpty)
                Text(_errorMessage, style: const TextStyle(color: Colors.red)),
              const SizedBox(height: 10),
              Expanded(
                child: _isLoading
                    ? const Center(child: CircularProgressIndicator(color: Color(0xFF06489F)))
                    : _medicalRecords.isEmpty
                        ? const Center(
                            child: Text(
                              'Belum ada data rekam medis.',
                              style: TextStyle(fontFamily: 'KohSantepheap'),
                            ),
                          )
                        : ListView.builder(
                            itemCount: _medicalRecords.length,
                            itemBuilder: (context, index) {
                              final record = _medicalRecords[index];
                              return GestureDetector(
                                onTap: () {
                                  Navigator.push(
                                    context,
                                    MaterialPageRoute(
                                      builder: (_) => MedicalRecordDetailScreen(record: record),
                                    ),
                                  );
                                },
                                child: Card(
                                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                                  elevation: 3,
                                  margin: const EdgeInsets.symmetric(vertical: 8),
                                  child: Padding(
                                    padding: const EdgeInsets.all(16),
                                    child: Row(
                                      crossAxisAlignment: CrossAxisAlignment.start,
                                      children: [
                                        const Icon(Iconsax.document, color: Color(0xFF06489F), size: 30),
                                        const SizedBox(width: 12),
                                        Expanded(
                                          child: Column(
                                            crossAxisAlignment: CrossAxisAlignment.start,
                                            children: [
                                              Text(
                                                'Kunjungan: ${record.getFormattedTanggalKunjungan()}',
                                                style: const TextStyle(
                                                  fontWeight: FontWeight.w600,
                                                  fontFamily: 'KohSantepheap',
                                                ),
                                              ),
                                              const SizedBox(height: 4),
                                              Text(
                                                'Pasien: ${record.getFormattedPasienNama()}',
                                                style: TextStyle(
                                                  color: Colors.grey[700],
                                                  fontFamily: 'KohSantepheap',
                                                ),
                                              ),
                                              Text(
                                                'Diagnosis: ${record.diagnosis ?? '-'}',
                                                style: const TextStyle(fontFamily: 'KohSantepheap'),
                                              ),
                                            ],
                                          ),
                                        ),
                                        const Icon(Iconsax.arrow_right_3, color: Color(0xFF06489F)),
                                      ],
                                    ),
                                  ),
                                ),
                              );
                            },
                          ),
              )
            ],
          ),
        ),
      ),
    );
  }
}