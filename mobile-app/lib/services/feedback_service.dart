import 'dart:convert';
import 'dart:io';
import 'package:http/http.dart' as http;
import 'package:mobile_puskesmas/config/api_config.dart';
import 'package:mobile_puskesmas/models/feedback_model.dart';
import 'package:mobile_puskesmas/services/auth_service.dart';

class FeedbackService {
  final AuthService _authService = AuthService();

  // Get all feedback for the current user
  Future<List<FeedbackModel>> getFeedbacks({int? medicalRecordId}) async {
    try {
      final token = await _authService.getToken();
      if (token == null) {
        throw Exception('Anda belum login. Silakan login terlebih dahulu.');
      }

      String url = '${ApiConfig.baseUrl}/feedback';
      if (medicalRecordId != null) {
        url += '?id_medical_record=$medicalRecordId';
      }

      final response = await http.get(
        Uri.parse(url),
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer $token',
        },
      );

      final data = jsonDecode(response.body);
      if (response.statusCode == 200 && data['success'] == true) {
        final List<dynamic> feedbacksData = data['data']['data'];
        return feedbacksData
            .map((item) => FeedbackModel.fromJson(item))
            .toList();
      } else {
        throw Exception(data['message'] ?? 'Gagal mengambil data feedback');
      }
    } on SocketException {
      throw Exception('Tidak dapat terhubung ke server. Periksa koneksi Anda.');
    } catch (e) {
      throw Exception('Terjadi kesalahan: ${e.toString()}');
    }
  }

  // Create new feedback
  Future<FeedbackModel> createFeedback({
    required int rating,
    String? comment,
    int? id_medical_record,
    required int pasien_id,
  }) async {
    try {
      final token = await _authService.getToken();
      if (token == null) {
        throw Exception('Anda belum login. Silakan login terlebih dahulu.');
      }

      final feedbackData = {
        'rating': rating,
        'comment': comment,
        'id_medical_record': id_medical_record,
        'pasien_id': pasien_id,
      };

      final response = await http.post(
        Uri.parse('${ApiConfig.baseUrl}/feedback'),
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer $token',
        },
        body: jsonEncode(feedbackData),
      );

      final data = jsonDecode(response.body);
      if (response.statusCode == 201 && data['success'] == true) {
        return FeedbackModel.fromJson(data['data']);
      } else {
        String errorMessage = data['message'] ?? 'Gagal mengirim feedback';
        if (data['errors'] != null) {
          final errors = data['errors'] as Map<String, dynamic>;
          errorMessage += ': ${errors.values.join(', ')}';
        }
        throw Exception(errorMessage);
      }
    } on SocketException {
      throw Exception('Tidak dapat terhubung ke server. Periksa koneksi Anda.');
    } catch (e) {
      throw Exception('Terjadi kesalahan: ${e.toString()}');
    }
  }

  // Get feedback by ID
  Future<FeedbackModel> getFeedbackById(int id) async {
    try {
      final token = await _authService.getToken();
      if (token == null) {
        throw Exception('Anda belum login. Silakan login terlebih dahulu.');
      }

      final response = await http.get(
        Uri.parse('${ApiConfig.baseUrl}/feedback/$id'),
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer $token',
        },
      );

      final data = jsonDecode(response.body);
      if (response.statusCode == 200 && data['success'] == true) {
        return FeedbackModel.fromJson(data['data']);
      } else {
        throw Exception(data['message'] ?? 'Feedback tidak ditemukan');
      }
    } on SocketException {
      throw Exception('Tidak dapat terhubung ke server. Periksa koneksi Anda.');
    } catch (e) {
      throw Exception('Terjadi kesalahan: ${e.toString()}');
    }
  }

  // Update feedback
  Future<FeedbackModel> updateFeedback({
    required int id,
    required int rating,
    String? comment,
  }) async {
    try {
      final token = await _authService.getToken();
      if (token == null) {
        throw Exception('Anda belum login. Silakan login terlebih dahulu.');
      }

      final updateData = {
        'rating': rating,
        'comment': comment,
      };

      final response = await http.put(
        Uri.parse('${ApiConfig.baseUrl}/feedback/$id'),
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer $token',
        },
        body: jsonEncode(updateData),
      );

      final data = jsonDecode(response.body);
      if (response.statusCode == 200 && data['success'] == true) {
        return FeedbackModel.fromJson(data['data']);
      } else {
        throw Exception(data['message'] ?? 'Gagal memperbarui feedback');
      }
    } on SocketException {
      throw Exception('Tidak dapat terhubung ke server. Periksa koneksi Anda.');
    } catch (e) {
      throw Exception('Terjadi kesalahan: ${e.toString()}');
    }
  }

  // Delete feedback
  Future<bool> deleteFeedback(int id) async {
    try {
      final token = await _authService.getToken();
      if (token == null) {
        throw Exception('Anda belum login. Silakan login terlebih dahulu.');
      }

      final response = await http.delete(
        Uri.parse('${ApiConfig.baseUrl}/feedback/$id'),
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer $token',
        },
      );

      final data = jsonDecode(response.body);
      if (response.statusCode == 200 && data['success'] == true) {
        return true;
      } else {
        throw Exception(data['message'] ?? 'Gagal menghapus feedback');
      }
    } on SocketException {
      throw Exception('Tidak dapat terhubung ke server. Periksa koneksi Anda.');
    } catch (e) {
      throw Exception('Terjadi kesalahan: ${e.toString()}');
    }
  }
}