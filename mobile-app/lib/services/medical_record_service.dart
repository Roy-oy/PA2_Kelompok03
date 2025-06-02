import 'dart:convert';
import 'dart:io';
import 'package:http/http.dart' as http;
import 'package:mobile_puskesmas/models/medical_record_model.dart';
import 'package:mobile_puskesmas/config/api_config.dart';
import 'package:mobile_puskesmas/services/auth_service.dart';

class MedicalRecordService {
  static final MedicalRecordService _instance = MedicalRecordService._internal();

  factory MedicalRecordService() => _instance;

  MedicalRecordService._internal();

  Future<List<MedicalRecordModel>> getMedicalRecords({String? search, required String nik}) async {
    try {
      print('Fetching medical records from: ${ApiConfig.baseUrl}/medical-records');
      final token = await AuthService().getToken();
      if (token == null) {
        throw Exception('User belum login');
      }

      final uri = Uri.parse('${ApiConfig.baseUrl}/medical-records')
          .replace(queryParameters: search != null ? {'search': search} : null);

      final response = await http.get(
        uri,
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer $token',
        },
      );

      print('Response status: ${response.statusCode}');

      if (response.statusCode == 200) {
        final Map<String, dynamic> jsonResponse = jsonDecode(response.body);
        if (jsonResponse['success'] == true && jsonResponse['data'] != null) {
          final List<dynamic> medicalRecords = jsonResponse['data']['data'];
          print('Found ${medicalRecords.length} medical records');
          return medicalRecords
              .map((json) => MedicalRecordModel.fromJson(json))
              .toList();
        }
        throw Exception('Data rekam medis tidak ditemukan');
      }
      throw Exception('Gagal memuat rekam medis. Status: ${response.statusCode}');
    } on SocketException {
      throw Exception('Tidak dapat terhubung ke server. Periksa koneksi internet Anda.');
    } on HttpException {
      throw Exception('Tidak dapat mengambil data rekam medis.');
    } on FormatException {
      throw Exception('Format data rekam medis tidak valid.');
    } catch (e) {
      print('Error in getMedicalRecords: $e');
      throw Exception('Terjadi kesalahan: ${e.toString()}');
    }
  }

  Future<Map<String, dynamic>> createMedicalRecord({
    required int pendaftaranId,
    required int pasienId,
    required String tanggalKunjungan,
    required String keluhan,
    required String diagnosis,
    required String pengobatan,
    String? hasilPemeriksaan,
    double? tinggiBadan,
    double? beratBadan,
    String? tekananDarah,
    double? suhuBadan,
  }) async {
    try {
      print('Creating medical record at: ${ApiConfig.baseUrl}/medical-records');
      final token = await AuthService().getToken();
      if (token == null) {
        throw Exception('User belum login');
      }

      final medicalRecordData = {
        'pendaftaran_id': pendaftaranId,
        'pasien_id': pasienId,
        'tanggal_kunjungan': tanggalKunjungan,
        'keluhan': keluhan,
        'diagnosis': diagnosis,
        'pengobatan': pengobatan,
        'hasil_pemeriksaan': hasilPemeriksaan,
        'tinggi_badan': tinggiBadan,
        'berat_badan': beratBadan,
        'tekanan_darah': tekananDarah,
        'suhu_badan': suhuBadan,
      }..removeWhere((key, value) => value == null);

      print('Sending data: $medicalRecordData');

      final response = await http.post(
        Uri.parse('${ApiConfig.baseUrl}/medical-records'),
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer $token',
        },
        body: jsonEncode(medicalRecordData),
      );

      print('Response status: ${response.statusCode}, Body: ${response.body}');

      if (response.statusCode == 201) {
        final Map<String, dynamic> jsonResponse = jsonDecode(response.body);
        if (jsonResponse['success'] == true && jsonResponse['data'] != null) {
          print('Successfully created medical record');
          return jsonResponse['data'];
        }
        throw Exception(jsonResponse['message'] ?? 'Pembuatan rekam medis gagal');
      } else if (response.statusCode == 422) {
        final Map<String, dynamic> jsonResponse = jsonDecode(response.body);
        if (jsonResponse['errors'] != null) {
          final String errorMessage = jsonResponse['errors'].entries
              .map((e) => '${e.value.join(', ')}')
              .join('\n');
          throw Exception('Validasi gagal:\n$errorMessage');
        }
        throw Exception('Validasi gagal. Periksa kembali data Anda.');
      }
      throw Exception('Gagal membuat rekam medis. Status: ${response.statusCode}');
    } on SocketException {
      throw Exception('Tidak dapat terhubung ke server. Periksa koneksi internet Anda.');
    } on HttpException {
      throw Exception('Tidak dapat mengirim data rekam medis.');
    } on FormatException {
      throw Exception('Format data rekam medis tidak valid.');
    } catch (e) {
      print('Error in createMedicalRecord: $e');
      throw Exception('Terjadi kesalahan: ${e.toString()}');
    }
  }

  Future<Map<String, dynamic>> getMedicalRecord(int id) async {
    try {
      print('Fetching medical record detail from: ${ApiConfig.baseUrl}/medical-records/$id');
      final token = await AuthService().getToken();
      if (token == null) {
        throw Exception('User belum login');
      }

      final response = await http.get(
        Uri.parse('${ApiConfig.baseUrl}/medical-records/$id'),
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer $token',
        },
      );

      print('Response status: ${response.statusCode}');

      if (response.statusCode == 200) {
        final Map<String, dynamic> jsonResponse = jsonDecode(response.body);
        if (jsonResponse['success'] == true && jsonResponse['data'] != null) {
          return jsonResponse['data'];
        }
        throw Exception('Detail rekam medis tidak ditemukan');
      }
      throw Exception('Gagal memuat detail rekam medis. Status: ${response.statusCode}');
    } on SocketException {
      throw Exception('Tidak dapat terhubung ke server. Periksa koneksi internet Anda.');
    } on HttpException {
      throw Exception('Tidak dapat mengambil detail rekam medis.');
    } on FormatException {
      throw Exception('Format data detail rekam medis tidak valid.');
    } catch (e) {
      print('Error in getMedicalRecord: $e');
      throw Exception('Terjadi kesalahan: ${e.toString()}');
    }
  }

  Future<Map<String, dynamic>> updateMedicalRecord({
    required int id,
    required String tanggalKunjungan,
    required String keluhan,
    required String diagnosis,
    required String pengobatan,
    String? hasilPemeriksaan,
    double? tinggiBadan,
    double? beratBadan,
    String? tekananDarah,
    double? suhuBadan,
  }) async {
    try {
      print('Updating medical record at: ${ApiConfig.baseUrl}/medical-records/$id');
      final token = await AuthService().getToken();
      if (token == null) {
        throw Exception('User belum login');
      }

      final medicalRecordData = {
        'tanggal_kunjungan': tanggalKunjungan,
        'keluhan': keluhan,
        'diagnosis': diagnosis,
        'pengobatan': pengobatan,
        'hasil_pemeriksaan': hasilPemeriksaan,
        'tinggi_badan': tinggiBadan,
        'berat_badan': beratBadan,
        'tekanan_darah': tekananDarah,
        'suhu_badan': suhuBadan,
      }..removeWhere((key, value) => value == null);

      final response = await http.put(
        Uri.parse('${ApiConfig.baseUrl}/medical-records/$id'),
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer $token',
        },
        body: jsonEncode(medicalRecordData),
      );

      print('Response status: ${response.statusCode}');

      if (response.statusCode == 200) {
        final Map<String, dynamic> jsonResponse = jsonDecode(response.body);
        if (jsonResponse['success'] == true && jsonResponse['data'] != null) {
          print('Successfully updated medical record');
          return jsonResponse['data'];
        }
        throw Exception(jsonResponse['message'] ?? 'Pembaruan rekam medis gagal');
      } else if (response.statusCode == 422) {
        final Map<String, dynamic> jsonResponse = jsonDecode(response.body);
        if (jsonResponse['errors'] != null) {
          final String errorMessage = jsonResponse['errors'].entries
              .map((e) => '${e.value.join(', ')}')
              .join('\n');
          throw Exception('Validasi gagal:\n$errorMessage');
        }
        throw Exception('Validasi gagal. Periksa kembali data Anda.');
      }
      throw Exception('Gagal memperbarui rekam medis. Status: ${response.statusCode}');
    } on SocketException {
      throw Exception('Tidak dapat terhubung ke server. Periksa koneksi internet Anda.');
    } on HttpException {
      throw Exception('Tidak dapat mengirim data pembaruan rekam medis.');
    } on FormatException {
      throw Exception('Format data pembaruan rekam medis tidak valid.');
    } catch (e) {
      print('Error in updateMedicalRecord: $e');
      throw Exception('Terjadi kesalahan: ${e.toString()}');
    }
  }

  Future<void> deleteMedicalRecord(int id) async {
    try {
      print('Deleting medical record from: ${ApiConfig.baseUrl}/medical-records/$id');
      final token = await AuthService().getToken();
      if (token == null) {
        throw Exception('User belum login');
      }

      final response = await http.delete(
        Uri.parse('${ApiConfig.baseUrl}/medical-records/$id'),
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer $token',
        },
      );

      print('Response status: ${response.statusCode}');

      if (response.statusCode == 200) {
        final Map<String, dynamic> jsonResponse = jsonDecode(response.body);
        if (jsonResponse['success'] == true) {
          print('Successfully deleted medical record');
          return;
        }
        throw Exception(jsonResponse['message'] ?? 'Penghapusan rekam medis gagal');
      }
      throw Exception('Gagal menghapus rekam medis. Status: ${response.statusCode}');
    } on SocketException {
      throw Exception('Tidak dapat terhubung ke server. Periksa koneksi internet Anda.');
    } on HttpException {
      throw Exception('Tidak dapat menghapus rekam medis.');
    } on FormatException {
      throw Exception('Format data penghapusan rekam medis tidak valid.');
    } catch (e) {
      print('Error in deleteMedicalRecord: $e');
      throw Exception('Terjadi kesalahan: ${e.toString()}');
    }
  }

  Future<Map<String, dynamic>> getCurrentAntrian() async {
    try {
      print('Fetching current antrian from: ${ApiConfig.baseUrl}/medical-records/current-antrian');
      final token = await AuthService().getToken();
      if (token == null) {
        throw Exception('User belum login');
      }

      final response = await http.get(
        Uri.parse('${ApiConfig.baseUrl}/medical-records/current-antrian'),
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer $token',
        },
      );

      print('Response status: ${response.statusCode}');

      if (response.statusCode == 200) {
        final Map<String, dynamic> jsonResponse = jsonDecode(response.body);
        if (jsonResponse['success'] == true && jsonResponse['data'] != null) {
          return jsonResponse['data'];
        }
        throw Exception('Antrian tidak ditemukan');
      } else if (response.statusCode == 404) {
        throw Exception('Tidak ada antrian yang sedang dilayani saat ini.');
      }
      throw Exception('Gagal memuat antrian. Status: ${response.statusCode}');
    } on SocketException {
      throw Exception('Tidak dapat terhubung ke server. Periksa koneksi internet Anda.');
    } on HttpException {
      throw Exception('Tidak dapat mengambil data antrian.');
    } on FormatException {
      throw Exception('Format data antrian tidak valid.');
    } catch (e) {
      print('Error in getCurrentAntrian: $e');
      throw Exception('Terjadi kesalahan: ${e.toString()}');
    }
  }
}