import 'dart:convert';
import 'dart:io';
import 'package:http/http.dart' as http;
import 'package:mobile_puskesmas/config/api_config.dart';
import 'package:mobile_puskesmas/models/faq_model.dart';

class FaqService {
  static final FaqService _instance = FaqService._internal();

  factory FaqService() => _instance;
  
  FaqService._internal();

  Future<List<FaqModel>> getFaq() async {
    try {
      print('Fetching FAQ from: ${ApiConfig.baseUrl}/faq');

      final response = await http.get(
        Uri.parse('${ApiConfig.baseUrl}/faq'),
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
        },
      );

      print('Response status: ${response.statusCode}');

      if (response.statusCode == 200) {
        final Map<String, dynamic> jsonResponse = jsonDecode(response.body);

        if (jsonResponse['data'] != null) {
          final List<dynamic> faqList = jsonResponse['data'];
          print('Found ${faqList.length} FAQ');

          final faq = faqList
              .map((item) => FaqModel.fromJson(item))
              .toList();

          print('Successfully parsed FAQ');
          return faq;
        }
        throw Exception('Data FAQ tidak ditemukan');
      }
      throw Exception('Gagal memuat FAQ. Status: ${response.statusCode}');
    } on SocketException {
      throw Exception('Tidak dapat terhubung ke server. Periksa koneksi internet Anda.');
    } on HttpException {
      throw Exception('Tidak dapat mengambil data FAQ.');
    } on FormatException {
      throw Exception('Format data FAQ tidak valid.');
    } catch (e) {
      print('Error in getFaq: $e');
      throw Exception('Terjadi kesalahan: ${e.toString()}');
    }
  }

  Future<FaqModel> getFaqDetail(int faqId) async {
    try {
      print('Fetching FAQ detail from: ${ApiConfig.baseUrl}/faq/$faqId');

      final response = await http.get(
        Uri.parse('${ApiConfig.baseUrl}/faq/$faqId'),
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
        },
      );

      print('Response status: ${response.statusCode}');

      if (response.statusCode == 200) {
        final Map<String, dynamic> jsonResponse = jsonDecode(response.body);

        if (jsonResponse['data'] != null) {
          final faqDetail = FaqModel.fromJson(jsonResponse['data']);
          print('Successfully parsed FAQ detail');
          return faqDetail;
        }
        throw Exception('Data FAQ tidak ditemukan');
      }
      throw Exception('Gagal memuat FAQ. Status: ${response.statusCode}');
    } on SocketException {
      throw Exception('Tidak dapat terhubung ke server. Periksa koneksi internet Anda.');
    } on HttpException {
      throw Exception('Tidak dapat mengambil data FAQ.');
    } on FormatException {
      throw Exception('Format data FAQ tidak valid.');
    } catch (e) {
      print('Error in getFaqDetail: $e');
      throw Exception('Terjadi kesalahan: ${e.toString()}');
    }
  }
}