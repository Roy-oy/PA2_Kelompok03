import 'package:flutter/material.dart';
import 'package:iconsax/iconsax.dart';
import 'package:mobile_puskesmas/models/feedback_model.dart';
import 'package:mobile_puskesmas/services/feedback_service.dart';

class FeedbackDetailScreen extends StatefulWidget {
  final int feedbackId;

  const FeedbackDetailScreen({Key? key, required this.feedbackId}) : super(key: key);

  @override
  State<FeedbackDetailScreen> createState() => _FeedbackDetailScreenState();
}

class _FeedbackDetailScreenState extends State<FeedbackDetailScreen> {
  final FeedbackService _feedbackService = FeedbackService();
  
  bool _isLoading = true;
  String _errorMessage = '';
  FeedbackModel? _feedback;

  @override
  void initState() {
    super.initState();
    _loadFeedback();
  }

  Future<void> _loadFeedback() async {
    setState(() {
      _isLoading = true;
      _errorMessage = '';
    });

    try {
      final feedback = await _feedbackService.getFeedbackById(widget.feedbackId);
      setState(() {
        _feedback = feedback;
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

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.grey[100],
      appBar: AppBar(
        title: const Text(
          'Detail Feedback',
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
          ? const Center(child: CircularProgressIndicator(color: Color(0xFF06489F)))
          : _errorMessage.isNotEmpty
              ? Center(
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
                        onPressed: _loadFeedback,
                      ),
                    ],
                  ),
                )
              : SingleChildScrollView(
                  padding: const EdgeInsets.all(16),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Card(
                        elevation: 2,
                        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                        child: Padding(
                          padding: const EdgeInsets.all(16),
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Row(
                                children: [
                                  CircleAvatar(
                                    backgroundColor: const Color(0xFF06489F).withOpacity(0.1),
                                    child: const Icon(Iconsax.message_text_1, color: Color(0xFF06489F)),
                                  ),
                                  const SizedBox(width: 12),
                                  Expanded(
                                    child: Column(
                                      crossAxisAlignment: CrossAxisAlignment.start,
                                      children: [
                                        const Text(
                                          'Feedback',
                                          style: TextStyle(
                                            fontSize: 18,
                                            fontWeight: FontWeight.bold,
                                            color: Color(0xFF06489F),
                                          ),
                                        ),
                                        Text(
                                          'Dibuat pada: ${_feedback?.getFormattedCreatedAt() ?? '-'}',
                                          style: TextStyle(
                                            fontSize: 12,
                                            color: Colors.grey[600],
                                          ),
                                        ),
                                      ],
                                    ),
                                  ),
                                ],
                              ),
                              const Divider(height: 32),
                              _buildInfoRow(
                                icon: Iconsax.user,
                                title: 'Pasien',
                                value: _feedback?.getPasienName() ?? '-',
                              ),
                              if (_feedback?.id_medical_record != null)
                                _buildInfoRow(
                                  icon: Iconsax.document_text,
                                  title: 'Rekam Medis',
                                  value: 'Tanggal Kunjungan: ${_feedback?.getMedicalRecordDate() ?? '-'}',
                                ),
                              const SizedBox(height: 16),
                              const Text(
                                'Rating',
                                style: TextStyle(
                                  fontSize: 14,
                                  fontWeight: FontWeight.w500,
                                ),
                              ),
                              const SizedBox(height: 8),
                              Row(
                                children: List.generate(
                                  5,
                                  (i) => Icon(
                                    i < (_feedback?.rating ?? 0) ? Icons.star : Icons.star_border,
                                    color: Colors.amber,
                                    size: 24,
                                  ),
                                ),
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
                              Container(
                                width: double.infinity,
                                padding: const EdgeInsets.all(16),
                                decoration: BoxDecoration(
                                  color: Colors.grey[100],
                                  borderRadius: BorderRadius.circular(8),
                                  border: Border.all(color: Colors.grey[300]!),
                                ),
                                child: Text(
                                  _feedback?.comment ?? 'Tidak ada komentar',
                                  style: TextStyle(
                                    fontSize: 14,
                                    color: _feedback?.comment != null && _feedback!.comment!.isNotEmpty
                                        ? Colors.black87
                                        : Colors.grey[600],
                                  ),
                                ),
                              ),
                            ],
                          ),
                        ),
                      ),
                    ],
                  ),
                ),
    );
  }

  Widget _buildInfoRow({
    required IconData icon,
    required String title,
    required String value,
  }) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 16),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Icon(icon, size: 18, color: const Color(0xFF06489F)),
          const SizedBox(width: 12),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  title,
                  style: TextStyle(
                    fontSize: 12,
                    color: Colors.grey[600],
                  ),
                ),
                Text(
                  value,
                  style: const TextStyle(
                    fontSize: 14,
                    fontWeight: FontWeight.w500,
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