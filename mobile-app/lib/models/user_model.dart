class UserModel {
  final int? id;
  final String? name;
  final String? email;
  final String? phone;
  final String? nik;
  final DateTime? dateOfBirth;
  final String? gender;
  final String? token;
  final bool isPatient;
  final PatientModel? patientData;

  UserModel({
    this.id,
    this.name,
    this.email,
    this.phone,
    this.nik,
    this.dateOfBirth,
    this.gender,
    this.token,
    this.isPatient = false,
    this.patientData,
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
      isPatient: json['is_patient'] ?? false,
      patientData: json['patient_data'] != null
          ? PatientModel.fromJson(json['patient_data'])
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
      'is_patient': isPatient,
    };
  }

  String getAge() {
    if (dateOfBirth == null) return '-';
    final now = DateTime.now();
    int age = now.year - dateOfBirth!.year;
    if (now.month < dateOfBirth!.month ||
        (now.month == dateOfBirth!.month && now.day < dateOfBirth!.day)) {
      age--;
    }
    return '$age tahun';
  }

  String getFormattedDateOfBirth() {
    if (dateOfBirth == null) return '-';
    return '${dateOfBirth!.day.toString().padLeft(2, '0')}-${dateOfBirth!.month.toString().padLeft(2, '0')}-${dateOfBirth!.year}';
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
    bool? isPatient,
    PatientModel? patientData,
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
      isPatient: isPatient ?? this.isPatient,
      patientData: patientData ?? this.patientData,
    );
  }
}

class PatientModel {
  final int? id;
  final String? noRm;
  final String? nama;
  final String? jenisKelamin;
  final String? tanggalLahir;
  final String? tempatLahir;
  final String? alamat;
  final String? noTelepon;
  final String? pekerjaan;
  final String? noBpjs;
  final String? golonganDarah;
  final String? rhesus;
  final String? statusPerkawinan;
  final String? agama;
  final String? pendidikan;
  final int? tinggiBadan;
  final int? beratBadan;
  final String? imt;
  final String? tekananDarah;
  final String? statusBpjs;
  final String? kelasRawat;
  final String? masaBerlakuBpjs;
  final String? riwayatAlergi;
  final String? riwayatPenyakit;

  PatientModel({
    this.id,
    this.noRm,
    this.nama,
    this.jenisKelamin,
    this.tanggalLahir,
    this.tempatLahir,
    this.alamat,
    this.noTelepon,
    this.pekerjaan,
    this.noBpjs,
    this.golonganDarah,
    this.rhesus,
    this.statusPerkawinan,
    this.agama,
    this.pendidikan,
    this.tinggiBadan,
    this.beratBadan,
    this.imt,
    this.tekananDarah,
    this.statusBpjs,
    this.kelasRawat,
    this.masaBerlakuBpjs,
    this.riwayatAlergi,
    this.riwayatPenyakit,
  });

  factory PatientModel.fromJson(Map<String, dynamic> json) {
    return PatientModel(
      id: json['id'],
      noRm: json['no_rm'],
      nama: json['nama'],
      jenisKelamin: json['jenis_kelamin'],
      tanggalLahir: json['tanggal_lahir'],
      tempatLahir: json['tempat_lahir'],
      alamat: json['alamat'],
      noTelepon: json['no_telepon'],
      pekerjaan: json['pekerjaan'],
      noBpjs: json['no_bpjs'],
      golonganDarah: json['golongan_darah'],
      rhesus: json['rhesus'],
      statusPerkawinan: json['status_perkawinan'],
      agama: json['agama'],
      pendidikan: json['pendidikan'],
      tinggiBadan: json['tinggi_badan'],
      beratBadan: json['berat_badan'],
      imt: json['imt'],
      tekananDarah: json['tekanan_darah'],
      statusBpjs: json['status_bpjs'],
      kelasRawat: json['kelas_rawat'],
      masaBerlakuBpjs: json['masa_berlaku_bpjs'],
      riwayatAlergi: json['riwayat_alergi'],
      riwayatPenyakit: json['riwayat_penyakit'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'no_rm': noRm,
      'nama': nama,
      'jenis_kelamin': jenisKelamin,
      'tanggal_lahir': tanggalLahir,
      'tempat_lahir': tempatLahir,
      'alamat': alamat,
      'no_telepon': noTelepon,
      'pekerjaan': pekerjaan,
      'no_bpjs': noBpjs,
      'golongan_darah': golonganDarah,
      'rhesus': rhesus,
      'status_perkawinan': statusPerkawinan,
      'agama': agama,
      'pendidikan': pendidikan,
      'tinggi_badan': tinggiBadan,
      'berat_badan': beratBadan,
      'imt': imt,
      'tekanan_darah': tekananDarah,
      'status_bpjs': statusBpjs,
      'kelas_rawat': kelasRawat,
      'masa_berlaku_bpjs': masaBerlakuBpjs,
      'riwayat_alergi': riwayatAlergi,
      'riwayat_penyakit': riwayatPenyakit,
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
