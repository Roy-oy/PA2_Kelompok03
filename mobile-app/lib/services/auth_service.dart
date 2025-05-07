import 'dart:convert';
import 'dart:io';
import 'dart:math' as math;
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

  // User login
  Future<UserModel?> login(String email, String password) async {
    try {
      final response = await http.post(
        Uri.parse('${ApiConfig.baseUrl}/login'),
        headers: {'Content-Type': 'application/json'},
        body: jsonEncode({
          'email': email,
          'password': password,
        }),
      );

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        final user = UserModel.fromJson(data['data']);

        // Save user data to SharedPreferences
        await _saveUserData(user);

        return user;
      } else {
        final data = jsonDecode(response.body);
        throw Exception(data['message'] ?? 'Login gagal');
      }
    } on SocketException {
      throw Exception(
          'Tidak dapat terhubung ke server. Pastikan server sedang berjalan dan alamat IP sudah benar.');
    } on HttpException {
      throw Exception('Terjadi kesalahan HTTP saat menghubungi server.');
    } on FormatException {
      throw Exception('Format respons dari server tidak valid.');
    } catch (e) {
      throw Exception('Terjadi kesalahan: ${e.toString()}');
    }
  }

  // User login with NIK
  Future<UserModel?> loginWithNik(String nik, String password) async {
    try {
      print('Attempting to login with NIK: $nik');

      final response = await http.post(
        Uri.parse('${ApiConfig.baseUrl}/login-with-nik'),
        headers: {'Content-Type': 'application/json'},
        body: jsonEncode({
          'nik': nik,
          'password': password,
        }),
      );

      print('Login response status: ${response.statusCode}');

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        final user = UserModel.fromJson(data['data']);

        // Save user data to SharedPreferences
        await _saveUserData(user);

        return user;
      } else {
        final data = jsonDecode(response.body);
        throw Exception(data['message'] ?? 'Login gagal');
      }
    } catch (e) {
      throw Exception('Terjadi kesalahan saat login: ${e.toString()}');
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
      final userData = {
        'name': name,
        'email': email,
        'password': password,
        'password_confirmation': passwordConfirmation,
        'phone': phone,
        'nik': nik,
      };

      print('Attempting to register with data: ${jsonEncode(userData)}');

      final response = await http.post(
        Uri.parse('${ApiConfig.baseUrl}/register'),
        headers: {'Content-Type': 'application/json'},
        body: jsonEncode(userData),
      );

      print('Registration response status: ${response.statusCode}');
      print('Registration response body: ${response.body}');

      if (response.statusCode == 201) {
        final data = jsonDecode(response.body);
        final user = UserModel.fromJson(data['data']);

        // Save user data to SharedPreferences
        await _saveUserData(user);

        return user;
      } else {
        Map<String, dynamic> responseData;
        try {
          responseData = jsonDecode(response.body);
        } catch (e) {
          responseData = {'message': 'Tidak dapat memproses respon server'};
        }

        String errorMessage = responseData['message'] ?? 'Registrasi gagal';
        if (responseData.containsKey('errors')) {
          final errors = responseData['errors'];
          errorMessage += ': ${errors.toString()}';
        }

        if (responseData.containsKey('error')) {
          errorMessage += ' - ${responseData['error']}';
        }

        throw Exception(errorMessage);
      }
    } on SocketException {
      throw Exception(
          'Tidak dapat terhubung ke server. Pastikan server sedang berjalan dan alamat IP sudah benar.');
    } on HttpException {
      throw Exception('Terjadi kesalahan HTTP saat menghubungi server.');
    } on FormatException {
      throw Exception('Format respons dari server tidak valid.');
    } catch (e) {
      throw Exception('Terjadi kesalahan: ${e.toString()}');
    }
  }

  // Register as patient
  Future<UserModel?> registerAsPatient(Map<String, dynamic> patientData) async {
    try {
      final user = await getUserData();

      if (user == null) {
        throw Exception('User tidak ditemukan');
      }

      print(
          'Making API request to register-as-patient with token: ${user.token?.substring(0, 10)}...');

      final response = await http.post(
        Uri.parse('${ApiConfig.baseUrl}/register-as-patient'),
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer ${user.token}',
        },
        body: jsonEncode(patientData),
      );

      print('Register-as-patient response status: ${response.statusCode}');
      print('Register-as-patient response body: ${response.body}');

      if (response.statusCode == 201) {
        final data = jsonDecode(response.body);
        final userData = data['data'];

        print(
            'Successfully registered as patient. Patient data received: ${userData['patient'] != null}');

        // Create updated user model
        final updatedUser = user.copyWith(
          isPatient: userData['is_patient'] ?? false,
          patientData: userData['patient'] != null
              ? PatientModel.fromJson(userData['patient'])
              : null,
        );

        print('Updated user model - Is patient: ${updatedUser.isPatient}');
        print(
            'Updated user model - Has patient data: ${updatedUser.patientData != null}');

        if (updatedUser.patientData != null) {
          print('Patient data fields:');
          print('- ID: ${updatedUser.patientData!.id}');
          print('- RM: ${updatedUser.patientData!.noRm}');
          print('- Name: ${updatedUser.patientData!.nama}');
          print('- Address: ${updatedUser.patientData!.alamat}');
        }

        // Save updated user data to SharedPreferences
        await _saveUserData(updatedUser);
        print('Updated user data saved to SharedPreferences');

        return updatedUser;
      } else {
        final data = jsonDecode(response.body);
        String errorMessage = data['message'] ?? 'Pendaftaran pasien gagal';

        if (data.containsKey('errors')) {
          final errors = data['errors'];
          print('Validation errors: $errors');

          // Format validation errors for better readability
          List<String> errorList = [];
          if (errors is Map) {
            errors.forEach((key, value) {
              if (value is List) {
                errorList.add('$key: ${value.join(", ")}');
              } else {
                errorList.add('$key: $value');
              }
            });
          }

          if (errorList.isNotEmpty) {
            errorMessage += ': ${errorList.join("; ")}';
          }
        }

        throw Exception(errorMessage);
      }
    } on SocketException {
      print('Socket exception when registering as patient - connection issue');
      throw Exception(
          'Tidak dapat terhubung ke server. Pastikan server sedang berjalan dan alamat IP sudah benar.');
    } on HttpException {
      print('HTTP exception when registering as patient');
      throw Exception('Terjadi kesalahan HTTP saat menghubungi server.');
    } on FormatException {
      print(
          'Format exception when registering as patient - invalid response format');
      throw Exception('Format respons dari server tidak valid.');
    } catch (e) {
      print('Unknown exception when registering as patient: ${e.toString()}');
      throw Exception('Terjadi kesalahan: ${e.toString()}');
    }
  }

  // Get user profile
  Future<UserModel?> getProfile() async {
    try {
      final user = await getUserData();

      if (user == null) {
        print('Cannot get profile: No user data found in local storage');
        throw Exception('User tidak ditemukan');
      }

      print(
          'Making API request to get profile with token: ${user.token?.substring(0, 10)}...');

      final response = await http.get(
        Uri.parse('${ApiConfig.baseUrl}/profile'),
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer ${user.token}',
        },
      );

      print('Get profile response status: ${response.statusCode}');
      print(
          'Get profile response body preview: ${response.body.substring(0, math.min(response.body.length, 200))}...');

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);

        print('Successfully retrieved profile data');

        // Check if the user is a patient
        final isPatient = data['data']['is_patient'] ?? false;
        print('User is patient: $isPatient');

        // Check if patient data exists
        final hasPatientData = data['data']['patient_data'] != null;
        print('Has patient data: $hasPatientData');

        if (hasPatientData) {
          print(
              'Patient data preview: ${data['data']['patient_data'].toString().substring(0, math.min(data['data']['patient_data'].toString().length, 200))}...');
        }

        final updatedUser =
            UserModel.fromJson(data['data']).copyWith(token: user.token);

        print('Updated user model - Is patient: ${updatedUser.isPatient}');
        print(
            'Updated user model - Has patient data: ${updatedUser.patientData != null}');

        if (updatedUser.patientData != null) {
          print('Patient data fields from profile:');
          print('- ID: ${updatedUser.patientData!.id}');
          print('- RM: ${updatedUser.patientData!.noRm}');
          print('- Name: ${updatedUser.patientData!.nama}');
          print('- Address: ${updatedUser.patientData!.alamat}');
          print('- Rhesus: ${updatedUser.patientData!.rhesus}');
          print(
              '- Status Perkawinan: ${updatedUser.patientData!.statusPerkawinan}');
          print('- Agama: ${updatedUser.patientData!.agama}');
          print('- Pendidikan: ${updatedUser.patientData!.pendidikan}');
          print('- Riwayat Alergi: ${updatedUser.patientData!.riwayatAlergi}');
          print(
              '- Riwayat Penyakit: ${updatedUser.patientData!.riwayatPenyakit}');
        }

        // Save updated user data to SharedPreferences
        await _saveUserData(updatedUser);
        print('Updated profile data saved to SharedPreferences');

        return updatedUser;
      } else {
        final data = jsonDecode(response.body);
        print('Failed to get profile: ${data['message']}');
        throw Exception(data['message'] ?? 'Gagal mendapatkan profil');
      }
    } on SocketException {
      print('Socket exception when getting profile - connection issue');
      throw Exception(
          'Tidak dapat terhubung ke server. Pastikan server sedang berjalan dan alamat IP sudah benar.');
    } on HttpException {
      print('HTTP exception when getting profile');
      throw Exception('Terjadi kesalahan HTTP saat menghubungi server.');
    } on FormatException {
      print('Format exception when getting profile - invalid response format');
      throw Exception('Format respons dari server tidak valid.');
    } catch (e) {
      print('Unknown exception when getting profile: ${e.toString()}');
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

  // Check if user is a patient
  Future<bool> isPatient() async {
    final user = await getUserData();
    return user != null && user.isPatient;
  }

  Future<String?> getToken() async {
    final user = await getUserData();
    return user?.token;
  }
}
