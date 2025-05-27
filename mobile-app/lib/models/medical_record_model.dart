import 'package:intl/intl.dart';
import 'package:json_annotation/json_annotation.dart';

class MedicalRecordModel {
  final int? id;
  final int? pendaftaranId;
  final int? pasienId;
  @JsonKey(name: 'tanggal_kunjungan')
  final DateTime? tanggalKunjungan;
  final String? keluhan;
  final String? diagnosis;
  final String? pengobatan;
  @JsonKey(name: 'hasil_pemeriksaan')
  final String? hasilPemeriksaan;
  @JsonKey(name: 'tinggi_badan')
  final double? tinggiBadan;
  @JsonKey(name: 'berat_badan')
  final double? beratBadan;
  @JsonKey(name: 'tekanan_darah')
  final String? tekananDarah;
  @JsonKey(name: 'suhu_badan')
  final double? suhuBadan;
  final PasienModel? pasien;
  final PendaftaranModel? pendaftaran;
  final DateTime? createdAt;
  final DateTime? updatedAt;

  MedicalRecordModel({
    this.id,
    this.pendaftaranId,
    this.pasienId,
    this.tanggalKunjungan,
    this.keluhan,
    this.diagnosis,
    this.pengobatan,
    this.hasilPemeriksaan,
    this.tinggiBadan,
    this.beratBadan,
    this.tekananDarah,
    this.suhuBadan,
    this.pasien,
    this.pendaftaran,
    this.createdAt,
    this.updatedAt,
  });

  factory MedicalRecordModel.fromJson(Map<String, dynamic> json) {
    return MedicalRecordModel(
      id: json['id'],
      pendaftaranId: json['pendaftaran_id'],
      pasienId: json['pasien_id'],
      tanggalKunjungan: json['tanggal_kunjungan'] != null
          ? DateTime.parse(json['tanggal_kunjungan'])
          : null,
      keluhan: json['keluhan'],
      diagnosis: json['diagnosis'],
      pengobatan: json['pengobatan'],
      hasilPemeriksaan: json['hasil_pemeriksaan'],
      tinggiBadan: (json['tinggi_badan'] as num?)?.toDouble(),
      beratBadan: (json['berat_badan'] as num?)?.toDouble(),
      tekananDarah: json['tekanan_darah'],
      suhuBadan: (json['suhu_badan'] as num?)?.toDouble(),
      pasien: json['pasien'] != null
          ? PasienModel.fromJson(json['pasien'])
          : null,
      pendaftaran: json['pendaftaran'] != null
          ? PendaftaranModel.fromJson(json['pendaftaran'])
          : null,
      createdAt: json['created_at'] != null
          ? DateTime.parse(json['created_at'])
          : null,
      updatedAt: json['updated_at'] != null
          ? DateTime.parse(json['updated_at'])
          : null,
    );
  }

  get tindakan => null;

  get catatan => null;

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'pendaftaran_id': pendaftaranId,
      'pasien_id': pasienId,
      'tanggal_kunjungan': tanggalKunjungan?.toIso8601String(),
      'keluhan': keluhan,
      'diagnosis': diagnosis,
      'pengobatan': pengobatan,
      'hasil_pemeriksaan': hasilPemeriksaan,
      'tinggi_badan': tinggiBadan,
      'berat_badan': beratBadan,
      'tekanan_darah': tekananDarah,
      'suhu_badan': suhuBadan,
      'pasien': pasien?.toJson(),
      'pendaftaran': pendaftaran?.toJson(),
      'created_at': createdAt?.toIso8601String(),
      'updated_at': updatedAt?.toIso8601String(),
    };
  }

  String getFormattedTanggalKunjungan() {
    if (tanggalKunjungan == null) return '-';
    return DateFormat('dd/MM/yyyy').format(tanggalKunjungan!);
  }

  String getFormattedPasienNama() {
    return pasien?.nama ?? '-';
  }

  String getFormattedPendaftaranTanggalDaftar() {
    return pendaftaran?.tanggalDaftar != null
        ? DateFormat('dd/MM/yyyy').format(pendaftaran!.tanggalDaftar!)
        : '-';
  }

  MedicalRecordModel copyWith({
    int? id,
    int? pendaftaranId,
    int? pasienId,
    DateTime? tanggalKunjungan,
    String? keluhan,
    String? diagnosis,
    String? pengobatan,
    String? hasilPemeriksaan,
    double? tinggiBadan,
    double? beratBadan,
    String? tekananDarah,
    double? suhuBadan,
    PasienModel? pasien,
    PendaftaranModel? pendaftaran,
    DateTime? createdAt,
    DateTime? updatedAt,
  }) {
    return MedicalRecordModel(
      id: id ?? this.id,
      pendaftaranId: pendaftaranId ?? this.pendaftaranId,
      pasienId: pasienId ?? this.pasienId,
      tanggalKunjungan: tanggalKunjungan ?? this.tanggalKunjungan,
      keluhan: keluhan ?? this.keluhan,
      diagnosis: diagnosis ?? this.diagnosis,
      pengobatan: pengobatan ?? this.pengobatan,
      hasilPemeriksaan: hasilPemeriksaan ?? this.hasilPemeriksaan,
      tinggiBadan: tinggiBadan ?? this.tinggiBadan,
      beratBadan: beratBadan ?? this.beratBadan,
      tekananDarah: tekananDarah ?? this.tekananDarah,
      suhuBadan: suhuBadan ?? this.suhuBadan,
      pasien: pasien ?? this.pasien,
      pendaftaran: pendaftaran ?? this.pendaftaran,
      createdAt: createdAt ?? this.createdAt,
      updatedAt: updatedAt ?? this.updatedAt,
    );
  }
}

class PasienModel {
  final int? id;
  final String? nik;
  final String? noKk;
  final String? nama;
  final int? appUserId;
  final String? jenisKelamin;
  final DateTime? tanggalLahir;
  final String? tempatLahir;
  final String? alamat;
  final String? noHp;
  final String? pekerjaan;
  final String? noBpjs;
  final String? golonganDarah;
  final String? riwayatAlergi;
  final String? riwayatPenyakit;
  final String? noRm;

  PasienModel({
    this.id,
    this.nik,
    this.noKk,
    this.nama,
    this.appUserId,
    this.jenisKelamin,
    this.tanggalLahir,
    this.tempatLahir,
    this.alamat,
    this.noHp,
    this.pekerjaan,
    this.noBpjs,
    this.golonganDarah,
    this.riwayatAlergi,
    this.riwayatPenyakit,
    this.noRm,
  });

  factory PasienModel.fromJson(Map<String, dynamic> json) {
    return PasienModel(
      id: json['id'],
      nik: json['nik'],
      noKk: json['no_kk'],
      nama: json['nama'],
      appUserId: json['app_user_id'],
      jenisKelamin: json['jenis_kelamin'],
      tanggalLahir: json['tanggal_lahir'] != null
          ? DateTime.parse(json['tanggal_lahir'])
          : null,
      tempatLahir: json['tempat_lahir'],
      alamat: json['alamat'],
      noHp: json['no_hp'],
      pekerjaan: json['pekerjaan'],
      noBpjs: json['no_bpjs'],
      golonganDarah: json['golongan_darah'],
      riwayatAlergi: json['riwayat_alergi'],
      riwayatPenyakit: json['riwayat_penyakit'],
      noRm: json['no_rm'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'nik': nik,
      'no_kk': noKk,
      'nama': nama,
      'app_user_id': appUserId,
      'jenis_kelamin': jenisKelamin,
      'tanggal_lahir': tanggalLahir?.toIso8601String(),
      'tempat_lahir': tempatLahir,
      'alamat': alamat,
      'no_hp': noHp,
      'pekerjaan': pekerjaan,
      'no_bpjs': noBpjs,
      'golongan_darah': golonganDarah,
      'riwayat_alergi': riwayatAlergi,
      'riwayat_penyakit': riwayatPenyakit,
      'no_rm': noRm,
    };
  }
}

class PendaftaranModel {
  final int? id;
  final int? pasienId;
  final String? jenisPasien;
  final String? jenisPembayaran;
  final String? keluhan;
  final int? clusterId;
  final DateTime? tanggalDaftar;
  final String? status;
  final PasienModel? pasien;
  final ClusterModel? cluster;
  final AntrianModel? antrian;
  final DateTime? createdAt;
  final DateTime? updatedAt;

  PendaftaranModel({
    this.id,
    this.pasienId,
    this.jenisPasien,
    this.jenisPembayaran,
    this.keluhan,
    this.clusterId,
    this.tanggalDaftar,
    this.status,
    this.pasien,
    this.cluster,
    this.antrian,
    this.createdAt,
    this.updatedAt,
  });

  factory PendaftaranModel.fromJson(Map<String, dynamic> json) {
    return PendaftaranModel(
      id: json['id'],
      pasienId: json['pasien_id'],
      jenisPasien: json['jenis_pasien'],
      jenisPembayaran: json['jenis_pembayaran'],
      keluhan: json['keluhan'],
      clusterId: json['cluster_id'],
      tanggalDaftar: json['tanggal_daftar'] != null
          ? DateTime.parse(json['tanggal_daftar'])
          : null,
      status: json['status'],
      pasien: json['pasien'] != null
          ? PasienModel.fromJson(json['pasien'])
          : null,
      cluster: json['cluster'] != null
          ? ClusterModel.fromJson(json['cluster'])
          : null,
      antrian: json['antrian'] != null
          ? AntrianModel.fromJson(json['antrian'])
          : null,
      createdAt: json['created_at'] != null
          ? DateTime.parse(json['created_at'])
          : null,
      updatedAt: json['updated_at'] != null
          ? DateTime.parse(json['updated_at'])
          : null,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'pasien_id': pasienId,
      'jenis_pasien': jenisPasien,
      'jenis_pembayaran': jenisPembayaran,
      'keluhan': keluhan,
      'cluster_id': clusterId,
      'tanggal_daftar': tanggalDaftar?.toIso8601String(),
      'status': status,
      'pasien': pasien?.toJson(),
      'cluster': cluster?.toJson(),
      'antrian': antrian?.toJson(),
      'created_at': createdAt?.toIso8601String(),
      'updated_at': updatedAt?.toIso8601String(),
    };
  }

  String getFormattedTanggalDaftar() {
    if (tanggalDaftar == null) return '-';
    return DateFormat('dd/MM/yyyy').format(tanggalDaftar!);
  }

  String getFormattedNoAntrian() {
    return antrian?.noAntrian ?? '-';
  }

  String getFormattedClusterNama() {
    return cluster?.nama ?? '-';
  }

  String getFormattedPasienNama() {
    return pasien?.nama ?? '-';
  }

  PendaftaranModel copyWith({
    int? id,
    int? pasienId,
    String? jenisPasien,
    String? jenisPembayaran,
    String? keluhan,
    int? clusterId,
    DateTime? tanggalDaftar,
    String? status,
    PasienModel? pasien,
    ClusterModel? cluster,
    AntrianModel? antrian,
    DateTime? createdAt,
    DateTime? updatedAt,
  }) {
    return PendaftaranModel(
      id: id ?? this.id,
      pasienId: pasienId ?? this.pasienId,
      jenisPasien: jenisPasien ?? this.jenisPasien,
      jenisPembayaran: jenisPembayaran ?? this.jenisPembayaran,
      keluhan: keluhan ?? this.keluhan,
      clusterId: clusterId ?? this.clusterId,
      tanggalDaftar: tanggalDaftar ?? this.tanggalDaftar,
      status: status ?? this.status,
      pasien: pasien ?? this.pasien,
      cluster: cluster ?? this.cluster,
      antrian: antrian ?? this.antrian,
      createdAt: createdAt ?? this.createdAt,
      updatedAt: updatedAt ?? this.updatedAt,
    );
  }
}

class ClusterModel {
  final int? id;
  final String? nama;

  ClusterModel({this.id, this.nama});

  factory ClusterModel.fromJson(Map<String, dynamic> json) {
    return ClusterModel(
      id: json['id'],
      nama: json['nama'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'nama': nama,
    };
  }
}

class AntrianModel {
  final int? id;
  final int? pendaftaranId;
  final int? clusterId;
  final String? noAntrian;
  final DateTime? tanggal;
  final String? status;

  AntrianModel({
    this.id,
    this.pendaftaranId,
    this.clusterId,
    this.noAntrian,
    this.tanggal,
    this.status,
  });

  factory AntrianModel.fromJson(Map<String, dynamic> json) {
    return AntrianModel(
      id: json['id'],
      pendaftaranId: json['pendaftaran_id'],
      clusterId: json['cluster_id'],
      noAntrian: json['no_antrian'],
      tanggal: json['tanggal'] != null
          ? DateTime.parse(json['tanggal'])
          : null,
      status: json['status'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'pendaftaran_id': pendaftaranId,
      'cluster_id': clusterId,
      'no_antrian': noAntrian,
      'tanggal': tanggal?.toIso8601String(),
      'status': status,
    };
  }
}