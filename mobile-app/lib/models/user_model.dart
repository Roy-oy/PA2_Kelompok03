class UserModel {
  final int? id;
  final String? name;
  final String? email;
  final String? noHp;
  final DateTime? tanggalLahir;
  final String? alamat;
  final String? jenisKelamin;
  final String? token;
  final bool isAppUser;
  final AppUserModel? appUserData;

  UserModel({
    this.id,
    this.name,
    this.email,
    this.noHp,
    this.tanggalLahir,
    this.alamat,
    this.jenisKelamin,
    this.token,
    this.isAppUser = false,
    this.appUserData,
  });

  factory UserModel.fromJson(Map<String, dynamic> json) {
    return UserModel(
      id: json['id'],
      name: json['name'],
      email: json['email'],
      noHp: json['no_hp'],
      tanggalLahir: json['tanggal_lahir'] != null
          ? DateTime.parse(json['tanggal_lahir'])
          : null,
      alamat: json['alamat'],
      jenisKelamin: json['jenis_kelamin'],
      token: json['token'],
      isAppUser: json['is_app_user'] ?? false,
      appUserData: json['app_user_data'] != null
          ? AppUserModel.fromJson(json['app_user_data'])
          : null,
    );
  }

  get phoneNumber => null;

  get nik => null;

  get phone => null;

  String? get gender => null;

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'email': email,
      'no_hp': noHp,
      'tanggal_lahir': tanggalLahir?.toIso8601String(),
      'alamat': alamat,
      'jenis_kelamin': jenisKelamin,
      'token': token,
      'is_app_user': isAppUser,
      'app_user_data': appUserData?.toJson(),
    };
  }

  String getFormattedTanggalLahir() {
    if (tanggalLahir == null) return '-';
    return '${tanggalLahir!.day.toString().padLeft(2, '0')}/${tanggalLahir!.month.toString().padLeft(2, '0')}/${tanggalLahir!.year}';
  }

  UserModel copyWith({
    int? id,
    String? name,
    String? email,
    String? noHp,
    DateTime? tanggalLahir,
    String? alamat,
    String? jenisKelamin,
    String? token,
    bool? isAppUser,
    AppUserModel? appUserData,
  }) {
    return UserModel(
      id: id ?? this.id,
      name: name ?? this.name,
      email: email ?? this.email,
      noHp: noHp ?? this.noHp,
      tanggalLahir: tanggalLahir ?? this.tanggalLahir,
      alamat: alamat ?? this.alamat,
      jenisKelamin: jenisKelamin ?? this.jenisKelamin,
      token: token ?? this.token,
      isAppUser: isAppUser ?? this.isAppUser,
      appUserData: appUserData ?? this.appUserData,
    );
  }
}

class AppUserModel {
  final int? id;
  final String? name;
  final String? jenisKelamin;
  final DateTime? tanggalLahir;
  final String? alamat;
  final String? noHp;

  AppUserModel({
    this.id,
    this.name,
    this.jenisKelamin,
    this.tanggalLahir,
    this.alamat,
    this.noHp,
  });

  factory AppUserModel.fromJson(Map<String, dynamic> json) {
    return AppUserModel(
      id: json['id'],
      name: json['name'],
      jenisKelamin: json['jenis_kelamin'],
      tanggalLahir: json['tanggal_lahir'] != null
          ? DateTime.parse(json['tanggal_lahir'])
          : null,
      alamat: json['alamat'],
      noHp: json['no_hp'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'jenis_kelamin': jenisKelamin,
      'tanggal_lahir': tanggalLahir?.toIso8601String(),
      'alamat': alamat,
      'no_hp': noHp,
    };
  }

  String? getFormattedTanggalLahir() {
    if (tanggalLahir == null) return null;
    return '${tanggalLahir!.day}/${tanggalLahir!.month}/${tanggalLahir!.year}';
  }
}