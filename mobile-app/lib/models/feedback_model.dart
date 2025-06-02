import 'package:intl/intl.dart';

class FeedbackModel {
  final int? id;
  final String? id_feedback;
  final int rating;
  final String? comment;
  final DateTime? created_at;
  final DateTime? updated_at;
  final int? id_medical_record;
  final int? pasien_id;
  final Map<String, dynamic>? pasien;
  final Map<String, dynamic>? medicalRecord;

  FeedbackModel({
    this.id,
    this.id_feedback,
    this.rating = 0,
    this.comment,
    this.created_at,
    this.updated_at,
    this.id_medical_record,
    this.pasien_id,
    this.pasien,
    this.medicalRecord,
  });

  factory FeedbackModel.fromJson(Map<String, dynamic> json) {
    return FeedbackModel(
      id: json['id'],
      id_feedback: json['id_feedback'],
      rating: json['rating'] ?? 0,
      comment: json['comment'],
      created_at: json['created_at'] != null 
          ? DateTime.parse(json['created_at']) 
          : null,
      updated_at: json['updated_at'] != null 
          ? DateTime.parse(json['updated_at']) 
          : null,
      id_medical_record: json['id_medical_record'],
      pasien_id: json['pasien_id'],
      pasien: json['pasien'],
      medicalRecord: json['medicalRecord'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'id_feedback': id_feedback,
      'rating': rating,
      'comment': comment,
      'created_at': created_at?.toIso8601String(),
      'updated_at': updated_at?.toIso8601String(),
      'id_medical_record': id_medical_record,
      'pasien_id': pasien_id,
    };
  }

  String getFormattedCreatedAt() {
    if (created_at == null) return '-';
    return DateFormat('dd/MM/yyyy HH:mm').format(created_at!);
  }

  String getMedicalRecordDate() {
    if (medicalRecord != null && medicalRecord!['tanggal_kunjungan'] != null) {
      final date = DateTime.parse(medicalRecord!['tanggal_kunjungan']);
      return DateFormat('dd/MM/yyyy').format(date);
    }
    return '-';
  }

  String getPasienName() {
    if (pasien != null && pasien!['nama'] != null) {
      return pasien!['nama'];
    }
    return '-';
  }
}