import 'package:flutter/material.dart';
import 'package:iconsax/iconsax.dart';
import 'package:mobile_puskesmas/models/feedback_model.dart';
import 'package:mobile_puskesmas/services/feedback_service.dart';
import 'package:mobile_puskesmas/services/auth_service.dart';
import 'package:mobile_puskesmas/services/medical_record_service.dart';
import 'package:mobile_puskesmas/services/pasien_service.dart';
import 'package:mobile_puskesmas/models/user_model.dart';
import 'package:mobile_puskesmas/models/medical_record_model.dart';

class FeedbackScreen extends StatefulWidget {
  final int? medicalRecordId; // Opsional, jika dari halaman rekam medis

  const FeedbackScreen({Key? key, this.medicalRecordId}) : super(key: key);

  @override
  State<FeedbackScreen> createState() => _FeedbackScreenState();
}

class _FeedbackScreenState extends State<FeedbackScreen> {
  final FeedbackService _feedbackService = FeedbackService();
  final AuthService _authService = AuthService();
  final MedicalRecordService _medicalRecordService = MedicalRecordService();
  final PasienService _pasienService = PasienService();

  List<FeedbackModel> _feedbacks = [];
  List<MedicalRecordModel> _medicalRecords = [];
  UserModel? _currentUser;
  int _selectedRecordId = 0;

  bool _isLoading = true;
  bool _isSubmitting = false;
  String _errorMessage = '';

  // Controller dan variable untuk form
  int _currentTabIndex = 0; // 0: Daftar, 1: Form Input
  final TextEditingController _commentController = TextEditingController();
  int _rating = 3;
  FeedbackModel? _editingFeedback;

  @override
  void initState() {
    super.initState();
    _loadUserData();

    // Jika ada medicalRecordId, langsung buka form input
    if (widget.medicalRecordId != null) {
      _selectedRecordId = widget.medicalRecordId!;
      _currentTabIndex = 1;
    }
  }

  Future<void> _loadUserData() async {
    setState(() {
      _isLoading = true;
      _errorMessage = '';
    });

    try {
      final user = await _authService.getUserData();
      if (user == null) {
        setState(() {
          _errorMessage =
              'Silakan login terlebih dahulu untuk mengakses halaman ini';
          _isLoading = false;
        });
        return;
      }

      setState(() {
        _currentUser = user;
      });

      await _loadMedicalRecords();
      await _loadFeedbacks();
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

  Future<void> _loadMedicalRecords() async {
    try {
      final records = await _medicalRecordService.getMedicalRecords(nik: '');
      setState(() {
        _medicalRecords = records;
        if (records.isNotEmpty && _selectedRecordId == 0) {
          _selectedRecordId = records[0].id ?? 0;
        }
      });
    } catch (e) {
      setState(() {
        _errorMessage = e.toString();
      });
    }
  }

  Future<void> _loadFeedbacks() async {
    try {
      final feedbacks = await _feedbackService.getFeedbacks();
      setState(() {
        _feedbacks = feedbacks;
      });
    } catch (e) {
      setState(() {
        _errorMessage = e.toString();
      });
    }
  }

  Future<void> _submitFeedback() async {
    if (_currentUser == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Anda belum login')),
      );
      return;
    }

    setState(() {
      _isSubmitting = true;
      _errorMessage = '';
    });

    try {
      if (_editingFeedback != null) {
        // Update existing feedback
        await _feedbackService.updateFeedback(
          id: _editingFeedback!.id!,
          rating: _rating,
          comment: _commentController.text,
        );
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Feedback berhasil diperbarui')),
        );
      } else {
        // Create new feedback
        await _feedbackService.createFeedback(
          rating: _rating,
          comment: _commentController.text,
          id_medical_record: _selectedRecordId,
          pasien_id: _currentUser!.id!,
        );
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Feedback berhasil dikirim')),
        );
      }

      // Reset form dan kembali ke daftar
      _resetForm();
      await _loadFeedbacks();
      setState(() {
        _currentTabIndex = 0;
      });
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

  void _resetForm() {
    setState(() {
      _rating = 3;
      _commentController.clear();
      _editingFeedback = null;
    });
  }

  void _editFeedback(FeedbackModel feedback) {
    setState(() {
      _editingFeedback = feedback;
      _rating = feedback.rating;
      _commentController.text = feedback.comment ?? '';
      _currentTabIndex = 1;
    });
  }

  Future<void> _deleteFeedback(FeedbackModel feedback) async {
    // Tampilkan dialog konfirmasi
    final shouldDelete = await showDialog<bool>(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('Hapus Feedback'),
        content: const Text('Anda yakin ingin menghapus feedback ini?'),
        actions: [
          TextButton(
            onPressed: () => Navigator.of(context).pop(false),
            child: const Text('Batal'),
          ),
          TextButton(
            onPressed: () => Navigator.of(context).pop(true),
            child: const Text('Hapus', style: TextStyle(color: Colors.red)),
          ),
        ],
      ),
    );

    if (shouldDelete != true) return;

    setState(() {
      _isLoading = true;
      _errorMessage = '';
    });

    try {
      await _feedbackService.deleteFeedback(feedback.id!);
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Feedback berhasil dihapus')),
      );
      await _loadFeedbacks();
    } catch (e) {
      setState(() {
        _errorMessage = e.toString();
      });
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text(_errorMessage)),
      );
    } finally {
      setState(() {
        _isLoading = false;
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
    if (_feedbacks.isEmpty) {
      return Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(Iconsax.message_question, size: 64, color: Colors.grey[400]),
            const SizedBox(height: 16),
            Text(
              'Belum ada feedback',
              style: TextStyle(
                fontSize: 16,
                fontWeight: FontWeight.w600,
                color: Colors.grey[600],
              ),
            ),
            const SizedBox(height: 8),
            Text(
              'Berikan feedback untuk pelayanan yang telah Anda terima',
              textAlign: TextAlign.center,
              style: TextStyle(
                fontSize: 14,
                color: Colors.grey[500],
              ),
            ),
            const SizedBox(height: 24),
            ElevatedButton.icon(
              icon: const Icon(Iconsax.add_circle),
              label: const Text('Buat Feedback Baru'),
              style: ElevatedButton.styleFrom(
                backgroundColor: const Color(0xFF06489F),
                foregroundColor: Colors.white,
                padding:
                    const EdgeInsets.symmetric(horizontal: 24, vertical: 12),
                shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(12)),
              ),
              onPressed: () {
                setState(() {
                  _resetForm();
                  _currentTabIndex = 1;
                });
              },
            ),
          ],
        ),
      );
    }

    return ListView.builder(
      padding: const EdgeInsets.all(16),
      itemCount: _feedbacks.length + 1, // +1 untuk tombol tambah
      itemBuilder: (context, index) {
        if (index == 0) {
          // Tombol tambah feedback baru
          return Padding(
            padding: const EdgeInsets.only(bottom: 16),
            child: ElevatedButton.icon(
              icon: const Icon(Iconsax.add_circle),
              label: const Text('Buat Feedback Baru'),
              style: ElevatedButton.styleFrom(
                backgroundColor: const Color(0xFF06489F),
                foregroundColor: Colors.white,
                padding: const EdgeInsets.symmetric(vertical: 12),
                shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(12)),
              ),
              onPressed: () {
                setState(() {
                  _resetForm();
                  _currentTabIndex = 1;
                });
              },
            ),
          );
        }

        final feedback = _feedbacks[index - 1];
        return Card(
          margin: const EdgeInsets.only(bottom: 12),
          elevation: 2,
          shape:
              RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
          child: Padding(
            padding: const EdgeInsets.all(16),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Text(
                      feedback.getFormattedCreatedAt(),
                      style: TextStyle(
                        fontSize: 12,
                        color: Colors.grey[600],
                      ),
                    ),
                    Row(
                      children: [
                        IconButton(
                          icon: const Icon(Iconsax.edit,
                              size: 18, color: Color(0xFF06489F)),
                          onPressed: () => _editFeedback(feedback),
                        ),
                        IconButton(
                          icon: const Icon(Iconsax.trash,
                              size: 18, color: Colors.red),
                          onPressed: () => _deleteFeedback(feedback),
                        ),
                      ],
                    ),
                  ],
                ),
                const SizedBox(height: 8),
                if (feedback.id_medical_record != null) ...[
                  Row(
                    children: [
                      const Icon(Iconsax.document_text,
                          size: 18, color: Color(0xFF06489F)),
                      const SizedBox(width: 8),
                      Expanded(
                        child: Text(
                          'Rekam Medis: ${feedback.getMedicalRecordDate()}',
                          style: const TextStyle(
                            fontSize: 14,
                            fontWeight: FontWeight.w500,
                          ),
                        ),
                      ),
                    ],
                  ),
                  const SizedBox(height: 8),
                ],
                Row(
                  children: [
                    const Icon(Iconsax.user,
                        size: 18, color: Color(0xFF06489F)),
                    const SizedBox(width: 8),
                    Expanded(
                      child: Text(
                        'Pasien: ${feedback.getPasienName()}',
                        style: const TextStyle(
                          fontSize: 14,
                          fontWeight: FontWeight.w500,
                        ),
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 12),
                Row(
                  children: List.generate(
                    5,
                    (i) => Icon(
                      i < feedback.rating ? Icons.star : Icons.star_border,
                      color: Colors.amber,
                      size: 20,
                    ),
                  ),
                ),
                if (feedback.comment != null &&
                    feedback.comment!.isNotEmpty) ...[
                  const SizedBox(height: 12),
                  Container(
                    padding: const EdgeInsets.all(12),
                    decoration: BoxDecoration(
                      color: Colors.grey[100],
                      borderRadius: BorderRadius.circular(8),
                    ),
                    child: Text(
                      feedback.comment!,
                      style: const TextStyle(fontSize: 14),
                    ),
                  ),
                ],
              ],
            ),
          ),
        );
      },
    );
  }

  Widget _buildFeedbackForm() {
    return SingleChildScrollView(
      padding: const EdgeInsets.all(16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Card(
            elevation: 2,
            shape:
                RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    _editingFeedback != null
                        ? 'Edit Feedback'
                        : 'Berikan Feedback Anda',
                    style: const TextStyle(
                      fontSize: 18,
                      fontWeight: FontWeight.bold,
                      color: Color(0xFF06489F),
                    ),
                  ),
                  const SizedBox(height: 16),
                  if (_editingFeedback == null) ...[
                    const Text(
                      'Pilih Rekam Medis (Opsional)',
                      style: TextStyle(
                        fontSize: 14,
                        fontWeight: FontWeight.w500,
                      ),
                    ),
                    const SizedBox(height: 8),
                    DropdownButtonFormField<int>(
                      decoration: InputDecoration(
                        border: OutlineInputBorder(
                          borderRadius: BorderRadius.circular(12),
                        ),
                        contentPadding: const EdgeInsets.symmetric(
                            horizontal: 16, vertical: 12),
                        hintText: 'Pilih rekam medis',
                      ),
                      value: _selectedRecordId != 0 ? _selectedRecordId : null,
                      items: [
                        const DropdownMenuItem<int>(
                          value: 0,
                          child: Text('Tidak terkait rekam medis'),
                        ),
                        ..._medicalRecords.map((record) {
                          return DropdownMenuItem<int>(
                            value: record.id,
                            child: Text(
                              '${record.getFormattedTanggalKunjungan()} - ${record.diagnosis ?? 'Tidak ada diagnosis'}',
                              overflow: TextOverflow.ellipsis,
                            ),
                          );
                        }).toList(),
                      ],
                      onChanged: (value) {
                        setState(() {
                          _selectedRecordId = value ?? 0;
                        });
                      },
                    ),
                    const SizedBox(height: 16),
                  ],
                  const Text(
                    'Rating',
                    style: TextStyle(
                      fontSize: 14,
                      fontWeight: FontWeight.w500,
                    ),
                  ),
                  const SizedBox(height: 8),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: List.generate(5, (i) => _buildStar(i + 1)),
                  ),
                  const SizedBox(height: 16),
                  const Text(
                    'Komentar',
                    style: TextStyle(
                      fontSize: 14,
                      fontWeight: FontWeight.w500,
                    ),
                  ),
                  const SizedBox(height: 8),
                  TextField(
                    controller: _commentController,
                    maxLines: 4,
                    decoration: InputDecoration(
                      hintText: 'Tulis komentar Anda di sini...',
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
                  Row(
                    children: [
                      Expanded(
                        child: OutlinedButton(
                          onPressed: () {
                            setState(() {
                              _currentTabIndex = 0;
                              _resetForm();
                            });
                          },
                          style: OutlinedButton.styleFrom(
                            padding: const EdgeInsets.symmetric(vertical: 12),
                            side: const BorderSide(color: Color(0xFF06489F)),
                            shape: RoundedRectangleBorder(
                              borderRadius: BorderRadius.circular(12),
                            ),
                          ),
                          child: const Text('Batal'),
                        ),
                      ),
                      const SizedBox(width: 16),
                      Expanded(
                        child: ElevatedButton(
                          onPressed: _isSubmitting ? null : _submitFeedback,
                          style: ElevatedButton.styleFrom(
                            backgroundColor: const Color(0xFF06489F),
                            foregroundColor: Colors.white,
                            padding: const EdgeInsets.symmetric(vertical: 12),
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
                              : Text(_editingFeedback != null
                                  ? 'Perbarui'
                                  : 'Kirim'),
                        ),
                      ),
                    ],
                  ),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.grey[100],
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
      ),
      body: _isLoading
          ? const Center(
              child: CircularProgressIndicator(color: Color(0xFF06489F)))
          : _errorMessage.isNotEmpty &&
                  _feedbacks.isEmpty &&
                  _currentTabIndex == 0
              ? Center(
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      Icon(Icons.error_outline,
                          size: 64, color: Colors.red[300]),
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
                          padding: const EdgeInsets.symmetric(
                              horizontal: 24, vertical: 12),
                          shape: RoundedRectangleBorder(
                              borderRadius: BorderRadius.circular(12)),
                        ),
                        onPressed: _loadUserData,
                      ),
                    ],
                  ),
                )
              : _currentTabIndex == 0
                  ? _buildFeedbackList()
                  : _buildFeedbackForm(),
      bottomNavigationBar: _isLoading ||
              (_errorMessage.isNotEmpty &&
                  _feedbacks.isEmpty &&
                  _currentTabIndex == 0)
          ? null
          : BottomNavigationBar(
              currentIndex: _currentTabIndex,
              onTap: (index) {
                if (index == 1) {
                  // Jika user memilih tab Form, reset form terlebih dahulu
                  _resetForm();
                }
                setState(() {
                  _currentTabIndex = index;
                });
              },
              backgroundColor: Colors.white,
              selectedItemColor: const Color(0xFF06489F),
              unselectedItemColor: Colors.grey[600],
              selectedLabelStyle: const TextStyle(fontWeight: FontWeight.bold),
              items: const [
                BottomNavigationBarItem(
                  icon: Icon(Iconsax.message_text),
                  label: 'Daftar Feedback',
                ),
                BottomNavigationBarItem(
                  icon: Icon(Iconsax.add_circle),
                  label: 'Buat Feedback',
                ),
              ],
            ),
    );
  }

  @override
  void dispose() {
    _commentController.dispose();
    super.dispose();
  }
}
