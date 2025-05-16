import 'dart:convert';
import 'dart:io';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import 'package:mobile_puskesmas/models/user_model.dart';
import 'package:mobile_puskesmas/config/api_config.dart';

class AuthService {
  // Key for storing user data in SharedPreferences
  static const String userKey = 'user_data';

  // Singleton pattern
  static final AuthService _instance = AuthService._internal();

  factory AuthService() => _instance;

  AuthService._internal();

  // Validate email to ensure it uses @gmail.com
  void _validateEmail(String email) {
    if (!email.endsWith('@gmail.com')) {
      throw Exception('Email harus menggunakan domain @gmail.com');
    }
  }

  // User login with email
  Future<UserModel?> login(String email, String password) async {
    try {
      _validateEmail(email);

      final response = await http.post(
        Uri.parse('${ApiConfig.baseUrl}/login'),
        headers: {'Content-Type': 'application/json'},
        body: jsonEncode({
          'email': email,
          'password': password,
        }),
      );

      final data = jsonDecode(response.body);
      if (response.statusCode == 200 && data['success'] == true) {
        final user = UserModel.fromJson(data['data']);
        await _saveUserData(user);
        return user;
      } else {
        throw Exception(data['message'] ?? 'Login gagal');
      }
    } on SocketException {
      throw Exception('Tidak dapat terhubung ke server. Periksa koneksi Anda.');
    } on HttpException {
      throw Exception('Terjadi kesalahan HTTP saat login.');
    } on FormatException {
      throw Exception('Format respons server tidak valid.');
    } catch (e) {
      throw Exception('Terjadi kesalahan: ${e.toString()}');
    }
  }

  // User registration
  Future<UserModel?> register(
    String name,
    String email,
    String password,
    String passwordConfirmation,
    String phone,
    String nik,
  ) async {
    try {
      _validateEmail(email);

      final userData = {
        'name': name,
        'email': email,
        'password': password,
        'password_confirmation': passwordConfirmation,
        'phone': phone,
        'nik': nik,
      };

      final response = await http.post(
        Uri.parse('${ApiConfig.baseUrl}/register'),
        headers: {'Content-Type': 'application/json'},
        body: jsonEncode(userData),
      );

      final data = jsonDecode(response.body);
      if (response.statusCode == 201 && data['success'] == true) {
        final user = UserModel.fromJson(data['data']);
        await _saveUserData(user);
        return user;
      } else {
        String errorMessage = data['message'] ?? 'Registrasi gagal';
        if (data['errors'] != null) {
          errorMessage += ': ${data['errors'].toString()}';
        }
        throw Exception(errorMessage);
      }
    } on SocketException {
      throw Exception('Tidak dapat terhubung ke server. Periksa koneksi Anda.');
    } on HttpException {
      throw Exception('Terjadi kesalahan HTTP saat registrasi.');
    } on FormatException {
      throw Exception('Format respons server tidak valid.');
    } catch (e) {
      throw Exception('Terjadi kesalahan: ${e.toString()}');
    }
  }

  // Register as app user
  Future<UserModel?> registerAsAppUser(Map<String, dynamic> appUserData) async {
    try {
      final user = await getUserData();

      if (user == null) {
        throw Exception('User tidak ditemukan');
      }

      final response = await http.post(
        Uri.parse('${ApiConfig.baseUrl}/register-as-app-user'),
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer ${user.token}',
        },
        body: jsonEncode(appUserData),
      );

      final data = jsonDecode(response.body);
      if (response.statusCode == 201 && data['success'] == true) {
        final updatedUser = user.copyWith(
          isAppUser: data['data']['is_app_user'] ?? false,
          appUserData: data['data']['app_user_data'] != null
              ? AppUserModel.fromJson(data['data']['app_user_data'])
              : null,
        );

        await _saveUserData(updatedUser);
        return updatedUser;
      } else {
        String errorMessage = data['message'] ?? 'P	urlah pengguna aplikasi gagal';
        if (data['errors'] != null) {
          errorMessage += ': ${data['errors'].toString()}';
        }
        throw Exception(errorMessage);
      }
    } on SocketException {
      throw Exception('Tidak dapat terhubung ke server. Periksa koneksi Anda.');
    } on HttpException {
      throw Exception('Terjadi kesalahan HTTP saat pendaftaran pengguna aplikasi.');
    } on FormatException {
      throw Exception('Format respons server tidak valid.');
    } catch (e) {
      throw Exception('Terjadi kesalahan: ${e.toString()}');
    }
  }

  // Get user profile
  Future<UserModel?> getProfile() async {
    try {
      final user = await getUserData();

      if (user == null) {
        throw Exception('User tidak ditemukan');
      }

      final response = await http.get(
        Uri.parse('${ApiConfig.baseUrl}/profile'),
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer ${user.token}',
        },
      );

      final data = jsonDecode(response.body);
      if (response.statusCode == 200 && data['success'] == true) {
        final updatedUser = UserModel.fromJson(data['data']).copyWith(token: user.token);
        await _saveUserData(updatedUser);
        return updatedUser;
      } else {
        throw Exception(data['message'] ?? 'Gagal mendapatkan profil');
      }
    } on SocketException {
      throw Exception('Tidak dapat terhubung ke server. Periksa koneksi Anda.');
    } on HttpException {
      throw Exception('Terjadi kesalahan HTTP saat mengambil profil.');
    } on FormatException {
      throw Exception('Format respons server tidak valid.');
    } catch (e) {
      throw Exception('Terjadi kesalahan: ${e.toString()}');
    }
  }

  // Get user data from SharedPreferences
  Future<UserModel?> getUserData() async {
    final prefs = await SharedPreferences.getInstance();
    final userData = prefs.getString(userKey);

    if (userData != null) {
      return UserModel.fromJson(jsonDecode(userData));
    }

    return null;
  }

  // Save user data to SharedPreferences
  Future<void> _saveUserData(UserModel user) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString(
        userKey, jsonEncode(user.toJson()..addAll({'token': user.token})));
  }

  // Clear user data from SharedPreferences (logout)
  Future<void> logout() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove(userKey);
  }

  // Check if user is logged in
  Future<bool> isLoggedIn() async {
    final user = await getUserData();
    return user != null;
  }

  // Check if user is an app user
  Future<bool> isAppUser() async {
    final user = await getUserData();
    return user != null && user.isAppUser;
  }

  Future<String?> getToken() async {
    final user = await getUserData();
    return user?.token;
  }
}