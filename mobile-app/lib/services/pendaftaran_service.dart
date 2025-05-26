import 'dart:convert';
import 'dart:io';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import 'package:mobile_puskesmas/models/pendaftaran_model.dart';
import 'package:mobile_puskesmas/config/api_config.dart';
import 'package:mobile_puskesmas/services/auth_service.dart';

class PendaftaranService {
  // Singleton pattern
  static final PendaftaranService _instance = PendaftaranService._internal();

  factory PendaftaranService() => _instance;

  PendaftaranService._internal();

  Future<List<PendaftaranModel>> getPendaftarans({String? date}) async {
    try {
      print('Fetching registrations from: ${ApiConfig.baseUrl}/pendaftarans');
      final token = await AuthService().getToken();
      if (token == null) {
        throw Exception('User belum login');
      }

      final uri = Uri.parse('${ApiConfig.baseUrl}/pendaftarans')
          .replace(queryParameters: date != null ? {'date': date} : null);

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
          final List<dynamic> pendaftarans = jsonResponse['data']['data'];
          print('Found ${pendaftarans.length} registrations');
          return pendaftarans
              .map((json) => PendaftaranModel.fromJson(json))
              .toList();
        }
        throw Exception('Data pendaftaran tidak ditemukan');
      }
      throw Exception('Gagal memuat pendaftaran. Status: ${response.statusCode}');
    } on SocketException {
      throw Exception('Tidak dapat terhubung ke server. Periksa koneksi internet Anda.');
    } on HttpException {
      throw Exception('Tidak dapat mengambil data pendaftaran.');
    } on FormatException {
      throw Exception('Format data pendaftaran tidak valid.');
    } catch (e) {
      print('Error in getPendaftarans: $e');
      throw Exception('Terjadi kesalahan: ${e.toString()}');
    }
  }

  Future<List<ClusterModel>> getClusters() async {
    try {
      print('Fetching clusters from: ${ApiConfig.baseUrl}/clusters');
      final token = await AuthService().getToken();
      if (token == null) {
        throw Exception('User belum login');
      }

      final response = await http.get(
        Uri.parse('${ApiConfig.baseUrl}/clusters'),
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer $token',
        },
      );

      print('Response status: ${response.statusCode}');

      if (response.statusCode == 200) {
        final Map<String, dynamic> jsonResponse = jsonDecode(response.body);
        if (jsonResponse['success'] == true && jsonResponse['data'] != null) {
          final List<dynamic> clusters = jsonResponse['data'];
          print('Found ${clusters.length} clusters');
          return clusters.map((json) => ClusterModel.fromJson(json)).toList();
        }
        throw Exception('Data cluster tidak ditemukan');
      }
      throw Exception('Gagal memuat cluster. Status: ${response.statusCode}');
    } on SocketException {
      throw Exception('Tidak dapat terhubung ke server. Periksa koneksi internet Anda.');
    } on HttpException {
      throw Exception('Tidak dapat mengambil data cluster.');
    } on FormatException {
      throw Exception('Format data cluster tidak valid.');
    } catch (e) {
      print('Error in getClusters: $e');
      throw Exception('Terjadi kesalahan: ${e.toString()}');
    }
  }

  Future<PendaftaranModel> createPendaftaran({
    required String nik,
    required String nama,
    required String keluhan,
    required int clusterId,
    required String tanggalDaftar,
    required String jenisPasien,
    required String jenisPembayaran,
    String? appUserId,
    required String jenisKelamin,
    required String tanggalLahir,
    required String tempatLahir,
    required String alamat,
    required String noHp,
    String? noKk,
    String? pekerjaan,
    String? noBpjs,
    required String golonganDarah,
    String? riwayatAlergi,
    String? riwayatPenyakit,
  }) async {
    try {
      print('Creating registration at: ${ApiConfig.baseUrl}/pendaftarans');
      final token = await AuthService().getToken();
      if (token == null) {
        throw Exception('User belum login');
      }

      final pendaftaranData = {
        'nik': nik,
        'nama': nama,
        'keluhan': keluhan,
        'cluster_id': clusterId,
        'tanggal_daftar': tanggalDaftar,
        'jenis_pasien': jenisPasien,
        'jenis_pembayaran': jenisPembayaran,
        'app_user_id': appUserId,
        'jenis_kelamin': jenisKelamin,
        'tanggal_lahir': tanggalLahir,
        'tempat_lahir': tempatLahir,
        'alamat': alamat,
        'no_hp': noHp,
        'no_kk': noKk,
        'pekerjaan': pekerjaan,
        'no_bpjs': noBpjs,
        'golongan_darah': golonganDarah,
        'riwayat_alergi': riwayatAlergi,
        'riwayat_penyakit': riwayatPenyakit,
      };

      final response = await http.post(
        Uri.parse('${ApiConfig.baseUrl}/pendaftarans'),
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer $token',
        },
        body: jsonEncode(pendaftaranData),
      );

      print('Response status: ${response.statusCode}');

      if (response.statusCode == 201) {
        final Map<String, dynamic> jsonResponse = jsonDecode(response.body);
        if (jsonResponse['success'] == true && jsonResponse['data'] != null) {
          print('Successfully created registration');
          return PendaftaranModel.fromJson(jsonResponse['data']);
        }
        throw Exception(jsonResponse['message'] ?? 'Pendaftaran gagal');
      }
      throw Exception('Gagal membuat pendaftaran. Status: ${response.statusCode}');
    } on SocketException {
      throw Exception('Tidak dapat terhubung ke server. Periksa koneksi internet Anda.');
    } on HttpException {
      throw Exception('Tidak dapat mengirim data pendaftaran.');
    } on FormatException {
      throw Exception('Format data pendaftaran tidak valid.');
    } catch (e) {
      print('Error in createPendaftaran: $e');
      throw Exception('Terjadi kesalahan: ${e.toString()}');
    }
  }

  Future<PendaftaranModel> getPendaftaran(int id) async {
    try {
      print('Fetching registration detail from: ${ApiConfig.baseUrl}/pendaftarans/$id');
      final token = await AuthService().getToken();
      if (token == null) {
        throw Exception('User belum login');
      }

      final response = await http.get(
        Uri.parse('${ApiConfig.baseUrl}/pendaftarans/$id'),
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer $token',
        },
      );

      print('Response status: ${response.statusCode}');

      if (response.statusCode == 200) {
        final Map<String, dynamic> jsonResponse = jsonDecode(response.body);
        if (jsonResponse['success'] == true && jsonResponse['data'] != null) {
          return PendaftaranModel.fromJson(jsonResponse['data']);
        }
        throw Exception('Detail pendaftaran tidak ditemukan');
      }
      throw Exception('Gagal memuat detail pendaftaran. Status: ${response.statusCode}');
    } on SocketException {
      throw Exception('Tidak dapat terhubung ke server. Periksa koneksi internet Anda.');
    } on HttpException {
      throw Exception('Tidak dapat mengambil detail pendaftaran.');
    } on FormatException {
      throw Exception('Format data detail pendaftaran tidak valid.');
    } catch (e) {
      print('Error in getPendaftaran: $e');
      throw Exception('Terjadi kesalahan: ${e.toString()}');
    }
  }

  Future<PendaftaranModel> updatePendaftaran({
    required int id,
    required String keluhan,
    required int clusterId,
    required String tanggalDaftar,
    required String jenisPembayaran,
    String? noBpjs,
    String? nama,
    String? jenisKelamin,
    String? tanggalLahir,
    String? tempatLahir,
    String? alamat,
    required String noHp,
    String? noKk,
    String? pekerjaan,
    required String golonganDarah,
    String? riwayatAlergi,
    String? riwayatPenyakit,
  }) async {
    try {
      print('Updating registration at: ${ApiConfig.baseUrl}/pendaftarans/$id');
      final token = await AuthService().getToken();
      if (token == null) {
        throw Exception('User belum login');
      }

      final pendaftaranData = {
        'keluhan': keluhan,
        'cluster_id': clusterId,
        'tanggal_daftar': tanggalDaftar,
        'jenis_pembayaran': jenisPembayaran,
        'no_bpjs': noBpjs,
        'nama': nama,
        'jenis_kelamin': jenisKelamin,
        'tanggal_lahir': tanggalLahir,
        'tempat_lahir': tempatLahir,
        'alamat': alamat,
        'no_hp': noHp,
        'no_kk': noKk,
        'pekerjaan': pekerjaan,
        'golongan_darah': golonganDarah,
        'riwayat_alergi': riwayatAlergi,
        'riwayat_penyakit': riwayatPenyakit,
      }..removeWhere((key, value) => value == null);

      final response = await http.put(
        Uri.parse('${ApiConfig.baseUrl}/pendaftarans/$id'),
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer $token',
        },
        body: jsonEncode(pendaftaranData),
      );

      print('Response status: ${response.statusCode}');

      if (response.statusCode == 200) {
        final Map<String, dynamic> jsonResponse = jsonDecode(response.body);
        if (jsonResponse['success'] == true && jsonResponse['data'] != null) {
          print('Successfully updated registration');
          return PendaftaranModel.fromJson(jsonResponse['data']);
        }
        throw Exception(jsonResponse['message'] ?? 'Pendaftaran gagal diperbarui');
      }
      throw Exception('Gagal memperbarui pendaftaran. Status: ${response.statusCode}');
    } on SocketException {
      throw Exception('Tidak dapat terhubung ke server. Periksa koneksi internet Anda.');
    } on HttpException {
      throw Exception('Tidak dapat mengirim data pembaruan pendaftaran.');
    } on FormatException {
      throw Exception('Format data pembaruan pendaftaran tidak valid.');
    } catch (e) {
      print('Error in updatePendaftaran: $e');
      throw Exception('Terjadi kesalahan: ${e.toString()}');
    }
  }

  Future<void> deletePendaftaran(int id) async {
    try {
      print('Deleting registration from: ${ApiConfig.baseUrl}/pendaftarans/$id');
      final token = await AuthService().getToken();
      if (token == null) {
        throw Exception('User belum login');
      }

      final response = await http.delete(
        Uri.parse('${ApiConfig.baseUrl}/pendaftarans/$id'),
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer $token',
        },
      );

      print('Response status: ${response.statusCode}');

      if (response.statusCode == 200) {
        final Map<String, dynamic> jsonResponse = jsonDecode(response.body);
        if (jsonResponse['success'] == true) {
          print('Successfully deleted registration');
          return;
        }
        throw Exception(jsonResponse['message'] ?? 'Pendaftaran gagal dihapus');
      }
      throw Exception('Gagal menghapus pendaftaran. Status: ${response.statusCode}');
    } on SocketException {
      throw Exception('Tidak dapat terhubung ke server. Periksa koneksi internet Anda.');
    } on HttpException {
      throw Exception('Tidak dapat menghapus pendaftaran.');
    } on FormatException {
      throw Exception('Format data penghapusan pendaftaran tidak valid.');
    } catch (e) {
      print('Error in deletePendaftaran: $e');
      throw Exception('Terjadi kesalahan: ${e.toString()}');
    }
  }
}