import 'package:flutter/material.dart';
import 'package:iconsax/iconsax.dart';
import 'package:mobile_puskesmas/config/api_config.dart';
import 'package:mobile_puskesmas/models/medical_record_model.dart';
import 'package:mobile_puskesmas/services/auth_service.dart';
import 'package:mobile_puskesmas/services/medical_record_service.dart';
import 'package:permission_handler/permission_handler.dart';
import 'package:path_provider/path_provider.dart';
import 'dart:io';
import 'package:http/http.dart' as http;

class MedicalRecordDetailScreen extends StatefulWidget {
  final MedicalRecordModel record;

  const MedicalRecordDetailScreen({Key? key, required this.record}) : super(key: key);

  @override
  _MedicalRecordDetailScreenState createState() => _MedicalRecordDetailScreenState();
}

class _MedicalRecordDetailScreenState extends State<MedicalRecordDetailScreen> {
  bool _isDownloading = false;

  Future<void> _downloadPdf() async {
    if (widget.record.id == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('ID rekam medis tidak tersedia')),
      );
      return;
    }

    setState(() => _isDownloading = true);

    try {
      final status = await Permission.storage.request();
      if (status.isGranted) {
        final directory = await getExternalStorageDirectory();
        if (directory == null) {
          throw Exception('Direktori penyimpanan tidak ditemukan');
        }

        final fileName =
            'rekam-medis-${widget.record.pasien?.noRm}-${DateTime.now().toIso8601String().split('T')[0]}.pdf';
        final filePath = '${directory.path}/$fileName';

        final response = await http.get(
          Uri.parse(
              '${ApiConfig.baseUrl}/medical-records/${widget.record.id}/pdf'),
          headers: {
            'Authorization': 'Bearer ${await AuthService().getToken()}',
          },
        );

        if (response.statusCode == 200) {
          final file = File(filePath);
          await file.writeAsBytes(response.bodyBytes);
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(content: Text('PDF tersimpan di: $filePath')),
          );
        } else {
          throw Exception('Gagal mengunduh PDF: Status ${response.statusCode}');
        }
      } else {
        throw Exception('Izin penyimpanan ditolak');
      }
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Gagal mengunduh PDF: $e')),
      );
    } finally {
      setState(() => _isDownloading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(
        title: const Text(
          'Detail Rekam Medis',
          style: TextStyle(
            fontFamily: 'KohSantepheap',
            color: Color(0xFF06489F),
            fontWeight: FontWeight.bold,
          ),
        ),
        backgroundColor: Colors.white,
        iconTheme: const IconThemeData(color: Color(0xFF06489F)),
        elevation: 1,
        actions: [
          if (widget.record.id != null)
            IconButton(
              icon: _isDownloading
                  ? const SizedBox(
                      width: 20,
                      height: 20,
                      child: CircularProgressIndicator(
                        color: Color(0xFF06489F),
                        strokeWidth: 2,
                      ),
                    )
                  : const Icon(Icons.download, color: Color(0xFF06489F)),
              onPressed: _isDownloading ? null : _downloadPdf,
            ),
        ],
      ),
      body: SafeArea(
        child: Padding(
          padding: const EdgeInsets.all(20),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Header Section (tanpa Container)
              Text(
                'Tanggal Kunjungan: ${widget.record.getFormattedTanggalKunjungan()}',
                style: const TextStyle(
                  fontSize: 18,
                  fontWeight: FontWeight.bold,
                  color: Color(0xFF06489F),
                  fontFamily: 'KohSantepheap',
                ),
              ),
              const SizedBox(height: 5),
              Text(
                'Pasien: ${widget.record.getFormattedPasienNama()}',
                style: const TextStyle(
                  fontSize: 16,
                  color: Colors.black87,
                  fontFamily: 'KohSantepheap',
                ),
              ),
              const SizedBox(height: 20),
              // Detail Items
              _buildDetailItem(
                icon: Iconsax.calendar_1,
                title: 'Tanggal Pendaftaran',
                value: widget.record.pendaftaran?.getFormattedTanggalDaftar() ?? '-',
              ),
              _buildDetailItem(
                icon: Iconsax.message,
                title: 'Keluhan',
                value: widget.record.keluhan ?? '-',
              ),
              _buildDetailItem(
                icon: Iconsax.health,
                title: 'Diagnosis',
                value: widget.record.diagnosis ?? '-',
              ),
              _buildDetailItem(
                icon: Iconsax.activity,
                title: 'Pengobatan',
                value: widget.record.pengobatan ?? '-',
              ),
              _buildDetailItem(
                icon: Iconsax.document_text,
                title: 'Hasil Pemeriksaan',
                value: widget.record.hasilPemeriksaan ?? '-',
              ),
              _buildDetailItem(
                icon: Iconsax.ruler,
                title: 'Tinggi Badan',
                value: widget.record.tinggiBadan != null
                    ? '${widget.record.tinggiBadan!.toStringAsFixed(1)} cm'
                    : '-',
              ),
              _buildDetailItem(
                icon: Iconsax.weight,
                title: 'Berat Badan',
                value: widget.record.beratBadan != null
                    ? '${widget.record.beratBadan!.toStringAsFixed(1)} kg'
                    : '-',
              ),
              _buildDetailItem(
                icon: Iconsax.monitor,
                title: 'Tekanan Darah',
                value: widget.record.tekananDarah ?? '-',
              ),
              _buildDetailItem(
                icon: Icons.device_thermostat,
                title: 'Suhu Badan',
                value: widget.record.suhuBadan != null
                    ? '${widget.record.suhuBadan!.toStringAsFixed(1)} °C'
                    : '-',
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildDetailItem({required IconData icon, required String title, required String value}) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 15),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Icon(icon, color: const Color(0xFF06489F), size: 20),
          const SizedBox(width: 10),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  title,
                  style: const TextStyle(
                    fontSize: 14,
                    fontWeight: FontWeight.bold,
                    color: Colors.black87,
                    fontFamily: 'KohSantepheap',
                  ),
                ),
                const SizedBox(height: 5),
                Text(
                  value,
                  style: const TextStyle(
                    fontSize: 14,
                    color: Colors.black54,
                    fontFamily: 'KohSantepheap',
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}