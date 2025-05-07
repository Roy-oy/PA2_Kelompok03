import 'dart:convert';
import 'dart:io';
import 'package:http/http.dart' as http;
import 'package:mobile_puskesmas/config/api_config.dart';
import 'package:mobile_puskesmas/models/pengumuman_model.dart';

class PengumumanService {
  // Singleton pattern
  static final PengumumanService _instance = PengumumanService._internal();

  factory PengumumanService() => _instance;

  PengumumanService._internal();

  Future<List<PengumumanModel>> getPengumuman() async {
    try {
      print('Fetching announcements from: ${ApiConfig.baseUrl}/pengumuman');

      final response = await http.get(
        Uri.parse('${ApiConfig.baseUrl}/pengumuman'),
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
        },
      );

      print('Response status: ${response.statusCode}');

      if (response.statusCode == 200) {
        final Map<String, dynamic> jsonResponse = jsonDecode(response.body);
        
        if (jsonResponse['data'] != null) {
          final List<dynamic> pengumumanList = jsonResponse['data'];
          print('Found ${pengumumanList.length} announcements');

          final announcements = pengumumanList
              .map((item) => PengumumanModel.fromJson(item))
              .toList();

          print('Successfully parsed announcements');
          return announcements;
        }
        throw Exception('Data pengumuman tidak ditemukan');
      }
      throw Exception('Gagal memuat pengumuman. Status: ${response.statusCode}');
      
    } on SocketException {
      throw Exception('Tidak dapat terhubung ke server. Periksa koneksi internet Anda.');
    } on HttpException {
      throw Exception('Tidak dapat mengambil data pengumuman.');
    } on FormatException {
      throw Exception('Format data pengumuman tidak valid.');
    } catch (e) {
      print('Error in getPengumuman: $e');
      throw Exception('Terjadi kesalahan: ${e.toString()}');
    }
  }
}