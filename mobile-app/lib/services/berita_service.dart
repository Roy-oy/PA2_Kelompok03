import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:mobile_puskesmas/models/berita_model.dart';
import 'package:mobile_puskesmas/config/api_config.dart';

class BeritaService {
  Future<List<Berita>> getBerita() async {
    final response = await http.get(
      Uri.parse('${ApiConfig.baseUrl}/berita'),
    );

    if (response.statusCode == 200) {
      final Map<String, dynamic> responseData = json.decode(response.body);
      final List<dynamic> data = responseData['data']['data'];
      return data.map((json) => Berita.fromJson(json)).toList();
    } else {
      throw Exception('Gagal memuat berita');
    }
  }

  Future<Berita> getBeritaById(int id) async {
    final response = await http.get(
      Uri.parse('${ApiConfig.baseUrl}/berita/$id'),
    );

    if (response.statusCode == 200) {
      final Map<String, dynamic> responseData = json.decode(response.body);
      return Berita.fromJson(responseData['data']);
    } else {
      throw Exception('Gagal memuat detail berita');
    }
  }

  Future<List<Berita>> getBeritaByKategori(int kategoriId) async {
    final response = await http.get(
      Uri.parse('${ApiConfig.baseUrl}/berita/kategori/$kategoriId'),
    );

    if (response.statusCode == 200) {
      final Map<String, dynamic> responseData = json.decode(response.body);
      final List<dynamic> data = responseData['data']['data'];
      return data.map((json) => Berita.fromJson(json)).toList();
    } else {
      throw Exception('Gagal memuat berita berdasarkan kategori');
    }
  }

  Future<List<Berita>> searchBerita(String query) async {
    final response = await http.get(
      Uri.parse('${ApiConfig.baseUrl}/berita/search?query=$query'),
    );

    if (response.statusCode == 200) {
      final Map<String, dynamic> responseData = json.decode(response.body);
      final List<dynamic> data = responseData['data']['data'];
      return data.map((json) => Berita.fromJson(json)).toList();
    } else {
      throw Exception('Gagal mencari berita');
    }
  }

  Future<List<KategoriBerita>> getKategoriBerita() async {
    final response = await http.get(
      Uri.parse('${ApiConfig.baseUrl}/kategori-berita'),
    );

    if (response.statusCode == 200) {
      final Map<String, dynamic> responseData = json.decode(response.body);
      final List<dynamic> data = responseData['data'];
      return data.map((json) => KategoriBerita.fromJson(json)).toList();
    } else {
      throw Exception('Gagal memuat kategori berita');
    }
  }
}