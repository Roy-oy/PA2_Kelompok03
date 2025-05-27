import 'package:flutter/material.dart';
import 'package:iconsax/iconsax.dart';
import 'package:intl/intl.dart';

class PendaftaranSuccessScreen extends StatelessWidget {
  final Map<String, dynamic> pendaftaranData;

  const PendaftaranSuccessScreen({Key? key, required this.pendaftaranData}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      body: SafeArea(
        child: Padding(
          padding: const EdgeInsets.all(20),
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              // Header Icon and Title
              const Icon(
                Iconsax.tick_circle,
                size: 80,
                color: Color(0xFF06489F),
              ),
              const SizedBox(height: 20),
              const Text(
                'Pendaftaran Berhasil!',
                style: TextStyle(
                  fontSize: 24,
                  fontWeight: FontWeight.bold,
                  color: Color(0xFF06489F),
                  fontFamily: 'KohSantepheap',
                ),
              ),
              const SizedBox(height: 10),
              Text(
                'Pendaftaran Anda telah berhasil. Silakan simpan nomor antrian Anda.',
                style: TextStyle(
                  fontSize: 14,
                  color: Colors.grey[600],
                  fontFamily: 'KohSantepheap',
                ),
                textAlign: TextAlign.center,
              ),
              const SizedBox(height: 40),

              // Registration Details Card
              Container(
                padding: const EdgeInsets.all(20),
                decoration: BoxDecoration(
                  color: Colors.grey.shade50,
                  borderRadius: BorderRadius.circular(15),
                  border: Border.all(color: Colors.grey.shade300),
                ),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    _buildDetailRow('Nomor Antrian', pendaftaranData['no_antrian'] ?? '-', Iconsax.ticket),
                    const SizedBox(height: 15),
                    _buildDetailRow('Nama Pasien', pendaftaranData['pasien']['nama'] ?? '-', Iconsax.user),
                    const SizedBox(height: 15),
                    _buildDetailRow(
                      'Tanggal Daftar',
                      pendaftaranData['tanggal_daftar'] != null
                          ? DateFormat('dd/MM/yyyy').format(DateTime.parse(pendaftaranData['tanggal_daftar']))
                          : '-',
                      Iconsax.calendar,
                    ),
                    const SizedBox(height: 15),
                    _buildDetailRow('Cluster', pendaftaranData['cluster']['nama'] ?? '-', Iconsax.hospital),
                    const SizedBox(height: 15),
                    _buildDetailRow('Keluhan', pendaftaranData['keluhan'] ?? '-', Iconsax.health),
                  ],
                ),
              ),

              const SizedBox(height: 40),

              // Buttons
              SizedBox(
                width: double.infinity,
                child: ElevatedButton(
                  onPressed: () {
                    Navigator.popUntil(context, (route) => route.isFirst); // Kembali ke halaman utama
                  },
                  style: ElevatedButton.styleFrom(
                    backgroundColor: const Color(0xFF06489F),
                    foregroundColor: Colors.white,
                    padding: const EdgeInsets.symmetric(vertical: 16),
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                    elevation: 2,
                  ),
                  child: const Row(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      Icon(Iconsax.home, size: 20, color: Colors.white),
                      SizedBox(width: 10),
                      Text(
                        'Kembali ke Beranda',
                        style: TextStyle(
                          fontSize: 16,
                          fontWeight: FontWeight.bold,
                          fontFamily: 'KohSantepheap',
                          letterSpacing: 1,
                        ),
                      ),
                    ],
                  ),
                ),
              ),
              const SizedBox(height: 15),
              SizedBox(
                width: double.infinity,
                child: OutlinedButton(
                  onPressed: () {
                    Navigator.pop(context); // Kembali ke halaman sebelumnya (bisa ke daftar pendaftaran)
                  },
                  style: OutlinedButton.styleFrom(
                    foregroundColor: const Color(0xFF06489F),
                    side: const BorderSide(color: Color(0xFF06489F)),
                    padding: const EdgeInsets.symmetric(vertical: 16),
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                  ),
                  child: const Row(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      Icon(Iconsax.document, size: 20, color: Color(0xFF06489F)),
                      SizedBox(width: 10),
                      Text(
                        'Lihat Pendaftaran',
                        style: TextStyle(
                          fontSize: 16,
                          fontWeight: FontWeight.bold,
                          color: Color(0xFF06489F),
                          fontFamily: 'KohSantepheap',
                          letterSpacing: 1,
                        ),
                      ),
                    ],
                  ),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildDetailRow(String label, String value, IconData icon) {
    return Row(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Icon(icon, color: const Color(0xFF06489F), size: 20),
        const SizedBox(width: 15),
        Expanded(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                label,
                style: TextStyle(fontSize: 14, color: Colors.grey[600], fontFamily: 'KohSantepheap'),
              ),
              const SizedBox(height: 3),
              Text(
                value,
                style: const TextStyle(
                  fontSize: 16,
                  fontWeight: FontWeight.w500,
                  color: Colors.black87,
                  fontFamily: 'KohSantepheap',
                ),
              ),
            ],
          ),
        ),
      ],
    );
  }
}