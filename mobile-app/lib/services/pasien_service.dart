import 'dart:convert';
import 'dart:io';
import 'package:http/http.dart' as http;
import 'package:mobile_puskesmas/config/api_config.dart';
import 'package:mobile_puskesmas/models/user_model.dart';
import 'package:mobile_puskesmas/screens/patient_form_screen.dart';
import 'package:mobile_puskesmas/services/auth_service.dart';
import 'package:shared_preferences/shared_preferences.dart';

class PasienService {
  // Singleton pattern
  static final PasienService _instance = PasienService._internal();

  factory PasienService() => _instance;

  PasienService._internal();

  // Get all patients
  Future<List<PatientFormScreen>> getAllPasien() async {
    try {
      // Get token from AuthService
      final authService = AuthService();
      final user = await authService.getUserData();

      if (user == null || user.token == null) {
        throw Exception('Anda perlu login terlebih dahulu');
      }

      final response = await http.get(
        Uri.parse('${ApiConfig.baseUrl}/pasien'),
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
          'Authorization': 'Bearer ${user.token}',
        },
      );

      if (response.statusCode == 200) {
        final Map<String, dynamic> jsonResponse = jsonDecode(response.body);

        throw Exception('Data pasien tidak ditemukan');
      }
      throw Exception(
          'Gagal memuat daftar pasien. Status: ${response.statusCode}');
    } catch (e) {
      throw Exception('Terjadi kesalahan: ${e.toString()}');
    }
  }

  Future<List<Map<String, dynamic>>> getMedicalRecords() async {
    try {
      // Dapatkan token dari AuthService
      final token = await AuthService().getToken();

      if (token == null) {
        // Jika tidak ada token, return list kosong
        return [];
      }

      // Panggil API untuk mendapatkan rekam medis
      final response = await http.get(
        Uri.parse('${ApiConfig.baseUrl}/pasien/medical-records'),
        headers: {
          'Authorization': 'Bearer $token',
          'Content-Type': 'application/json',
        },
      );

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        if (data['success'] == true && data['data'] != null) {
          return List<Map<String, dynamic>>.from(data['data']);
        }
      }

      // Jika tidak berhasil mendapatkan data, kembalikan list kosong
      return [];
    } catch (e) {
      print('Error in getMedicalRecords: $e');
      // Jika terjadi kesalahan, kembalikan list kosong
      return [];
    }
  }

  // Fungsi untuk mengambil detail rekam medis
  Future<Map<String, dynamic>?> getMedicalRecordDetail(int recordId) async {
    try {
      final token = await AuthService().getToken();

      if (token == null) {
        return null;
      }

      final response = await http.get(
        Uri.parse('${ApiConfig.baseUrl}/pasien/medical-records/$recordId'),
        headers: {
          'Authorization': 'Bearer $token',
          'Content-Type': 'application/json',
        },
      );

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        if (data['success'] == true && data['data'] != null) {
          return Map<String, dynamic>.from(data['data']);
        }
      }

      return null;
    } catch (e) {
      print('Error in getMedicalRecordDetail: $e');
      return null;
    }
  }
}
