import 'package:flutter/material.dart';
import 'package:iconsax/iconsax.dart';
import 'package:mobile_puskesmas/models/feedback_model.dart';
import 'package:mobile_puskesmas/models/medical_record_model.dart';
import 'package:mobile_puskesmas/services/feedback_service.dart';
import 'package:mobile_puskesmas/services/medical_record_service.dart';
import 'package:mobile_puskesmas/services/auth_service.dart';

class Feedback_1 extends StatefulWidget {
  const Feedback_1({Key? key}) : super(key: key);

  @override
  State<Feedback_1> createState() => _Feedback_1State();
}

class _Feedback_1State extends State<Feedback_1> {
  int _pageIndex = 0;
  int _rating = 3;
  final TextEditingController _feedbackController = TextEditingController();
  int _selectedRecordId = 0;

  // Services
  final FeedbackService _feedbackService = FeedbackService();
  final MedicalRecordService _medicalRecordService = MedicalRecordService();
  final AuthService _authService = AuthService();

  // Data
  List<MedicalRecordModel> _medicalRecords = [];
  bool _isLoading = true;
  bool _isSubmitting = false;
  String _errorMessage = '';

  @override
  void initState() {
    super.initState();
    _loadData();
  }

  Future<void> _loadData() async {
    setState(() {
      _isLoading = true;
      _errorMessage = '';
    });

    try {
      // Periksa apakah user sudah login
      final isLoggedIn = await _authService.isLoggedIn();
      if (!isLoggedIn) {
        setState(() {
          _errorMessage = 'Anda belum login. Silakan login terlebih dahulu untuk mengakses halaman ini.';
          _isLoading = false;
        });
        return;
      }

      // Ambil data rekam medis
      final records = await _medicalRecordService.getMedicalRecords(nik: '');
      setState(() {
        _medicalRecords = records;
        if (records.isNotEmpty) {
          _selectedRecordId = records[0].id ?? 0;
        }
      });
    } catch (e) {
      setState(() {
        _errorMessage = e.toString();
      });
    } finally {
      setState(() {
        _isLoading = false;
      });
    }
  }

  void _goToFeedbackForm(MedicalRecordModel record) {
    setState(() {
      _selectedRecordId = record.id ?? 0;
      _pageIndex = 1;
    });
  }

  Future<void> _submitFeedback() async {
    if (_feedbackController.text.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Feedback tidak boleh kosong')),
      );
      return;
    }

    setState(() {
      _isSubmitting = true;
      _errorMessage = '';
    });

    try {
      // Ambil data user yang login
      final user = await _authService.getUserData();
      if (user == null) {
        throw Exception('Anda belum login. Silakan login terlebih dahulu.');
      }

      // Kirim feedback
      await _feedbackService.createFeedback(
        rating: _rating,
        comment: _feedbackController.text,
        id_medical_record: _selectedRecordId,
        pasien_id: user.id!,
      );

      // Sukses
      setState(() {
        _pageIndex = 2; // Pindah ke halaman sukses
      });
      
      // Reset form
      _feedbackController.clear();
      _rating = 3;
    } catch (e) {
      setState(() {
        _errorMessage = e.toString();
      });
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text(_errorMessage)),
      );
    } finally {
      setState(() {
        _isSubmitting = false;
      });
    }
  }

  Widget _buildStar(int index) {
    return IconButton(
      icon: Icon(
        index <= _rating ? Icons.star : Icons.star_border,
        color: Colors.amber,
        size: 32,
      ),
      onPressed: () {
        setState(() {
          _rating = index;
        });
      },
    );
  }

  Widget _buildFeedbackList() {
    if (_isLoading) {
      return const Center(child: CircularProgressIndicator(color: Color(0xFF06489F)));
    }

    if (_errorMessage.isNotEmpty) {
      return Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(Icons.error_outline, size: 64, color: Colors.red[300]),
            const SizedBox(height: 16),
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 32),
              child: Text(
                _errorMessage,
                textAlign: TextAlign.center,
                style: TextStyle(
                  fontSize: 16,
                  color: Colors.red[600],
                ),
              ),
            ),
            const SizedBox(height: 24),
            ElevatedButton.icon(
              icon: const Icon(Icons.refresh),
              label: const Text('Coba Lagi'),
              style: ElevatedButton.styleFrom(
                backgroundColor: const Color(0xFF06489F),
                foregroundColor: Colors.white,
                padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 12),
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
              ),
              onPressed: _loadData,
            ),
          ],
        ),
      );
    }

    if (_medicalRecords.isEmpty) {
      return Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(Iconsax.document_text, size: 64, color: Colors.grey[400]),
            const SizedBox(height: 16),
            Text(
              'Belum ada rekam medis',
              style: TextStyle(
                fontSize: 16,
                fontWeight: FontWeight.w600,
                color: Colors.grey[600],
              ),
            ),
            const SizedBox(height: 8),
            Text(
              'Anda harus memiliki rekam medis terlebih dahulu\nuntuk memberikan feedback',
              textAlign: TextAlign.center,
              style: TextStyle(
                fontSize: 14,
                color: Colors.grey[500],
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
        return Card(
          margin: const EdgeInsets.only(bottom: 12),
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(12),
          ),
          elevation: 2,
          child: Padding(
            padding: const EdgeInsets.all(16),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  record.getFormattedTanggalKunjungan(),
                  style: TextStyle(
                    fontSize: 14,
                    fontWeight: FontWeight.bold,
                    color: Colors.grey[700],
                  ),
                ),
                const SizedBox(height: 8),
                Row(
                  children: [
                    const Icon(Icons.medical_services, color: Color(0xFF06489F)),
                    const SizedBox(width: 8),
                    Expanded(
                      child: Text(
                        'Pelayanan Dokter',
                        style: const TextStyle(
                          fontSize: 16,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 8),
                Row(
                  children: [
                    const Icon(Icons.assignment, color: Color(0xFF06489F)),
                    const SizedBox(width: 8),
                    Expanded(
                      child: Text(
                        'Diagnosis: ${record.diagnosis ?? 'Tidak ada diagnosis'}',
                        style: const TextStyle(fontSize: 14),
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 12),
                Row(
                  mainAxisAlignment: MainAxisAlignment.end,
                  children: [
                    ElevatedButton(
                      onPressed: () => _goToFeedbackForm(record),
                      style: ElevatedButton.styleFrom(
                        backgroundColor: const Color(0xFF06489F),
                        foregroundColor: Colors.white,
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(8),
                        ),
                      ),
                      child: const Text('Isi Feedback'),
                    ),
                  ],
                ),
              ],
            ),
          ),
        );
      },
    );
  }

  Widget _buildFeedbackForm() {
    return Padding(
      padding: const EdgeInsets.all(16),
      child: Column(
        children: [
          const Text(
            'Bagaimana Pengalaman Anda?',
            style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold, color: Color(0xFF06489F)),
          ),
          const SizedBox(height: 16),
          if (_selectedRecordId != 0)
            Card(
              margin: const EdgeInsets.only(bottom: 16),
              shape: RoundedRectangleBorder(
                borderRadius: BorderRadius.circular(12),
              ),
              child: Padding(
                padding: const EdgeInsets.all(16),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      'Rekam Medis',
                      style: TextStyle(
                        fontSize: 14,
                        fontWeight: FontWeight.bold,
                        color: Colors.grey[700],
                      ),
                    ),
                    const SizedBox(height: 8),
                    Row(
                      children: [
                        const Icon(Icons.date_range, color: Color(0xFF06489F), size: 18),
                        const SizedBox(width: 8),
                        Text(
                          'Tanggal: ${_medicalRecords.firstWhere((record) => record.id == _selectedRecordId, orElse: () => MedicalRecordModel()).getFormattedTanggalKunjungan()}',
                          style: const TextStyle(fontSize: 14),
                        ),
                      ],
                    ),
                    const SizedBox(height: 4),
                    Row(
                      children: [
                        const Icon(Icons.medical_services, color: Color(0xFF06489F), size: 18),
                        const SizedBox(width: 8),
                        Expanded(
                          child: Text(
                            'Diagnosis: ${_medicalRecords.firstWhere((record) => record.id == _selectedRecordId, orElse: () => MedicalRecordModel()).diagnosis ?? 'Tidak ada diagnosis'}',
                            style: const TextStyle(fontSize: 14),
                          ),
                        ),
                      ],
                    ),
                  ],
                ),
              ),
            ),
          Row(
            mainAxisAlignment: MainAxisAlignment.center,
            children: List.generate(5, (i) => _buildStar(i + 1)),
          ),
          const SizedBox(height: 16),
          TextField(
            controller: _feedbackController,
            maxLines: 4,
            decoration: InputDecoration(
              hintText: 'Masukkan Feedback Anda',
              border: OutlineInputBorder(
                borderRadius: BorderRadius.circular(12),
              ),
              contentPadding: const EdgeInsets.all(16),
            ),
          ),
          if (_errorMessage.isNotEmpty) ...[
            const SizedBox(height: 16),
            Container(
              padding: const EdgeInsets.all(12),
              decoration: BoxDecoration(
                color: Colors.red[50],
                borderRadius: BorderRadius.circular(8),
                border: Border.all(color: Colors.red[300]!),
              ),
              child: Row(
                children: [
                  const Icon(Icons.error_outline, color: Colors.red),
                  const SizedBox(width: 8),
                  Expanded(
                    child: Text(
                      _errorMessage,
                      style: const TextStyle(color: Colors.red),
                    ),
                  ),
                ],
              ),
            ),
          ],
          const SizedBox(height: 20),
          SizedBox(
            width: double.infinity,
            child: ElevatedButton(
              onPressed: _isSubmitting ? null : _submitFeedback,
              style: ElevatedButton.styleFrom(
                backgroundColor: const Color(0xFF06489F),
                foregroundColor: Colors.white,
                padding: const EdgeInsets.symmetric(vertical: 16),
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(12),
                ),
              ),
              child: _isSubmitting
                  ? const SizedBox(
                      height: 20,
                      width: 20,
                      child: CircularProgressIndicator(
                        color: Colors.white,
                        strokeWidth: 2,
                      ),
                    )
                  : const Text(
                      'Kirim Feedback',
                      style: TextStyle(
                        fontSize: 16,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildSuccessPage() {
    return Padding(
      padding: const EdgeInsets.all(24),
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Container(
            padding: const EdgeInsets.all(20),
            decoration: BoxDecoration(
              color: Colors.green[50],
              shape: BoxShape.circle,
            ),
            child: Icon(Icons.check_circle, color: Colors.green[400], size: 80),
          ),
          const SizedBox(height: 24),
          const Text(
            'Feedback Berhasil Dikirim!',
            style: TextStyle(
              fontSize: 20,
              color: Color(0xFF06489F),
              fontWeight: FontWeight.bold,
            ),
          ),
          const SizedBox(height: 16),
          const Text(
            'Terima kasih atas masukan Anda! Feedback Anda sangat berharga bagi kami untuk meningkatkan pelayanan. Kami akan menindaklanjuti segera.',
            textAlign: TextAlign.center,
            style: TextStyle(fontSize: 16),
          ),
          const SizedBox(height: 32),
          SizedBox(
            width: double.infinity,
            child: ElevatedButton(
              onPressed: () {
                setState(() {
                  _pageIndex = 0;
                });
              },
              style: ElevatedButton.styleFrom(
                backgroundColor: const Color(0xFF06489F),
                foregroundColor: Colors.white,
                padding: const EdgeInsets.symmetric(vertical: 16),
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(12),
                ),
              ),
              child: const Text(
                'Kembali ke Daftar',
                style: TextStyle(
                  fontSize: 16,
                  fontWeight: FontWeight.bold,
                ),
              ),
            ),
          ),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    Widget body;
    switch (_pageIndex) {
      case 0:
        body = _buildFeedbackList();
        break;
      case 1:
        body = _buildFeedbackForm();
        break;
      case 2:
        body = _buildSuccessPage();
        break;
      default:
        body = const Center(child: Text('Halaman tidak ditemukan'));
    }

    return Scaffold(
      appBar: AppBar(
        title: const Text(
          'Feedback',
          style: TextStyle(
            fontWeight: FontWeight.bold,
            fontFamily: 'KohSantepheap',
            color: Color(0xFF06489F),
          ),
        ),
        backgroundColor: Colors.white,
        elevation: 2,
        iconTheme: const IconThemeData(color: Color(0xFF06489F)),
        leading: _pageIndex > 0
            ? IconButton(
                icon: const Icon(Icons.arrow_back),
                onPressed: () {
                  setState(() {
                    _pageIndex = 0;
                  });
                },
              )
            : null,
      ),
      body: body,
    );
  }

  @override
  void dispose() {
    _feedbackController.dispose();
    super.dispose();
  }
}