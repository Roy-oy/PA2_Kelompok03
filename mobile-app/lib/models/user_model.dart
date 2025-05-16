class UserModel {
  final int? id;
  final String? name;
  final String? email;
  final String? phone;
  final String? nik;
  final DateTime? dateOfBirth;
  final String? gender;
  final String? token;
  final bool isAppUser;
  final AppUserModel? appUserData;

  UserModel({
    this.id,
    this.name,
    this.email,
    this.phone,
    this.nik,
    this.dateOfBirth,
    this.gender,
    this.token,
    this.isAppUser = false,
    this.appUserData,
  });

  factory UserModel.fromJson(Map<String, dynamic> json) {
    return UserModel(
      id: json['id'],
      name: json['name'],
      email: json['email'],
      phone: json['phone'],
      nik: json['nik'],
      dateOfBirth: json['date_of_birth'] != null
          ? DateTime.parse(json['date_of_birth'])
          : null,
      gender: json['gender'],
      token: json['token'],
      isAppUser: json['is_app_user'] ?? false,
      appUserData: json['app_user_data'] != null
          ? AppUserModel.fromJson(json['app_user_data'])
          : null,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'email': email,
      'phone': phone,
      'nik': nik,
      'date_of_birth': dateOfBirth?.toIso8601String(),
      'gender': gender,
      'is_app_user': isAppUser,
      'app_user_data': appUserData?.toJson(),
    };
  }

  String getFormattedDateOfBirth() {
    if (dateOfBirth == null) return '-';
    return '${dateOfBirth!.day.toString().padLeft(2, '0')}/${dateOfBirth!.month.toString().padLeft(2, '0')}/${dateOfBirth!.year}';
  }

  UserModel copyWith({
    int? id,
    String? name,
    String? email,
    String? phone,
    String? nik,
    DateTime? dateOfBirth,
    String? gender,
    String? token,
    bool? isAppUser,
    AppUserModel? appUserData,
  }) {
    return UserModel(
      id: id ?? this.id,
      name: name ?? this.name,
      email: email ?? this.email,
      phone: phone ?? this.phone,
      nik: nik ?? this.nik,
      dateOfBirth: dateOfBirth ?? this.dateOfBirth,
      gender: gender ?? this.gender,
      token: token ?? this.token,
      isAppUser: isAppUser ?? this.isAppUser,
      appUserData: appUserData ?? this.appUserData,
    );
  }
}

class AppUserModel {
  final int? id;
  final String? nama;
  final String? jenisKelamin;
  final String? tanggalLahir;
  final String? alamat;
  final String? noTelepon;

  AppUserModel({
    this.id,
    this.nama,
    this.jenisKelamin,
    this.tanggalLahir,
    this.alamat,
    this.noTelepon,
  });

  factory AppUserModel.fromJson(Map<String, dynamic> json) {
    return AppUserModel(
      id: json['id'],
      nama: json['nama'],
      jenisKelamin: json['jenis_kelamin'],
      tanggalLahir: json['tanggal_lahir'],
      alamat: json['alamat'],
      noTelepon: json['no_telepon'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'nama': nama,
      'jenis_kelamin': jenisKelamin,
      'tanggal_lahir': tanggalLahir,
      'alamat': alamat,
      'no_telepon': noTelepon,
    };
  }

  String? getFormattedTanggalLahir() {
    if (tanggalLahir == null) return null;

    try {
      final date = DateTime.parse(tanggalLahir!);
      return '${date.day}/${date.month}/${date.year}';
    } catch (e) {
      return tanggalLahir;
    }
  }
}