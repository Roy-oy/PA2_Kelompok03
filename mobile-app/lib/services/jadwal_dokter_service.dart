import 'dart:convert';
import 'dart:io';
import 'package:http/http.dart' as http;
import 'package:mobile_puskesmas/config/api_config.dart';
import 'package:mobile_puskesmas/models/jadwal_dokter_model.dart';

class JadwalDokterService {
  // Singleton pattern
  static final JadwalDokterService _instance = JadwalDokterService._internal();

  factory JadwalDokterService() => _instance;

  JadwalDokterService._internal();

  Future<List<JadwalDokterModel>> getJadwalDokter() async {
    try {
      print('Fetching doctor schedules from: ${ApiConfig.baseUrl}/jadwal-dokter');

      final response = await http.get(
        Uri.parse('${ApiConfig.baseUrl}/jadwal-dokter'),
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
        },
      );

      print('Response status: ${response.statusCode}');

      if (response.statusCode == 200) {
        final Map<String, dynamic> jsonResponse = jsonDecode(response.body);
        
        if (jsonResponse['data'] != null) {
          final List<dynamic> jadwalList = jsonResponse['data'];
          print('Found ${jadwalList.length} doctor schedules');

          final schedules = jadwalList
              .map((item) => JadwalDokterModel.fromJson(item))
              .toList();

          print('Successfully parsed doctor schedules');
          return schedules;
        }
        throw Exception('Data jadwal dokter tidak ditemukan');
      }
      throw Exception('Gagal memuat jadwal dokter. Status: ${response.statusCode}');
      
    } on SocketException {
      throw Exception('Tidak dapat terhubung ke server. Periksa koneksi internet Anda.');
    } on HttpException {
      throw Exception('Tidak dapat mengambil data jadwal dokter.');
    } on FormatException {
      throw Exception('Format data jadwal dokter tidak valid.');
    } catch (e) {
      print('Error in getJadwalDokter: $e');
      throw Exception('Terjadi kesalahan: ${e.toString()}');
    }
  }

  Future<JadwalDokterModel> getJadwalDokterDetail(int jadwalId) async {
    try {
      print('Fetching doctor schedule detail from: ${ApiConfig.baseUrl}/jadwal-dokter/$jadwalId');

      final response = await http.get(
        Uri.parse('${ApiConfig.baseUrl}/jadwal-dokter/$jadwalId'),
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
        },
      );

      print('Response status: ${response.statusCode}');

      if (response.statusCode == 200) {
        final Map<String, dynamic> jsonResponse = jsonDecode(response.body);
        
        if (jsonResponse['data'] != null) {
          return JadwalDokterModel.fromJson(jsonResponse['data']);
        }
        throw Exception('Detail jadwal dokter tidak ditemukan');
      }
      throw Exception('Gagal memuat detail jadwal dokter. Status: ${response.statusCode}');
      
    } on SocketException {
      throw Exception('Tidak dapat terhubung ke server. Periksa koneksi internet Anda.');
    } on HttpException {
      throw Exception('Tidak dapat mengambil detail jadwal dokter.');
    } on FormatException {
      throw Exception('Format data detail jadwal dokter tidak valid.');
    } catch (e) {
      print('Error in getJadwalDokterDetail: $e');
      throw Exception('Terjadi kesalahan: ${e.toString()}');
    }
  }
}